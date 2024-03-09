<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    function index() {
        // Load select - group
        $data = DB::table('client_group')
            ->select('group_id', 'group_name')
            ->orderBy('group_name')
            ->get();

        // Load currency
        $dataCurrency = DB::table('currency')
            ->select('currency_id', 'currency_name')
            ->orderBy('currency_name')
            ->get();

        // Load country
        $dataCountry = DB::table('country')
            ->select('country_id', 'country_name')
            ->orderBy('country_name')
            ->get();

        return view('vendor')->with('clientGroupData', $data)->with('vendorCurrencyData', $dataCurrency)->with('countryData', $dataCountry);
    }

    function getDataTable(Request $request) {
        $userGroupId = Auth::user()->group_id;

        if ($request->ajax()) {
            if (Auth::user()->privilege === 'ROOT') {
                $data = DB::table('vendor_sms')
                    ->leftJoin('country', 'vendor_sms.vendor_country', '=', 'country.country_id')
                    ->leftJoin('client_group', 'vendor_sms.client_group_id', '=', 'client_group.group_id')
                    ->select('vendor_sms.vendor_id', 'vendor_sms.vendor_name', DB::raw("coalesce(vendor_sms.vendor_city, '') as vendor_city"),
                        DB::raw("coalesce(vendor_sms.vendor_country, '') as vendor_country"), DB::raw("coalesce(vendor_sms.pic_name, '') as pic_name"),
                        DB::raw("coalesce(vendor_sms.pic_phone_number, '') as pic_phone_number"), DB::raw("coalesce(vendor_sms.pic_email, '') as pic_email"),
                        DB::raw("coalesce(vendor_sms.queue_name, '') as queue_Name"), DB::raw("coalesce(vendor_sms.client_group_id, '') as client_group_id"),
                        DB::raw("coalesce(vendor_sms.vendor_tps, 0) as vendor_tps"),
                        DB::raw("coalesce(country.country_name, '') as country_name"), DB::raw("coalesce(client_group.group_name, '') as group_name"))
                    ->orderBy('vendor_sms.vendor_name')
                    ->get();
            } else {
                $data = DB::table('vendor_sms')
                    ->leftJoin('country', 'vendor_sms.vendor_country', '=', 'country.country_id')
                    ->where('vendor_sms.client_group_id', '=', $userGroupId)
                    ->select('vendor_sms.vendor_id', 'vendor_sms.vendor_name', DB::raw("coalesce(vendor_sms.vendor_city, '') as vendor_city"),
                        DB::raw("coalesce(vendor_sms.vendor_country, '') as vendor_country"), DB::raw("coalesce(vendor_sms.pic_name, '') as pic_name"),
                        DB::raw("coalesce(vendor_sms.pic_phone_number, '') as pic_phone_number"), DB::raw("coalesce(vendor_sms.pic_email, '') as pic_email"),
                        DB::raw("coalesce(vendor_sms.queue_name, '') as queue_Name"), DB::raw("coalesce(vendor_sms.client_group_id, '') as client_group_id"),
                        DB::raw("coalesce(vendor_sms.vendor_tps, 0) as vendor_tps"),
                        DB::raw("coalesce(country.country_name, '') as country_name"))
                    ->orderBy('vendor_sms.vendor_name')
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if (Auth::user()->privilege === 'ROOT') {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditVendor" data-editVendorId="'.
                            $row->vendor_id.'" data-editVendorName="'.$row->vendor_name.'" data-editVendorCity="'.$row->vendor_city.'" data-editVendorCountry="'.$row->vendor_country.
                            '" data-editVendorPIC="'.$row->pic_name.'" data-editVendorPICPhoneNumber="'.$row->pic_phone_number.'" data-editVendorPICEmail="'.$row->pic_email.
                            '" data-editVendorQueueName="'.$row->queue_name.'" data-editVendorTPS="'.$row->vendor_tps.
                            '" data-editVendorClientGroupId="'.$row->client_group_id.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelVendor" data-delVendorId="'.
                            $row->vendor_id.'" data-delVendorName="'.$row->vendor_name.'">Delete</a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditVendor" data-editVendorId="'.
                            $row->vendor_id.'" data-editVendorName="'.$row->vendor_name.'" data-editVendorCity="'.$row->vendor_city.'" data-editVendorCountry="'.$row->vendor_country.
                            '" data-editVendorPIC="'.$row->pic_name.'" data-editVendorPICPhoneNumber="'.$row->pic_phone_number.'" data-editVendorPICEmail="'.$row->pic_email.
                            '" data-editVendorQueueName="'.$row->queue_name.'" data-editVendorTPS="'.$row->vendor_tps.
                            '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelVendor" data-delVendorId="'.
                            $row->vendor_id.'" data-delVendorName="'.$row->vendor_name.'">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function doSaveNewVendor(Request $request) {
        $vendorName = $request->vendorname;
        $vendorCity = $request->vendorcity;
        $vendorCountry = $request->vendorcountry;
        $vendorPIC = $request->vendorpic;
        $vendorPICPhoneNumber = $request->vendorpicphonenumber;
        $vendorPICEmail = $request->vendorpicemail;
        $vendorQueueName = $request->vendorqueuename;
        $vendorTPS = $request->vendortps;
        $vendorClientGroup = $request->vendorclientgroup;

        if (strlen(str_replace(' ', '', $vendorName)) > 2) {
            $maxLen = 2;
        } else {
            $maxLen = strlen(str_replace(' ', '', $vendorName));
        }
        $vendorId = strtoupper(substr(str_replace(' ', '', $vendorName), 0, $maxLen)).strtoupper(substr(str_replace(' ', '', $vendorName), strlen(str_replace(' ', '', $vendorName)) - 2, strlen(str_replace(' ', '', $vendorName)))).date('Ymd');

        $privilege = Auth::user()->privilege;

        if ($privilege != 'ROOT') {
            $vendorClientGroup = Auth::user()->group_id;
        }

        try {
            DB::table('vendor_sms')
                ->insert(['vendor_id' => $vendorId, 'vendor_name' => $vendorName, 'vendor_city' => $vendorCity,
                    'vendor_country' => $vendorCountry, 'pic_name' => $vendorPIC, 'pic_phone_number' => $vendorPICPhoneNumber,
                    'pic_email' => $vendorPICEmail, 'is_active' => true, 'queue_name' => $vendorQueueName,
                    'vendor_tps' => $vendorTPS, 'client_group_id' => $vendorClientGroup]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditVendor(Request $request) {
        $vendorId = $request->vendorid;
        $vendorName = $request->vendorname;
        $vendorCity = $request->vendorcity;
        $vendorCountry = $request->vendorcountry;
        $vendorPIC = $request->vendorpic;
        $vendorPICPhoneNumber = $request->vendorpicphonenumber;
        $vendorPICEmail = $request->vendorpicemail;
        $vendorQueueName = $request->vendorqueuename;
        $vendorTPS = $request->vendortps;
        if (isset($request->vendorclientgroupid)) {
            $vendorClientGroup = $request->vendorclientgroupid;
        }

        if (strlen(str_replace(' ', '', $vendorName)) > 2) {
            $maxLen = 2;
        } else {
            $maxLen = strlen(str_replace(' ', '', $vendorName));
        }
        $newVendorId = strtoupper(substr(str_replace(' ', '', $vendorName), 0, $maxLen)).strtoupper(substr(str_replace(' ', '', $vendorName), strlen(str_replace(' ', '', $vendorName)) - 2, strlen(str_replace(' ', '', $vendorName)))).date('Ymd');

        $privilege = Auth::user()->privilege;

        if ($privilege != 'ROOT') {
            $vendorClientGroup = Auth::user()->group_id;
        }

        try {
            if (Auth::user()->privilege === 'ROOT' && isset($request->vendorclientgroupid)) {
            DB::table('vendor_sms')
                ->where('vendor_id', '=', $vendorId)
                ->update(['vendor_id' => $newVendorId, 'vendor_name' => $vendorName, 'vendor_city' => $vendorCity, 'vendor_country' => $vendorCountry,
                    'pic_name' => $vendorPIC, 'pic_phone_number' => $vendorPICPhoneNumber, 'pic_email' => $vendorPICEmail,
                    'queue_name' => $vendorQueueName, 'vendor_tps' => $vendorTPS, 'client_group_id' => $vendorClientGroup ]);
            } else {
                DB::table('vendor_sms')
                    ->where('vendor_id', '=', $vendorId)
                    ->update(['vendor_id' => $newVendorId, 'vendor_name' => $vendorName, 'vendor_city' => $vendorCity, 'vendor_country' => $vendorCountry,
                        'pic_name' => $vendorPIC, 'pic_phone_number' => $vendorPICPhoneNumber, 'pic_email' => $vendorPICEmail,
                        'queue_name' => $vendorQueueName, 'vendor_tps' => $vendorTPS]);
            }

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteVendor(Request $request) {
        $vendorId = $request->vendorid;

        try {
            DB::table('vendor_sms')
                ->where('vendor_id', '=', $vendorId)
                ->delete();

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
