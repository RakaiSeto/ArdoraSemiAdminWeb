<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Helpers\Auditrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yajra\DataTables\Facades\DataTables;

class RoutingController extends Controller
{
    function initiateRabbitMQConnection() {
        $rabbitMqHost = env('RABBITMQ_HOST', 'localhost');
        $rabbitMqPort = env('RABBITMQ_PORT', 5672);
        $rabbitMqVHost = env('RABBITMQ_VHOST', 'BLASTME');
        $rabbitMqUserName = env('RABBITMQ_USERNAME', 'chandra');
        $rabbitMqPassword = env('RABBITMQ_PASSWORD', 'Eliandri3');

        return new AMQPStreamConnection($rabbitMqHost, $rabbitMqPort, $rabbitMqUserName, $rabbitMqPassword, $rabbitMqVHost);
    }

    function index(Request $request) {
        $groupId = Auth::user()->group_id;

        try {
            // Data Client
            Log::debug('privilege: '.$request->session()->get('privilege'));
            if ($request->session()->get('privilege') === 'ROOT') {
                $dataClient = DB::table('client')
                    ->select('client_id', 'client_name')
                    ->where('is_active', '=', true)
                    ->orderBy('client_name')
                    ->get();
            } else if ($request->session()->get('privilege') === 'SYSADMIN' || $request->session()->get('privilege') === 'SYSFINANCE' || $request->session()->get('privilege') === 'SYSOP' || $request->session()->get('privilege') === 'REPORT' || $request->session()->get('privilege') === "B2B_USER" || $request->session()->get('privilege') === "B2B_RESELLER")  {
                $dataClient = DB::table('client')
                    ->select('client.client_id', 'client.client_name')
                    ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                    ->leftJoin('users', 'users.client_id', '=', 'client_to_reseller.reseller_id')
                    ->where('client.is_active', '=', true)
                    ->where('users.client_id', '=', $request->session()->get('reseller_id'))
                    ->orderBy('client_name')
                    ->get();
            } else {
                $dataClient = [];
            }

            // Data Telecom
            $dataTelecom = DB::table('telecom')
                ->where('is_active', '=', true)
                ->select('telecom_id', 'telecom_name')
                ->orderBy('telecom_name')
                ->get();

            // Data Vendor
            if ($request->session()->get('privilege') === 'ROOT') {
                $dataVendor = DB::table('vendor_sms')
                    ->where('is_active', '=', true)
                    ->select('vendor_id', 'vendor_name')
                    ->orderBy('vendor_name')
                    ->get();
            } else if ($request->session()->get('privilege') === 'SYSADMIN' || $request->session()->get('privilege') === 'SYSFINANCE' || $request->session()->get('privilege') === 'SYSOP' || $request->session()->get('privilege') === 'REPORT' || $request->session()->get('privilege') === "B2B_USER" || $request->session()->get('privilege') === "B2B_RESELLER")  {
                $dataVendor = DB::table('vendor_sms')
                    ->where('client_group_id', '=', $groupId)
                    ->where('is_active', '=', true)
                    ->select('vendor_id', 'vendor_name')
                    ->orderBy('vendor_name')
                    ->get();
            }

            Log::debug('dataVendor: '.$dataVendor);
            return view('routingtable')->with('clientData', $dataClient)->with('telecomData', $dataTelecom)->with('vendorData', $dataVendor);
        } catch (Exception $e) {
            return view('routingtable');
        }
    }

