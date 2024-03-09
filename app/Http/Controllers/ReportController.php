<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    function index() {
        return view('report');
    }

    function getDataTable(Request $request) {
        $rangeDateTime = $request->input('daterange');
        $searchCategory = $request->input('searchcategory');
        $searchKeyword = $request->input('searchkeyword');
        $clientId = Auth::user()->client_id;

        // Split by -
        $splittedDateTime = explode('-', $rangeDateTime);
        $strStartDate = trim($splittedDateTime[0]);
        $startDate = DateTime::createFromFormat('d/m/Y', $strStartDate)->format('Y-m-d 00:00:00');

        $strEndDate = trim($splittedDateTime[1]);
        $endDate = DateTime::createFromFormat('d/m/Y', $strEndDate)->format('Y-m-d 23:59:59');

        // Map search category to field
        $searchField = 'trx.transaction_id';
        if ($searchCategory === 'messageid') {
            $searchField = 'trx.message_id';
        } else if ($searchCategory === 'anumber') {
            $searchField = 'trx.client_sender_id';
        } else if ($searchCategory === 'bnumber') {
            $searchField = 'trx.msisdn';
        } else if ($searchCategory === 'status') {
            $searchField = 'stat.description';
        } else {
            $searchField = 'trx.message_id';
        }

        if (strlen($searchKeyword) > 0) {
            $data = DB::table('transaction_sms as trx')
                ->leftJoin('transaction_status as stat', 'trx.status_code', '=', 'stat.status_code')
                ->leftJoin('transaction_sms_financial as trf', 'trx.message_id', '=', 'trf.message_id')
                ->where('trx.transaction_date', '>=', $startDate)
                ->where('trx.transaction_date', '<=', $endDate)
                ->where('trx.client_id', '=', $clientId)
                ->where($searchField, '=', $searchKeyword)
                ->select('trx.transaction_date', 'trx.message_id', 'trx.batch_id', 'trx.client_sender_id',
                    'trx.msisdn', 'trx.message', 'trx.status_code', 'stat.description as status_description', 'trf.previous_balance',
                    'trf.usage', 'trf.after_balance'
                )
                ->orderBy('trx.transaction_date', 'desc');
        } else {
            $data = DB::table('transaction_sms as trx')
                ->leftJoin('transaction_status as stat', 'trx.status_code', '=', 'stat.status_code')
                ->leftJoin('transaction_sms_financial as trf', 'trx.message_id', '=', 'trf.message_id')
                ->where('trx.transaction_date', '>=', $startDate)
                ->where('trx.transaction_date', '<=', $endDate)
                ->where('trx.client_id', '=', $clientId)
                ->select('trx.transaction_date', 'trx.message_id', 'trx.batch_id', 'trx.client_sender_id',
                    'trx.msisdn', 'trx.message', 'trx.status_code', 'stat.description as status_description', 'trf.previous_balance',
                    'trf.usage', 'trf.after_balance'
                )
                ->orderBy('trx.transaction_date', 'desc');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    function exportCSV(Request $request): string
    {
        $reqUserName = Auth::user()->email;
        $reqClientId = $request->input('client');
        $reqSearchKeyword = $request->input('searchkeyword');
        $reqSearchCategory = $request->input('searchcategory');
        $reqTrxType = $request->input('type');
        $reqDateRange = $request->input('daterange');

        // Split by -
        $splittedDateTime = explode('-', $reqDateRange);
        $strStartDate = trim($splittedDateTime[0]);
        $startDate = DateTime::createFromFormat('d/m/Y', $strStartDate)->format('Y-m-d 00:00:00');

        $strEndDate = trim($splittedDateTime[1]);
        $endDate = DateTime::createFromFormat('d/m/Y', $strEndDate)->format('Y-m-d 23:59:59');

        // Compose requestId
        $requestId = $reqUserName.'-'.Date('YmdHis');

        // Insert into table report_request_transaction_pg
        $insertResult = DB::table('report_request_transaction_pg')->insert([
            'request_id' => $requestId,
            'request_datetime' => Date('Y-m-d H:i:s'),
            'username' => $reqUserName,
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'client_id' => $reqClientId,
            'search_keyword' => $reqSearchKeyword,
            'search_parameter' => $reqSearchCategory,
            'is_generated' => false,
            'trx_type' => $reqTrxType
        ]);

        if ($insertResult) {
            $isSuccess = '0';
        } else {
            $isSuccess = '-1';
        }

        return $isSuccess;
    }

    function exportedReportIndex() {
        $dataClient = DB::table('client')
            ->select('client_id', 'client_name')
            ->orderBy('client_name')
            ->get();

        return view('exportedreport')->with('dataClient', $dataClient);
    }

    function getExportedReportDataTable(Request $request) {
        $rangeDateTime = $request->input('daterange');
        $clientId = $request->input('client');

        // Split by -
        $splittedDateTime = explode('-', $rangeDateTime);
        $strStartDate = trim($splittedDateTime[0]);
        $startDate = DateTime::createFromFormat('d/m/Y', $strStartDate)->format('Y-m-d 00:00:00');

        $strEndDate = trim($splittedDateTime[1]);
        $endDate = DateTime::createFromFormat('d/m/Y', $strEndDate)->format('Y-m-d 23:59:59');

        $dataExported = DB::table('report_request_transaction_pg as trx')
            ->leftJoin('client as ccc', 'trx.client_id', '=', 'ccc.client_id')
            ->where('trx.request_datetime', '>=', $startDate)
            ->where('trx.request_datetime', '<=', $endDate)
            ->where('trx.client_id', '=', $clientId)
            ->select('trx.request_datetime', 'trx.request_id', 'trx.client_id', 'ccc.client_name', 'trx.username',
            'trx.start_datetime', 'trx.end_datetime', 'trx.search_keyword', 'trx.search_parameter', 'trx.trx_type', 'trx.is_generated')
            ->orderBy('trx.request_datetime', 'desc');

        return DataTables::of($dataExported)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if ($row->is_generated === true) {
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalTransactionDetail" data-requestid="'.
                        $row->request_id.'">Detail</a>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['is_generated', 'action'])
            ->make(true);
    }
}
