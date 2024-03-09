<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ResellerBalanceController extends Controller
{
    function index() {
        $theClient = Auth::user()->client_id;



        return view('resellerbalance')->with('dataSubClientBalance', $dataSubClientBalance);
    }

    function getDataTable(Request $request) {
        $theClient = Auth::user()->client_id;
        $searchedClientId = $request->searchclient;

        if (strlen($searchedClientId) > 0) {
            // Search Client
            $dataSubClientBalance = DB::table('client')
                ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                ->where('client.client_id', '=', $searchedClientId)
                ->select('client.client_id', 'client.client_name', 'client_balance.now_balance', 'client_balance.last_usage_value',
                    'client_balance.last_usage_date_time', 'client_balance.last_usage_type', 'client_balance.last_usage_message_id', 'client.is_active')
                ->get();
        } else {
            $dataSubClientBalance = DB::table('client')
                ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                ->where('client.client_id', '=', $theClient)
                ->orWhere('client.upline_client_id', '=', $theClient)
                ->select('client.client_id', 'client.client_name', 'client_balance.now_balance', 'client_balance.last_usage_value',
                    'client_balance.last_usage_date_time', 'client_balance.last_usage_type', 'client_balance.last_usage_message_id', 'client.is_active')
                ->get();
        }

        return DataTables::of($dataSubClientBalance)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::user()->privilege === 'B2B_RESELLER' && $row->is_active === true && $row->client_id != Auth::user()->client_id) {
                    return '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTopUp" data-clientId="'.
                        $row->client_id.'" data-isActive="'.$row->is_active.'" data-clientName="'.$row->client_name.'">Disable</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($row) {
                if ($row->is_active === true) {
                    return 'YES';
                } else {
                    return 'NO';
                }
            })
            ->make(true);
    }
}
