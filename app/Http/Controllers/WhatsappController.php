<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WhatsappController extends Controller
{
    function index() {
        // Load select - group

        $data = DB::table('wa_device')
            ->select('device_location', DB::raw("coalesce(device_number, '') as device_number"), 'device_local', 'device_public', 'device_status', 'device_idx')
            ->orderBy('device_location')
            ->where('is_active', '=', true)
            ->get();
        $result=json_decode($data);

        $rawslot = DB::table('wa_device')
            ->select(DB::raw("COUNT(*) as total"))
            ->where('is_active', '=', true)
            ->get();
        $slot=json_decode($rawslot);

        $rawactive = DB::table('wa_device')
            ->select(DB::raw("COUNT(*) as total"))
            ->where('is_active', '=', true)
            ->where('device_status', '=', 'OK')
            ->get();
        $active=json_decode($rawactive);

        $rawinactive = DB::table('wa_device')
            ->select(DB::raw("COUNT(*) as total"))
            ->where('is_active', '=', true)
            ->where('device_status', '=', 'NOK')
            ->get();
        $inactive=json_decode($rawinactive);

        $rawdown = DB::table('wa_device')
            ->select(DB::raw("COUNT(*) as total"))
            ->where('is_active', '=', true)
            ->where('device_status', '=', 'NOKLOGIN')
            ->get();
        $down=json_decode($rawdown);

//        dd($data);
        return view('whatsapp')
            ->with('keywordData', $result)
            ->with('totalSlot', $slot)
            ->with('totalActive', $active)
            ->with('totalInActive', $inactive)
            ->with('totalDown', $down);
    }

    function getDataTable(Request $request) {
        $userGroupId = Auth::user()->group_id;

        if ($request->ajax()) {
            $data = DB::table('wa_device')
                ->select('device_location', DB::raw("coalesce(device_number, '') as device_number"), 'device_local', 'device_public', 'device_status')
                ->orderBy('device_location')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);


        }
    }
}
