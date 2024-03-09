<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientSenderIdController extends Controller
{
    function index() {
        if (Auth::user()->privilege === 'ROOT') {
            $dataClient = DB::table('client')
                ->select('client_id', 'client_name')
                ->orderBy('client_name')
                ->get();

            return view('clientsenderid')->with('clientData', $dataClient);
        } else if ((Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP')) {
            $dataClient = DB::table('client')
                ->where('client_group_id', '=', Auth::user()->group_id)
                ->select('client_id', 'client_name')
                ->orderBy('client_name')
                ->get();

            return view('clientsenderid')->with('clientData', $dataClient);
        }
    }

    function getDataTable(Request $request) {
        $privilege = Auth::user()->privilege;
        $groupId = Auth::user()->group_id;

        if ($request->ajax()) {
            if ($privilege === 'ROOT') {
                $dataSenderId = DB::table('client_senderid_sms')
                    ->leftJoin('client', 'client_senderid_sms.client_id', '=', 'client.client_id')
                    ->leftJoin('client_group', 'client_group.group_id', '=', 'client.client_group_id')
                    ->select('client_senderid_sms.client_sender_id_id', 'client_senderid_sms.sender_id', 'client_senderid_sms.masking', 'client_senderid_sms.client_id',
                        DB::raw("coalesce(client.client_name, '') as client_name"), DB::raw("coalesce(client_group.group_name, '') as group_name"), 'client_senderid_sms.is_active')
                    ->orderBy('client_senderid_sms.sender_id')
                    ->get();
            } else {
                $dataSenderId = DB::table('client_senderid_sms')
                    ->leftJoin('client', 'client_senderid_sms.client_id', '=', 'client.client_id')
                    ->leftJoin('client_group', 'client_group.group_id', '=', 'client.client_group_id')
                    ->select('client_senderid_sms.client_sender_id_id', 'client_senderid_sms.sender_id', 'client_senderid_sms.masking',
                        'client_senderid_sms.client_id', DB::raw("coalesce(client.client_name, '') as client_name"), 'client_senderid_sms.is_active')
                    ->where('client_group.group_id', '=', $groupId)
                    ->orderBy('client_senderid_sms.sender_id')
                    ->get();
            }

            return DataTables::of($dataSenderId)
                ->editColumn('is_active', function($row) {
                    $booleanBox = '<i class="fa fa-check" aria-hidden="true"></i>';

                    if ($row->is_active === true) {
                        $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                    } else {
                        $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                    }

                    return $booleanBox;
                })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditClientSenderId" data-editClientSenderIdId="'.
                        $row->client_sender_id_id.'" data-editClientSenderId="'.$row->sender_id.'" data-editMasking="'.$row->masking.'" data-editClientId="'.$row->client_id.
                        '" data-editIsActive="'.$row->is_active.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelClientSenderId" data-delClientSenderIdId="'.
                        $row->client_sender_id_id.'" data-delClientSenderId="'.$row->sender_id.'">Delete</a>';
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
    }

    function doSaveNewClientSenderId(Request $request) {
        $clientSenderId = $request->clientsenderid;
        $clientMasking = $request->clientmasking;
        $clientId = $request->clientid;

        $clientSenderIdId = trim($clientSenderId).'-'.trim($clientId);

        try {
            DB::table('client_senderid_sms')
                ->insert(['client_sender_id_id' => $clientSenderIdId, 'sender_id' => $clientSenderId, 'client_id' => $clientId, 'masking' => $clientMasking, 'is_active' => true]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditClientSenderId(Request $request) {
        $clientSenderIdIdOrig = $request->clientsenderidid;
        $clientSenderId = $request->clientsenderid;
        $clientMasking = $request->clientmasking;
        $clientId = $request->clientid;

        $newClientSenderIdId = trim($clientSenderId).'-'.trim($clientId);

        try {
            DB::table('client_senderid_sms')
                ->where('client_sender_id_id', '=', $clientSenderIdIdOrig)
                ->update(['client_sender_id_id' => $newClientSenderIdId, 'sender_id' => $clientSenderId, 'client_id' => $clientId, 'masking' => $clientMasking]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteClientSenderId(Request $request) {
        $clientSenderIdId = $request->clientsenderidid;

        try {
            DB::table('client_senderid_sms')
                ->where('client_sender_id_id', '=', $clientSenderIdId)
                ->delete();

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
