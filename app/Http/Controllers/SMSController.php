<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;

class SMSController extends Controller
{
    function initiateRabbitMQConnection() {
        $rabbitMqHost = env('RABBITMQ_HOST', 'localhost');
        $rabbitMqPort = env('RABBITMQ_PORT', 5672);
        $rabbitMqVHost = env('RABBITMQ_VHOST', 'BLASTME');
        $rabbitMqUserName = env('RABBITMQ_USERNAME', 'chandra');
        $rabbitMqPassword = env('RABBITMQ_PASSWORD', 'Eliandri3');

        return new AMQPStreamConnection($rabbitMqHost, $rabbitMqPort, $rabbitMqUserName, $rabbitMqPassword, $rabbitMqVHost);
    }

    function index() {
        $dataANumber = DB::table('a_number_country')
            ->where('client_id', '=', Auth::user()->client_id)
            ->select('a_country_id', 'country_name')
            ->orderBy('country_name')
            ->get();

        return view('sendsms')->with('dataANumber', $dataANumber);
    }

    function getTemplateFile(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::download('public/DestinationNumber.txt');
    }

    function checkIfHasUpLine(string $clientId) {
        try {
            $dataClient = DB::table('client')
                ->select('client_id', 'client_name', 'business_model', 'is_reseller', 'upline_client_id')
                ->where('client_id', '=', $clientId)
                ->where('is_active', '=', true)
                ->get();

            $uplineClientId = '';
            foreach ($dataClient as $data) {
                if (isset($data->upline_client_id)) {
                    $uplineClientId = $data->upline_client_id;
                }
            }

            return $uplineClientId;
        } catch (Exception $e) {
            return '';
        }
    }

    function sendMessage(Request $request): int
    {
        $queueName = "BATCHTOGO_SMS";
        $batchId = str_replace('-', '', Uuid::uuid4()->toString());
        $remoteIpAddress = \Request::ip();
        $clientId = Auth::user()->client_id;
        $uplineOfClientId = $this->checkIfHasUpLine($clientId);

        $apiUserName = Auth::user()->email;
        // replace @ with _
        $apiUserName = str_replace('@', '_', $apiUserName);
        // replace . with _
        $apiUserName = str_replace('.', '_', $apiUserName);

        // Uploaded file
        $bNumberFile = $request->file('bnumberfile');

        // Get the file extension
        $fileExt = $bNumberFile->getClientOriginalExtension();

        if ($fileExt === 'txt') {
            // Set new file name
            $newFileName = 'sms_'.$batchId;

            // $bNumberFile->move($this->directorySMSSingleBNumberFile, $newFileName);

            // Put into storage directory
            $request->file('bnumberfile')->storeAs('uploadedbnumbers', $newFileName);

            $trxStatus = 0;
        } else {
            $trxStatus = -2;
        }

        if ($trxStatus === 0) {
            $this->initiateRabbitMQConnection();
            $countryOrigin = $request->input('anumbercountryid');
            $smsContent = $request->input('message');

            // queueMessage has to be in JSON
            $queueMessage = json_encode(array("batchId" => $batchId, "serviceType" => "SMS_SINGLE"));
            $finalQueueMessage = new AMQPMessage($queueMessage, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

            try {
                // Save to DB
                $insertResult = DB::table('batchtogo_sms')
                    ->insert([
                        "batch_id" => $batchId,
                        "date_time" => date('Y-m-d H:i:s'),
                        "anumbercountryid" => $countryOrigin,
                        "sms_content" => $smsContent,
                        "client_id" => $clientId,
                        "upline_client_id" => $uplineOfClientId,
                        "api_username" => $apiUserName,
                        "remote_ip_address" => $remoteIpAddress,
                        "is_processed" => false
                    ]);

                if ($insertResult) {
                    // Open RabbitMQ Connection
                    $theConnection = $this->initiateRabbitMQConnection();

                    // Open RabbitMQ Channel
                    $theChannel = $theConnection->channel();

                    // Publish
                    $theChannel->basic_publish($finalQueueMessage, '', $queueName);

                    // Close Channel
                    $theChannel->close();

                    // Close Connection
                    $theConnection->close();

                    $trxStatus = 0;
                } else {
                    $trxStatus = -1;
                }
            } catch (Exception $e) {
                //$trxStatus = $e;
                Log::debug($e);
                $trxStatus = -3;
            }
        }

        return $trxStatus;
    }
}