    function getDataTable(Request $request) {
        //$privilege = Auth::user()->privilege;
        $groupId = Auth::user()->group_id;

        $clientId = $request->input('clientId');
        $searchKeyword = $request->input('searchKeyword');
        $searchCategory = $request->input('searchCategory');

        Log::debug('clientId: '.$clientId.', searchKeyword: '.$searchKeyword.', searchCategory: '.$searchCategory);

        if ($clientId == "ALL") {
            if (strlen($searchKeyword) > 0) {
                // Searched
                $searchCat = 'routing_table_sms.senderID';
                $operator = '=';

                if ($searchCategory === 'senderID') {
                    $searchCat = 'routing_table_sms.senderID';
                    $operator = '=';
                } else if ($searchCategory === 'vendor') {
                    $searchCat = 'vendor_sms.vendor_name';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else if ($searchCategory === 'client') {
                    $searchCat = 'client.client_name';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else if ($searchCategory === 'userapi') {
                    $searchCat = 'routing_table_sms.client_user_api';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else {
                    $searchCat = 'routing_table_sms.senderID';
                    $operator = '=';
                }

                if ($request->session()->get('privilege') == 'ROOT') {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->where($searchCat, $operator, $searchKeyword)
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                } else {
//                    $dataRouting = DB::table('routing_table_sms')
//                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
//                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
//                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
//                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
//                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
//                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
//                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
//                        ->where($searchCat, $operator, $searchKeyword)
//                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
//                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
//                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
//                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
//                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
//                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
//                        ->orderBy('routing_table_sms.routing_id');
                    $dataRouting = [];
                }
            } else {
                // Not search
                if ($request->session()->get('privilege') == 'ROOT') {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                } else {
//                    $dataRouting = DB::table('routing_table_sms')
//                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
//                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
//                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
//                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
//                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
//                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
//                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
//                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
//                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
//                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
//                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
//                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
//                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
//                        ->orderBy('routing_table_sms.routing_id');
                    $dataRouting = [];
                }
            }
        } else {
            if (strlen($searchKeyword) > 0) {
                // Searched
                $searchCat = 'routing_table_sms.senderID';
                $operator = '=';

                if ($searchCategory === 'senderID') {
                    $searchCat = 'routing_table_sms.senderID';
                    $operator = '=';
                } else if ($searchCategory === 'vendor') {
                    $searchCat = 'vendor_sms.vendor_name';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else if ($searchCategory === 'client') {
                    $searchCat = 'client.client_name';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else if ($searchCategory === 'userapi') {
                    $searchCat = 'routing_table_sms.client_user_api';
                    $operator = 'ilike';
                    $searchKeyword = '%'.$searchKeyword.'%';
                } else {
                    $searchCat = 'routing_table_sms.senderID';
                    $operator = '=';
                }

                if ($request->session()->get('privilege') == 'ROOT') {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->where('routing_table_sms.client_id', '=', $clientId)
                        ->where($searchCat, $operator, $searchKeyword)
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                } else {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->where('routing_table_sms.client_id', '=', $clientId)
                        ->where($searchCat, $operator, $searchKeyword)
                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                }
            } else {
                // Not search
                if ($request->session()->get('privilege') == 'ROOT') {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->where('routing_table_sms.client_id', '=', $clientId)
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                } else {
                    $dataRouting = DB::table('routing_table_sms')
                        ->leftJoin('client', 'routing_table_sms.client_id', '=', 'client.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->leftJoin('vendor_sms', 'routing_table_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                        ->leftJoin('currency', 'routing_table_sms.currency_id', '=', 'currency.currency_id')
                        ->leftJoin('client_senderid_sms', 'routing_table_sms.client_sender_id_id', '=', 'client_senderid_sms.client_sender_id_id')
                        ->leftJoin('vendor_senderid_sms', 'routing_table_sms.vendor_sender_id_id', '=', 'vendor_senderid_sms.vendor_sender_id_id')
                        ->leftJoin('telecom', 'routing_table_sms.telecom_id', '=', 'telecom.telecom_id')
                        ->where('routing_table_sms.client_id', '=', $clientId)
                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
                        ->select('routing_table_sms.routing_id', 'routing_table_sms.client_id', 'client.client_name', 'routing_table_sms.client_user_api', 'routing_table_sms.client_sender_id_id', 'client_senderid_sms.sender_id as client_sender_id',
                            'routing_table_sms.telecom_id', 'telecom.telecom_name', 'routing_table_sms.vendor_id', 'vendor_sms.vendor_name', 'routing_table_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id as vendor_sender_id', DB::raw("coalesce(routing_table_sms.vendor_parameter_json, '') as vendor_parameter_json"),
                            'routing_table_sms.client_price_per_submit', 'routing_table_sms.client_price_per_delivery', 'routing_table_sms.vendor_price_per_submit',
                            'routing_table_sms.vendor_price_per_delivery', 'routing_table_sms.voice_unit_second', 'routing_table_sms.voice_price_per_unit', 'routing_table_sms.currency_id',
                            'currency.currency_name', 'routing_table_sms.is_charged_per_dr', 'routing_table_sms.fake_dr', 'routing_table_sms.is_active')
                        ->orderBy('routing_table_sms.routing_id');
                }
            }
        }

        return DataTables::of($dataRouting)
            ->editColumn('is_charged_per_dr', function ($row) {
                if ($row->is_charged_per_dr === true) {
                    $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                } else {
                    $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                }

                return $booleanBox;
            })
            ->editColumn('fake_dr', function ($row) {
                if ($row->fake_dr === true) {
                    $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                } else {
                    $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                }

                return $booleanBox;
            })
            ->editColumn('is_active', function ($row) {
                if ($row->is_active === true) {
                    $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                } else {
                    $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                }

                return $booleanBox;
            })
            ->addIndexColumn()
            ->addColumn('action',
                function ($row) {
                    if ($row->is_charged_per_dr === true) {
                        $isChargedPerDR = "true";
                    } else {
                        $isChargedPerDR = "false";
                    }

                    if ($row->fake_dr === true) {
                        $isFakeDR = "true";
                    } else {
                        $isFakeDR = "false";
                    }

                    if ($row->is_active === true) {
                        $isActive = "true";
                    } else {
                        $isActive = "false";
                    }
                   return '<a data-id="' . $row->routing_id . '" class="edit btn btn-success btn-sm text-white button-toggle" data-editIsActive="' . $isActive . '">toggle</a>';

                })
            ->rawColumns(['action', 'is_charged_per_dr', 'fake_dr', 'is_active'])
            ->make(true);
    }

    function restart(Request $request) {
        // queueMessage has to be in JSON
        $queueMessage = json_encode(array("type" => "restartblastme"));
        $finalQueueMessage = new AMQPMessage($queueMessage, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        // Open RabbitMQ Connection
        $theConnection = $this->initiateRabbitMQConnection();

        // Open RabbitMQ Channel
        $theChannel = $theConnection->channel();

        // Publish
        $theChannel->basic_publish($finalQueueMessage, '', 'TRCV_SYSTEM_COMMAND');

        // Close Channel
        $theChannel->close();

        // Close Connection
        $theConnection->close();

        return redirect('messagingrouting');
    }

    function doToggleRoutingTable(Request $request)
    {
        if ($request->state === "true") {
            DB::table('routing_table_sms')
                ->where('routing_id', $request->id)
                ->update(['is_active' => false]);
        } else {
            DB::table('routing_table_sms')
                ->where('routing_id', $request->id)
                ->update(['is_active' => true]);
        }

        Auditrail::save_auditrail($request->session()->get('email'), $request->ip(), "ROUTING", "TOGGLE : ".$request->id ." || SUCCESS", $request->getHttpHost());

        return redirect('messagingrouting');
    }

    function doSaveEditRoutingTable(Request $request): int
    {
        $routingId = $request->input('routingId');
        $clientId = $request->input('clientId');
        $clientSenderIdId = $request->input('clientSenderIdId');
        $apiUserName = $request->input('apiUserName');
        $telecomId = $request->input('telecomId');
        $clientPricePerSubmit = $request->input('clientPricePerSubmit');
        $clientPricePerDelivery = $request->input('clientPricePerDelivery');
        $clientCurrencyId = $request->input('clientCurrencyId');
        $vendorId = $request->input('vendorId');
        $vendorSenderIdId = $request->input('vendorSenderIdId');
        $vendorParameter = $request->input('vendorParameter');
        $vendorPricePerSubmit = $request->input('vendorPricePerSubmit');
        $vendorPricePerDelivery = $request->input('vendorPricePerDelivery');
        $voiceUnitSecond = $request->input('voiceUnitSecond');
        $voicePricePerUnit = $request->input('voicePricePerUnit');
        $dlrType = $request->input('dlrType');
        $chargeOnDLR = $request->input('chargeOnDlr');

        Log::debug('routingId: '.$routingId.', clientId: '.$clientId.', clientSenderIdId: '.$clientSenderIdId.
            ', apiUserName:'.$apiUserName.', telecomId: '.$telecomId);
        $fakeDR = true;
        if ($dlrType === 'VENDOR') {
            $fakeDR = false;
        }
        // Save new Routing
        $newRoutingId = $clientSenderIdId.'-'.$apiUserName.'-'.$telecomId;

        try {
            DB::table('routing_table_sms')
                ->where('routing_id', '=', $routingId)
                ->update([
                    'routing_id' => $newRoutingId,
                    'client_id' => $clientId,
                    'client_sender_id_id' => $clientSenderIdId,
                    'telecom_id' => $telecomId,
                    'vendor_id' => $vendorId,
                    'vendor_sender_id_id' => $vendorSenderIdId,
                    'vendor_parameter_json' => $vendorParameter,
                    'client_price_per_submit' => $clientPricePerSubmit,
                    'vendor_price_per_submit' => $vendorPricePerSubmit,
                    'currency_id' => $clientCurrencyId,
                    'is_active' => true,
                    'fake_dr' => $fakeDR,
                    'client_user_api' => $apiUserName,
                    'is_charged_per_dr' => $chargeOnDLR,
                    'client_price_per_delivery' => $clientPricePerDelivery,
                    'vendor_price_per_delivery' => $vendorPricePerDelivery,
                    'voice_unit_second' => $voiceUnitSecond,
                    'voice_price_per_unit' => $voicePricePerUnit
                ]);

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "ROUTING", "UPDATE : ".$routingId." || SUCCESS", $request->getHttpHost());

            // Reload redis
            //$this->reloadRoutingTableRedis();

            return 0;
        } catch (Exception $e) {
            //return $e;
            Log::debug('Exception: '.$e->getMessage());
            return -1;
        }
    }

    function doDeleteRoutingTable(Request $request): int
    {
        $routingId = $request->routingId;

        try {
            DB::table('routing_table_sms')
                ->where('routing_id', $routingId)
                ->delete();

            // Reload redis
            //$this->reloadRoutingTableRedis();

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "ROUTING", "DELETE : ".$routingId." || SUCCESS", $request->getHttpHost());

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
