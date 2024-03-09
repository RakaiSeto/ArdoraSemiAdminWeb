<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yajra\DataTables\Facades\DataTables;

class TransactionReportController extends Controller
{
    private function initiateRabbitMQConnection() {
        $rabbitMqHost = env('RABBITMQ_HOST', '172.31.24.217');
        $rabbitMqPort = env('RABBITMQ_PORT', 5672);
        $rabbitMqVHost = env('RABBITMQ_VHOST', 'BLASTME');
        $rabbitMqUserName = env('RABBITMQ_USERNAME', 'parjo');
        $rabbitMqPassword = env('RABBITMQ_PASSWORD', 'ToInfinityAndBeyond');

        return new AMQPStreamConnection($rabbitMqHost, $rabbitMqPort, $rabbitMqUserName, $rabbitMqPassword, $rabbitMqVHost);
    }

    function index(Request $request) {
        $clientGroupId = Auth::user()->group_id;

        // Get data client
        Log::debug($request->session()->get('privilege'));
        if ($request->session()->get('privilege') === 'ROOT') {
            $clientData = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();
        } else if ($request->session()->get('privilege') === 'SYSADMIN' || $request->session()->get('privilege') === 'SYSFINANCE' || $request->session()->get('privilege') === 'SYSOP' || $request->session()->get('privilege') === 'REPORT' || $request->session()->get('privilege') === "B2B_USER" || $request->session()->get('privilege') === "B2B_RESELLER")  {
            $clientData = DB::table('client')
                ->select('client.client_id', 'client.client_name')
                ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                ->leftJoin('users', 'users.client_id', '=', 'client_to_reseller.reseller_id')
                ->where('client.is_active', '=', true)
                ->where('users.client_id', '=', $request->session()->get('reseller_id'))
                ->orderBy('client_name')
                ->get();
        } else {
            $clientData = [];
        }

        return view('transactionreport')->with('clientData', $clientData);
    }

