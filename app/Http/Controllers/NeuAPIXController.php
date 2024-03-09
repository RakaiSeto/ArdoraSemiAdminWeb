<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NeuAPIXController extends Controller
{
    function index() {
        // Load select - group
        $data = DB::table('client_group')
            ->select('group_id', 'group_name')
            ->orderBy('group_name')
            ->get();

        return view('neuapix')->with('clientGroupData', $data);
    }

    function getDataTable(Request $request) {
        error_log('get table');

        try {
            if ($request->ajax()) {
                $dataAPICredential = DB::table('neuapix_account')
                    ->orderBy('neuapix_account.acc_id')
                    ->select('neuapix_account.acc_id',
                        'neuapix_account.token_id', 'neuapix_account.app_id', 'neuapix_account.domain_name')
                    ->where('neuapix_account.client_group_id', '=', Auth::user()->group_id)
                    ->get();

                return DataTables::of($dataAPICredential)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditNeuAPIx" data-accId="'.
                            $row->acc_id.'" data-tokenId="'.$row->token_id.'" data-appId="'.$row->app_id.'" data-domainName="'.$row->domain_name.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelNeuAPIx" data-accId="'.
                            $row->acc_id.'">Delete</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } else {
                return '';
            }
        } catch (Exception $e) {
            //return $e;
            return '';
        }
    }

    function doSaveNewNeuAPIx(Request $request) {
        $accId = $request->accid;
        $tokenId = $request->tokenid;
        $appId = $request->appid;
        $domainName = $request->domainname;
        $clientGroupId = Auth::user()->group_id;

        $trxStatus = 0;
        try {
            DB::table('neuapix_account')
                ->insert(['acc_id' => $accId, 'client_group_id' => $clientGroupId, 'token_id' => $tokenId,
                    'app_id' => $appId, 'domain_name' => $domainName]);


            $trxStatus = 0;
        } catch (Exception $e) {
//            $trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditNeuAPIx(Request $request) {
        $accId = $request->accid;
        $tokenId = $request->tokenid;
        $appId = $request->appid;
        $domainName = $request->domainname;
        $clientGroupId = Auth::user()->group_id;

        $trxStatus = 0;
        try {
            DB::table('neuapix_account')
                ->where('acc_id', $accId)
                ->where('client_group_id', $clientGroupId)
                ->update(['token_id' => $tokenId, 'app_id' => $appId, 'domain_name' => $domainName]);

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteNeuAPIx(Request $request) {
        $accId = $request->accid;
        $clientGroupId = Auth::user()->group_id;

        error_log($accId);
        error_log($clientGroupId);
        $trxStatus = 0;
        try {
            DB::table('neuapix_account')
                ->where('acc_id', $accId)
                ->where('client_group_id', $clientGroupId)
                ->delete();

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
