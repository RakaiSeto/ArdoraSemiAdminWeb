<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VendorSenderIdController extends Controller
{
    function index() {
        if (Auth::user()->privilege === 'ROOT') {
            $dataVendor = DB::table('vendor_sms')
                ->select('vendor_id', 'vendor_name')
                ->orderBy('vendor_name')
                ->get();

            return view('vendorsenderid')->with('vendorData', $dataVendor);
        } else if ((Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP')) {
            $dataVendor = DB::table('vendor_sms')
                ->where('client_group_id', '=', Auth::user()->group_id)
                ->select('vendor_id', 'vendor_name')
                ->orderBy('vendor_name')
                ->get();

            return view('vendorsenderid')->with('vendorData', $dataVendor);
        }
    }

    function getDataTable(Request $request) {
        $privilege = Auth::user()->privilege;
        $groupId = Auth::user()->group_id;

        if ($request->ajax()) {
            if ($privilege === 'ROOT') {
                $dataSenderId = DB::table('vendor_senderid_sms')
                    ->leftJoin('vendor_sms', 'vendor_senderid_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                    ->leftJoin('client_group', 'client_group.group_id', '=', 'vendor_sms.client_group_id')
                    ->select('vendor_senderid_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id', 'vendor_senderid_sms.vendor_id',
                    'vendor_sms.vendor_name', 'vendor_sms.client_group_id', 'client_group.group_name', 'vendor_senderid_sms.is_active', 'vendor_senderid_sms.masking')
                    ->orderBy('vendor_senderid_sms.sender_id')
                    ->get();
            } else {
                $dataSenderId = DB::table('vendor_senderid_sms')
                    ->leftJoin('vendor_sms', 'vendor_senderid_sms.vendor_id', '=', 'vendor_sms.vendor_id')
                    ->select('vendor_senderid_sms.vendor_sender_id_id', 'vendor_senderid_sms.sender_id', 'vendor_senderid_sms.vendor_id',
                        'vendor_sms.vendor_name', 'vendor_sms.client_group_id', 'vendor_senderid_sms.is_active', 'vendor_senderid_sms.masking')
                    ->where('vendor_sms.client_group_id', '=', $groupId)
                    ->orderBy('vendor_senderid_sms.sender_id')
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
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditVendorSenderId" data-editVendorSenderIdId="'.
                        $row->vendor_sender_id_id.'" data-editVendorSenderId="'.$row->sender_id.'" data-editVendorId="'.$row->vendor_id.'" data-editVendorMasking="'.$row->masking.
                        '" data-editIsActive="'.$row->is_active.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelVendorSenderId" data-delVendorSenderIdId="'.
                        $row->vendor_sender_id_id.'" data-delVendorSenderId="'.$row->sender_id.'">Delete</a>';
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
    }

    function doSaveNewVendorSenderId(Request $request) {
        $vendorSenderId = $request->vendorsenderid;
        $vendorSenderMasking = $request->vendormasking;
        $vendorId = $request->vendorid;

        $vendorSenderIdId = trim($vendorSenderId).'-'.trim($vendorId);

        try {
            DB::table('vendor_senderid_sms')
                ->insert(['vendor_sender_id_id' => $vendorSenderIdId, 'sender_id' => $vendorSenderId, 'vendor_id' => $vendorId, 'masking' => $vendorSenderMasking, 'is_active' => true]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditVendorSenderId(Request $request) {
        $vendorSenderIdId = $request->vendorsenderidid;
        $vendorSenderId = $request->vendorsenderid;
        $vendorSenderMasking = $request->vendormasking;
        $vendorId = $request->vendorid;

        $newVendorSenderIdId = trim($vendorSenderId).'-'.trim($vendorId);

        try {
            DB::table('vendor_senderid_sms')
                ->where('vendor_sender_id_id', '=', $vendorSenderIdId)
                ->update(['vendor_sender_id_id' => $newVendorSenderIdId, 'sender_id' => $vendorSenderId, 'vendor_id' => $vendorId, 'masking' => $vendorSenderMasking]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteVendorSenderId(Request $request) {
        $vendorSenderIdId = $request->vendorsenderidid;

        try {
            DB::table('vendor_senderid_sms')
                ->where('vendor_sender_id_id', '=', $vendorSenderIdId)
                ->delete();

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