    function getDataTable(Request $request) {
        $clientGroupId = Auth::user()->group_id;
        //$privilege = $request->session()->get('privilege');
        $selectedClientId = $request->input('clientid');
        $searchCategory = $request->input('searchcategory');
        $searchKeyword = $request->input('searchkeyword');

        if($request->ajax())  {
            if(isset($request->daterange)) {
                $dateRange = $request->daterange;

                $splittedDateRange = explode('-', $dateRange);

                if(count($splittedDateRange) === 2) {
                    $startDate = trim($splittedDateRange[0]);

                    // Change from dd/mm/yyyy to yyyy-mm-dd
                    $splitedStartDate = explode('/', $startDate);
                    $startDate = $splitedStartDate[2].'-'.$splitedStartDate[1].'-'.$splitedStartDate[0].' 00:00:00';

                    $endDate = trim($splittedDateRange[1]);

                    // Change from dd/mm/yyyy to yyyy-mm-dd
                    $splitedEndDate = explode('/', $endDate);
                    $endDate = $splitedEndDate[2].'-'.$splitedEndDate[1].'-'.$splitedEndDate[0].' 23:59:59';
                } else {
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                }
            } else {
                $startDate = date('Y-m-d 00:00:00');
                $endDate = date('Y-m-d 23:59:59');
            }

            // search field
            if ($searchCategory === 'messageid') {
                $searchField = 'transaction_sms.message_id';
            } else if ($searchCategory === 'batchid') {
                $searchField = 'transaction_sms.batch_id';
            } else if ($searchCategory === 'clientsenderid') {
                $searchField = 'transaction_sms.client_sender_id';
            } else {
                $searchField = 'transaction_sms.msisdn';
            }

            if ($request->session()->get('privilege') == 'ROOT') {
                $data = DB::table('transaction_sms')
                    ->leftJoin('transaction_status', 'transaction_sms.status_code', '=', 'transaction_status.status_code')
                    ->leftJoin('client', 'transaction_sms.client_id', '=', 'client.client_id')
                    ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                    ->leftJoin('country', 'transaction_sms.country_code', '=', 'country.country_id')
                    ->leftJoin('transaction_sms_vendor', 'transaction_sms_vendor.message_id', '=', 'transaction_sms.message_id')
                    ->leftJoin('telecom', 'transaction_sms.telecom_id', '=', 'telecom.telecom_id')
                    ->leftJoin('transaction_sms_financial', 'transaction_sms.message_id', '=', 'transaction_sms_financial.message_id')
                    ->where('transaction_sms.transaction_date', '>=', $startDate)
                    ->where('transaction_sms.transaction_date', '<=', $endDate)
                    ->select(
                        'transaction_sms.transaction_date', 'transaction_sms.batch_id', 'transaction_sms.message_id',
                        'transaction_sms.client_sender_id', 'transaction_sms.msisdn', 'transaction_sms.message', 'transaction_status.description',
                        'client.client_name', 'country.country_name', 'telecom.telecom_name', 'transaction_sms.message_encodng', 'transaction_sms.message_length',
                        'transaction_sms.sms_count', 'transaction_sms_financial.previous_balance', 'transaction_sms.client_price_total', 'transaction_sms_financial.after_balance',
                        'transaction_sms_vendor.vendor_id', 'transaction_sms_vendor.vendor_hit_date_time',
                        'transaction_sms_vendor.vendor_hit_resp_date_time', 'transaction_sms_vendor.vendor_message_id',
                        'transaction_sms_vendor.vendor_callback_date_time', 'transaction_sms_vendor.vendor_trx_status');
            } else {
                Log::debug($request->session()->get('reseller_id'));
                $data = DB::table('transaction_sms')
                    ->leftJoin('transaction_status', 'transaction_sms.status_code', '=', 'transaction_status.status_code')
                    ->leftJoin('client', 'transaction_sms.client_id', '=', 'client.client_id')
                    ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                    ->leftJoin('country', 'transaction_sms.country_code', '=', 'country.country_id')
                    ->leftJoin('transaction_sms_vendor', 'transaction_sms_vendor.message_id', '=', 'transaction_sms.message_id')
                    ->leftJoin('telecom', 'transaction_sms.telecom_id', '=', 'telecom.telecom_id')
                    ->leftJoin('transaction_sms_financial', 'transaction_sms.message_id', '=', 'transaction_sms_financial.message_id')
                    ->where('transaction_sms.transaction_date', '>=', $startDate)
                    ->where('transaction_sms.transaction_date', '<=', $endDate)
                    ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
                    ->select(
                        'transaction_sms.transaction_date', 'transaction_sms.batch_id', 'transaction_sms.message_id',
                        'transaction_sms.client_sender_id', 'transaction_sms.msisdn', 'transaction_sms.message', 'transaction_status.description',
                        'client.client_name', 'country.country_name', 'telecom.telecom_name', 'transaction_sms.message_encodng', 'transaction_sms.message_length',
                        'transaction_sms.sms_count', 'transaction_sms_financial.previous_balance', 'transaction_sms.client_price_total', 'transaction_sms_financial.after_balance',
                        'transaction_sms_vendor.vendor_id', 'transaction_sms_vendor.vendor_hit_date_time',
                        'transaction_sms_vendor.vendor_hit_resp_date_time', 'transaction_sms_vendor.vendor_message_id',
                        'transaction_sms_vendor.vendor_callback_date_time', 'transaction_sms_vendor.vendor_trx_status');
            }

            if (strlen(trim($searchKeyword)) > 0) {
                $data = $data->where($searchField, '=', $searchKeyword);
            }

            if ($selectedClientId !== 'ALL') {
                $data = $data->where('transaction_sms.client_id', '=', $selectedClientId);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalDetailTransaction" data-messageId="'.
                        $row->message_id.'">View Detail</a>';
                })
                ->editColumn('previous_balance', function ($row) {
                    return number_format($row->previous_balance, 5, '.', ',');
                })
                ->editColumn('client_price_total', function ($row) {
                    return number_format($row->client_price_total, 5, '.', ',');
                })
                ->editColumn('after_balance', function ($row) {
                    return number_format($row->after_balance, 5, '.', ',');
                })
                ->make(true);
        } else {
            return '';
        }

    }

    function doExportTransaction(Request $request): int
    {
        date_default_timezone_set('Asia/Jakarta');

        $transactionDateTime = $request->input('transactiondatetime');
        $selectedClientId = $request->input('selectedclientid');
        $searchCategory = $request->input('searchcategory');
        $searchKeyword = $request->input('searchkeyword');

        // split transactionDateTime to startDateTime and endDateTime
        $splittedDateRange = explode('-', $transactionDateTime);

        if(count($splittedDateRange) === 2) {
            $startDate = trim($splittedDateRange[0]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedStartDate = explode('/', $startDate);
            $startDate = $splitedStartDate[2].'-'.$splitedStartDate[1].'-'.$splitedStartDate[0].' 00:00:00';

            $endDate = trim($splittedDateRange[1]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedEndDate = explode('/', $endDate);
            $endDate = $splitedEndDate[2].'-'.$splitedEndDate[1].'-'.$splitedEndDate[0].' 23:59:59';
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
        }

        $userEmail = Auth::user()->email;
        $clientId = Auth::user()->client_id;
        $clientGroupId = Auth::user()->group_id;
        $privilege = Auth::user()->privilege;

        $requestDateTime = date('Y-m-d H:i:s');

        $requestId = $userEmail.'-'.$selectedClientId.'-'.date('ymdHis');

        // Save to table report request transaction sms
        try {
            $insertResult = DB::table('report_request_transaction_sms')
                ->insert(['request_id' => $requestId, 'request_datetime' => $requestDateTime, 'username' => $userEmail, 'start_datetime' => $startDate,
                    'end_datetime' => $endDate, 'client_id' => $selectedClientId, 'search_keyword' => $searchKeyword, 'search_parameter' => $searchCategory,
                    'is_generated' => false, 'is_generated_2' => false, 'file_path_2' => $requestId.'.csv']);


            if ($insertResult) {
                // Send to queue report request
                // queueMessage has to be in JSON:
                //  "requestId" => $requestId,
                //  "startDateTime" => $startDate
                //  "endDateTime" => $endDate,
                //  "selectedClientId" => $selectedClientId,
                //  "searchCategory" => $searchcategory,
                //  "searchKeyword" => $searchkeyword,
                //  "userEmail" => $userEmail,
                //  "clientId" => $clientId,
                //  "clientGroupId" => $clientGroupId,
                //  "privilege" => $privilege

                $reportRequestQueueName = 'CSV_REPORT_SMS_ADMIN';
                $queueMessage = json_encode(array('requestId' => $requestId, 'startDateTime' => $startDate, 'endDateTime' => $endDate,
                    'selectedClientId' => $selectedClientId, 'searchCategory' => $searchCategory, 'searchKeyword' => $searchKeyword,
                    'userEmail' => $userEmail, 'clientId' => $clientId, 'clientGroupId' => $clientGroupId, 'privilege' => $privilege));
                $finalQueueMessage = new AMQPMessage($queueMessage, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

                // Open RabbitMQ Connection
                $theConnection = $this->initiateRabbitMQConnection();

                // Open RabbitMQ Channel
                $theChannel = $theConnection->channel();

                // Publish
                $theChannel->basic_publish($finalQueueMessage, '', $reportRequestQueueName);

                // Close Channel
                $theChannel->close();

                // Close Connection
                $theConnection->close();

                return 0;
            } else {
                // Failed
                return -2;
            }
        } catch (\Exception $e) {
            Log::debug($e);
            return -1;
        }
    }

    function doExportSummaryTransaction(Request $request): int
    {
        date_default_timezone_set('Asia/Jakarta');

        $transactionDateTime = $request->input('transactiondatetime');
        $selectedClientId = $request->input('selectedclientid');
        $searchCategory = $request->input('searchcategory');
        $searchKeyword = $request->input('searchkeyword');

        // split transactionDateTime to startDateTime and endDateTime
        $splittedDateRange = explode('-', $transactionDateTime);

        if(count($splittedDateRange) === 2) {
            $startDate = trim($splittedDateRange[0]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedStartDate = explode('/', $startDate);
            $startDate = $splitedStartDate[2].'-'.$splitedStartDate[1].'-'.$splitedStartDate[0].' 00:00:00';

            $endDate = trim($splittedDateRange[1]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedEndDate = explode('/', $endDate);
            $endDate = $splitedEndDate[2].'-'.$splitedEndDate[1].'-'.$splitedEndDate[0].' 23:59:59';
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
        }

        $userEmail = Auth::user()->email;
        $clientId = Auth::user()->client_id;
        $clientGroupId = Auth::user()->group_id;
        $privilege = Auth::user()->privilege;

        $requestDateTime = date('Y-m-d H:i:s');

        $requestId = $userEmail.'-'.$selectedClientId.'-'.date('ymdHis');

        // Save to table report request transaction sms
        try {
            Log::debug('KESINI BRO');
            $insertResult = DB::table('report_request_transaction_summary_sms')
                ->insert(['request_id' => $requestId, 'request_datetime' => $requestDateTime, 'username' => $userEmail, 'start_datetime' => $startDate,
                    'end_datetime' => $endDate, 'client_id' => $selectedClientId,  'is_generated' => false, 'is_generated_2' => false, 'file_path_2' => $requestId.'.csv']);

            Log::debug($insertResult);

            if ($insertResult) {
                // Send to queue report request
                // queueMessage has to be in JSON:
                //  "requestId" => $requestId,
                //  "startDateTime" => $startDate
                //  "endDateTime" => $endDate,
                //  "selectedClientId" => $selectedClientId,
                //  "searchCategory" => $searchcategory,
                //  "searchKeyword" => $searchkeyword,
                //  "userEmail" => $userEmail,
                //  "clientId" => $clientId,
                //  "clientGroupId" => $clientGroupId,
                //  "privilege" => $privilege

                $reportRequestQueueName = 'EXCEL_REPORT_SUMMARY_TRANSACTION';
                $queueMessage = json_encode(array('requestId' => $requestId, 'startDateTime' => $startDate, 'endDateTime' => $endDate,
                    'selectedClientId' => $selectedClientId, 'searchCategory' => $searchCategory, 'searchKeyword' => $searchKeyword,
                    'userEmail' => $userEmail, 'clientId' => $clientId, 'clientGroupId' => $clientGroupId, 'privilege' => $privilege));
                $finalQueueMessage = new AMQPMessage($queueMessage, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

                // Open RabbitMQ Connection
                $theConnection = $this->initiateRabbitMQConnection();

                // Open RabbitMQ Channel
                $theChannel = $theConnection->channel();

                // Publish
                $theChannel->basic_publish($finalQueueMessage, '', $reportRequestQueueName);

                // Close Channel
                $theChannel->close();

                // Close Connection
                $theConnection->close();

                return 0;
            } else {
                // Failed
                return -2;
            }
        } catch (\Exception $e) {
            Log::debug($e);
            return -1;
        }
    }

    function csvReport() {
        $clientGroupId = Auth::user()->group_id;
        Log::debug('clientGroupId: '.$clientGroupId);

        // Get data client
        if (Auth::user()->privilege === 'ROOT') {
            $clientData = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();
        } else if (Auth::user()->privilege === 'SYSADMIN' || Auth::user()->privilege === 'SYSFINANCE' || Auth::user()->privilege === 'SYSOP' || Auth::user()->privilege === 'REPORT') {
            $clientData = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('is_active', '=', true)
                ->where('client_group_id', '=', $clientGroupId)
                ->orderBy('client_name')
                ->get();
        } else {
            $clientData = [];
        }

        return view('exportedreport')->with('clientData', $clientData);
    }

    function getCSVDataTable(Request $request) {
        $clientGroupId = Auth::user()->group_id;
        $privilege = Auth::user()->privilege;

        $searchCategory = $request->input('searchCategory');
        $searchKeyword = $request->input('searchKeyword');

        Log::debug('clientGroupId: '.$clientGroupId.', privilege: '.$privilege);

        $searchField = 'ccc.client_name';
        $operator = 'ilike';

        if($request->ajax())  {
            if ($privilege === 'ROOT') {
                if (trim($searchKeyword) != '') {
                    if ($searchCategory === 'clientId') {
                        $searchField = 'rpt.client_id';
                        $operator = '=';
                    } else if ($searchCategory === 'clientName') {
                        $searchField = 'ccc.client_name';
                        $operator = 'ilike';
                        $searchKeyword = '%'.$searchKeyword.'%';
                    }

                    $data = DB::table('report_request_transaction_sms as rpt')
                        ->leftJoin('client as ccc', 'rpt.client_id', '=', 'ccc.client_id')
                        // ->where('ccc.is_active', '=', true)
->where('rpt.username','=',$userEmail)
                        ->where($searchField, $operator, $searchKeyword)
                        ->select('rpt.request_id', 'rpt.request_datetime', 'rpt.username', 'rpt.start_datetime',
                            'rpt.end_datetime', 'rpt.client_id', 'ccc.client_name', 'rpt.search_keyword', 'rpt.search_parameter',
                            'rpt.is_generated_2', 'rpt.file_path_2')
                        ->orderBy('rpt.request_datetime', 'DESC');
                } else {
                    $data = DB::table('report_request_transaction_sms as rpt')
                        ->leftJoin('client as ccc', 'rpt.client_id', '=', 'ccc.client_id')
                        //->where('ccc.is_active', '=', true)
->where('rpt.username','=',$userEmail)
                        ->select('rpt.request_id', 'rpt.request_datetime', 'rpt.username', 'rpt.start_datetime',
                            'rpt.end_datetime', 'rpt.client_id', 'ccc.client_name', 'rpt.search_keyword', 'rpt.search_parameter',
                            'rpt.is_generated_2', 'rpt.file_path_2')
                        ->orderBy('rpt.request_datetime', 'DESC');
                }
            } else {
                if (trim($searchKeyword) != '') {
                    if ($searchCategory === 'clientId') {
                        $searchField = 'rpt.client_id';
                        $operator = '=';
                    } else if ($searchCategory === 'clientName') {
                        $searchField = 'ccc.client_name';
                        $operator = 'ilike';
                        $searchKeyword = '%'.$searchKeyword.'%';
                    }

                    $data = DB::table('report_request_transaction_sms as rpt')
                        ->leftJoin('users as usr', 'rpt.username', '=', 'usr.email')
                        ->leftJoin('client as ccc', 'usr.client_id', '=', 'ccc.client_id')
                        ->where('ccc.is_active', '=', true)
                        ->where($searchField, $operator, $searchKeyword)
                        ->where('ccc.client_group_id', '=', $clientGroupId)
                        ->select('rpt.request_id', 'rpt.request_datetime', 'rpt.username', 'rpt.start_datetime',
                            'rpt.end_datetime', 'rpt.client_id', 'ccc.client_name', 'rpt.search_keyword', 'rpt.search_parameter',
                            'rpt.is_generated_2', 'rpt.file_path_2')
                        ->orderBy('rpt.request_datetime', 'DESC');

                    Log::debug($data->toSql());
                } else {
                    $data = DB::table('report_request_transaction_sms as rpt')
                        ->leftJoin('users as usr', 'rpt.username', '=', 'usr.email')
                        ->leftJoin('client as ccc', 'usr.client_id', '=', 'ccc.client_id')
                        ->where('ccc.is_active', '=', true)
                        ->where('ccc.client_group_id', '=', $clientGroupId)
                        ->select('rpt.request_id', 'rpt.request_datetime', 'rpt.username', 'rpt.start_datetime',
                            'rpt.end_datetime', 'rpt.client_id', 'ccc.client_name', 'rpt.search_keyword', 'rpt.search_parameter',
                            'rpt.is_generated_2', 'rpt.file_path_2')
                        ->orderBy('rpt.request_datetime', 'DESC');

                    Log::debug($data->toSql());
                }
            }

            return DataTables::of($data)
                ->editColumn('is_generated_2', function($row) {
                    if ($row->is_generated_2 === true) {
                        $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                    } else {
                        $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                    }

                    return $booleanBox;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if ($row->is_generated_2 === true) {
                        //$url = "https://dl-report.artamaya.com:9903/report/".$row->file_path_2;
                        //$url =  url("/storage/reports") . "/" . $row->file_path_2;
                        $url =  "https://neoadmin.krapoex.com/storage/reports/" . $row->file_path_2;
                        $btn = '<a href="'.$url.'" class="edit btn btn-success btn-sm" data-toggle="_blank">Download</a>';
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'is_generated_2'])
                ->make(true);
        } else {
            return '';
        }
    }

    function summaryTransaction(Request $request) {
        $clientGroupId = Auth::user()->group_id;

        // Get data client
        if ($request->session()->get('privilege') === 'ROOT') {
            $clientData = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();
        } else if ($request->session()->get('privilege') === 'SYSADMIN' || $request->session()->get('privilege') === 'SYSFINANCE' || $request->session()->get('privilege') === 'SYSOP' || $request->session()->get('privilege') === 'REPORT' || $request->session()->get('privilege') === "B2B_USER" || $request->session()->get('privilege') === "B2B_RESELLER")  {
            $clientData = DB::table('client')
                ->select('client.client_id', 'client.client_name')
                ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                ->leftJoin('users', 'users.client_id', '=', 'client_to_reseller.reseller_id')
                ->where('client.is_active', '=', true)
                ->where('users.client_id', '=', $request->session()->get('reseller_id'))
                ->orderBy('client_name')
                ->get();
        } else {
            $clientData = [];
        }

        return view('transactionsummary')->with('clientData', $clientData);
    }

    function getSummaryFinancialDataTable(Request $request) {
        $clientGroupId = env("APP_CLIENT_GROUP_ID", "");

        if($request->input('daterange') !== null) {
            $dateRange = $request->input('daterange');

            $splittedDateRange = explode('-', $dateRange);

            if(count($splittedDateRange) === 2) {
                $startDate = trim($splittedDateRange[0]);

                // Change from dd/mm/yyyy to yyyy-mm-dd
                $splitedStartDate = explode('/', $startDate);
                $startDate = $splitedStartDate[2].'-'.$splitedStartDate[1].'-'.$splitedStartDate[0].' 00:00:00';

                $endDate = trim($splittedDateRange[1]);

                // Change from dd/mm/yyyy to yyyy-mm-dd
                $splitedEndDate = explode('/', $endDate);
                $endDate = $splitedEndDate[2].'-'.$splitedEndDate[1].'-'.$splitedEndDate[0].' 23:59:59';
            } else {
                $startDate = date('Y-m-d 00:00:00');
                $endDate = date('Y-m-d 23:59:59');
            }
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
        }

        $selectedClientId = $request->input('selectedclientid');

        Log::debug('clientGroupId: '.$clientGroupId.', selectedClientId: '.$selectedClientId);

        if ($selectedClientId === 'ALL') {
            //$dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name,
            //sum(tx.sms_count) as sms_count, sum(tx.client_price_total) as price_total, tx.status_code from transaction_sms as tx
            //left join client as cc on tx.client_id = cc.client_id where tx.transaction_date >= '".$startDate."' and tx.transaction_date <= '".$endDate.
            //"' and cc.client_group_id = '".$clientGroupId."' group by day, tx.client_id, cc.client_name, tx.status_code order by day desc");
            if ($request->session()->get('privilege') == 'ROOT') {
                $dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name, sum(tx.sms_count) as sms_count,
            sum(tx.client_price_total) as price_total, st.description as status_name from transaction_sms as tx
            left join client as cc on tx.client_id = cc.client_id
            left join client_to_reseller as cr on cr.client_id = cc.client_id
            left join transaction_status as st on tx.status_code = st.status_code
            where tx.transaction_date >= '".$startDate."' and tx.transaction_date <= '".$endDate."'
            group by day, tx.client_id, cc.client_name, st.description order by day desc");
            } else {
                $dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name, sum(tx.sms_count) as sms_count,
            sum(tx.client_price_total) as price_total, st.description as status_name from transaction_sms as tx
            left join client as cc on tx.client_id = cc.client_id
            left join client_to_reseller as cr on cr.client_id = cc.client_id
            left join transaction_status as st on tx.status_code = st.status_code
            where tx.transaction_date >= '".$startDate."' and tx.transaction_date <= '".$endDate."' and cr.reseller_id = '".$request->session()->get('reseller_id')."'
            group by day, tx.client_id, cc.client_name, st.description order by day desc");
            }

        } else {
            //$dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name,
            //sum(tx.sms_count) as sms_count, sum(tx.client_price_total) as price_total, tx.status_code from transaction_sms as tx
            //left join client as cc on tx.client_id = cc.client_id where tx.transaction_date >= '".$startDate."' and tx.transaction_date <= '".$endDate.
            //"' and cc.client_group_id = '".$clientGroupId."' and tx.client_id = '".$selectedClientId."' group by day, tx.client_id, cc.client_name, tx.status_code order by day desc");
            if ($request->session()->get('privilege') == 'ROOT') {
                $dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name, sum(tx.sms_count) as sms_count,
                sum(tx.client_price_total) as price_total, st.description as status_name from transaction_sms as tx
                left join client as cc on tx.client_id = cc.client_id
                left join client_to_reseller as cr on cr.client_id = cc.client_id
                left join transaction_status as st on tx.status_code = st.status_code
                where tx.transaction_date >= '" . $startDate . "' and tx.transaction_date <= '" . $endDate . "' and tx.client_id = '" . $selectedClientId . "'
                group by day, tx.client_id, cc.client_name, st.description order by day desc");
            } else {
                $dataSummary = DB::select("select date_trunc('day', tx.transaction_date) as day, tx.client_id, cc.client_name, sum(tx.sms_count) as sms_count,
                sum(tx.client_price_total) as price_total, st.description as status_name from transaction_sms as tx
                left join client as cc on tx.client_id = cc.client_id
                left join client_to_reseller as cr on cr.client_id = cc.client_id
                left join transaction_status as st on tx.status_code = st.status_code
                where tx.transaction_date >= '" . $startDate . "' and tx.transaction_date <= '" . $endDate . "' and cr.reseller_id = '" . $request->session()->get('reseller_id') . "' and tx.client_id = '" . $selectedClientId . "'
                group by day, tx.client_id, cc.client_name, st.description order by day desc");
            }
        }

        return DataTables::of($dataSummary)
            ->make(true);
    }

    function getDetailTransaction(Request $request): string
    {
        $messageid = $request->input('messageid');
        $dateRange = $request->input('daterange');

        $splittedDateRange = explode('-', $dateRange);

        if(count($splittedDateRange) === 2) {
            $startDate = trim($splittedDateRange[0]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedStartDate = explode('/', $startDate);
            $startDate = $splitedStartDate[2].'-'.$splitedStartDate[1].'-'.$splitedStartDate[0].' 00:00:00';

            $endDate = trim($splittedDateRange[1]);

            // Change from dd/mm/yyyy to yyyy-mm-dd
            $splitedEndDate = explode('/', $endDate);
            $endDate = $splitedEndDate[2].'-'.$splitedEndDate[1].'-'.$splitedEndDate[0].' 23:59:59';
        } else {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
        }

        $data = DB::select(DB::raw("select trx.message_id, trx.transaction_date, trx.msisdn, trx.message, trx.country_code, trx.telecom_id, ltel.telecom_name,
trx.status_code, lsts.status_name, trx.client_id, lccc.client_name, trx.currency, trx.message_encodng, trx.message_length, trx.sms_count,
trx.client_sender_id, trx.batch_id, trx.api_username,
ltrf.previous_balance, ltrf.usage, ltrf.after_balance,
ltrr.receiver_date_time, ltrr.receiver_data, ltrr.receiver_client_response, ltrr.client_ip_address, ltrr.receiver_client_response_date_time,
ldlr.dlr_date_time, ldlr.dlr_body, ldlr.dlr_status, ldlr.dlr_push_to, ldlp.dlr_client_push_response,
ltrv.vendor_id, lvvv.vendor_name, ltrv.vendor_hit_date_time, ltrv.vendor_hit_request, ltrv.vendor_hit_resp_date_time, ltrv.vendor_hit_response,
ltrv.vendor_message_id, ltrv.vendor_callback_date_time, ltrv.vendor_callback, ltrv.vendor_trx_status
from transaction_sms as trx
left join lateral (select tel.telecom_name from telecom as tel where tel.telecom_id = trx.telecom_id limit 1) ltel on true
left join lateral (select sts.description as status_name from transaction_status as sts where sts.status_code = trx.status_code limit 1) lsts on true
left join lateral (select ccc.client_name from client as ccc where ccc.client_id = trx.client_id limit 1) lccc on true
left join lateral (select trf.previous_balance, trf.usage, trf.after_balance from transaction_sms_financial as trf where trf.message_id = trx.message_id limit 1) ltrf on true
left join lateral (select trr.receiver_date_time, trr.receiver_data, trr.receiver_client_response, trr.client_ip_address,
				   trr.receiver_client_response_date_time from transaction_sms_receiver as trr where trr.message_id = trx.message_id limit 1) ltrr on true
left join lateral (select dlr.dlr_date_time, dlr.dlr_body, dlr.dlr_status, dlr.dlr_push_to from transaction_sms_dlr as dlr where dlr.message_id = trx.message_id limit 1) ldlr on true
left join lateral (select dlp.dlr_client_push_response from transaction_sms_dlr_client_resp as dlp where dlp.message_id = trx.message_id limit 1) ldlp on true
left join lateral (select trv.vendor_id, trv.vendor_hit_date_time, trv.vendor_hit_request, trv.vendor_hit_resp_date_time, trv.vendor_hit_response,
				   trv.vendor_message_id, trv.vendor_callback_date_time, trv.vendor_callback, trv.vendor_trx_status from transaction_sms_vendor as trv
				  where trv.message_id = trx.message_id limit 1) ltrv on true
left join lateral (select vvv.vendor_name from vendor_sms as vvv where ltrv.vendor_id = vvv.vendor_id limit 1) as lvvv on true
where trx.message_id = :parmessageid"), array('parmessageid' =>$messageid));

        return json_encode($data);
    }
}
