<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class ReportVendorController extends Controller
{
    function index() {
        $clientGroupId = Auth::user()->group_id;

        // Get data client
//        if (Auth::user()->privilege === 'ROOT') {
//            $clientData = DB::table('report_vendor_sms')
//                ->select('report_datetime', 'report_filename')
//                ->orderBy('report_datetime', 'DESC')
//                ->limit(1)
//                ->get();
//        } else if (Auth::user()->privilege === 'SYSADMIN' || Auth::user()->privilege === 'SYSFINANCE' || Auth::user()->privilege === 'SYSOP' || Auth::user()->privilege === 'REPORT') {
//            $clientData = DB::table('report_vendor_sms')
//                ->select('report_datetime', 'report_filename')
//                ->orderBy('report_datetime', 'DESC')
//                ->limit(1)
//                ->get();
//        } else {
//            $clientData = [];
//        }

//        return view('vendorreport')->with('clientData', $clientData);
        return view('vendorreport');
    }

    function getCSVDataTableVendor(Request $request) {
        $clientGroupId = Auth::user()->group_id;
        $privilege = Auth::user()->privilege;

        $searchCategory = $request->input('searchCategory');
        $searchKeyword = $request->input('searchKeyword');

        Log::debug('clientGroupId: '.$clientGroupId.', privilege: '.$privilege);

        $searchField = 'ccc.client_name';
        $operator = 'ilike';

        if($request->ajax())  {
            $until = date('Y-m-d', strtotime('-30 day', time()));

//            $data = DB::select("select report_datetime, report_filename from report_vendor_sms
//                                where report_datetime >= '" . $until . " 23:59:59' order by report_datetime DESC");


            $data = DB::table('report_vendor_sms_new')
                ->select('report_datetime', 'report_allstatus', 'report_nonsuccess')
                ->where('report_datetime', '>=', $until)
                ->orderBy('report_datetime', 'DESC');
            Log::debug($data->toSql());
            Log::debug($until);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function($row1){
                    if ($row1->report_allstatus != "NONE") {
                        $url1 = "https://dl-report.artamaya.com:9903/report/" . $row1->report_allstatus;
                        $btn1 = '<a href="' . $url1 . '" class="edit btn btn-success btn-sm" data-toggle="_blank">Download</a>';
                        return $btn1;
                    }
                })
                ->addColumn('action2', function($row2){
                    $url2 = "https://dl-report.artamaya.com:9903/report/".$row2->report_nonsuccess;
                    $btn2 = '<a href="'.$url2.'" class="edit btn btn-success btn-sm" data-toggle="_blank">Download</a>';
                    return $btn2;
                })
                ->rawColumns(['action1', 'action2'])
                ->make(true);
        } else {
            return '';
        }
    }
}
