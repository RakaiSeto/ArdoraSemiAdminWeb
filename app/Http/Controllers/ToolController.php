<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    function getClientSenderIdByClientId(Request $request) {
        $clientId = $request->clientId;

        try {
            return DB::table('client_senderid_sms')
                ->where('client_id', '=', $clientId)
                ->select('client_sender_id_id', 'sender_id')
                ->orderBy('sender_id')
                ->get();
        } catch (\Exception $e) {
            return '';
        }
    }

    function getAPIUserNameByClientId(Request $request) {
        $clientId = $request->input('clientId');

        try {
            return DB::table('user_api')
                ->where('client_id', '=', $clientId)
                ->where('is_active', '=', true)
                ->select('username')
                ->orderBy('username')
                ->get();
        } catch (\Exception $e) {
            Log::debug('getAPIUserNameByClient - error: '.$e);
            return '';
        }
    }

    function getClientPropertyByClientId(Request $request) {
        $clientId = $request->clientId;

        try {
            return DB::table('client')
                ->where('client_id', '=', $clientId)
                ->select('client_name', 'client_country', 'currency_id', 'business_model')
                ->get();
        } catch (\Exception $e) {
            return '';
        }
    }

    function getVendorSenderIdByVendorId(Request $request) {
        $vendorId = $request->vendorId;

        try {
            return DB::table('vendor_senderid_sms')
                ->where('vendor_id', '=', $vendorId)
                ->where('is_active', '=', true)
                ->select('vendor_sender_id_id', 'sender_id')
                ->orderBy('sender_id')
                ->get();
        } catch (\Exception $e) {
            return '';
        }
    }
}
