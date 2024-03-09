<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ResellerClientController extends Controller
{
    function index() {
        $dataCurrency = DB::table('client')
            ->where('client_id', '=', Auth::user()->client_id)
            ->select('currency_id')
            ->get();

        $dataCountry = DB::table('country')
            ->select('country_id', 'country_name')
            ->orderBy('country_name')
            ->get();

        return view('resellerclient')->with('dataCurrency', $dataCurrency)->with('dataCountry', $dataCountry);
    }

    function getDataTable(Request $request) {
        $resellerclientname = $request->input('clientname');
        $uplineClientId = Auth::user()->client_id;

        if (strlen($resellerclientname) > 0) {
            $dataClient = DB::table('client')
                ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                ->where('upline_client_id', '=', $uplineClientId)
                ->where('client_name', '=', $resellerclientname)
                ->select('client_id', 'client_name', 'country.country_name', 'pic_name', 'is_active')
                ->orderBy('client_name');
        } else {
            $dataClient = DB::table('client')
                ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                ->where('upline_client_id', '=', $uplineClientId)
                ->select('client_id', 'client_name', 'country.country_name', 'pic_name', 'is_active')
                ->orderBy('client_name');
        }

        return DataTables::of($dataClient)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::user()->privilege === 'B2B_RESELLER' && $row->is_active === true) {
                    return '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDoAbleClient" data-clientId="'.
                        $row->client_id.'" data-isActive="'.$row->is_active.'" data-clientName="'.$row->client_name.'">Disable</a>';
                } else if (Auth::user()->privilege === 'B2B_RESELLER' && $row->is_active === false) {
                    return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalDoAbleClient" data-clientId="'.
                        $row->client_id.'" data-isActive="'.$row->is_active.'" data-clientName="'.$row->client_name.'">Enable</a>';
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

    function saveNewClient(Request $request): int
    {
        $clientName = $request->input('clientname');
        $countryId = $request->input('country');
        $picName = $request->input('picname');
        $currencyId = $request->input('currencyid');

        if (strlen($clientName) > 0) {
            $uplineClientId = Auth::user()->client_id;
            $clientGroupId = Auth::user()->group_id;

            if (strlen(str_replace(' ', '', $clientName)) > 2) {
                $maxLenDepan = 2;
            } else {
                $maxLenDepan = strlen(str_replace(' ', '', $clientName));
            }

            if (strlen(str_replace(' ', '', $clientName)) > 2) {
                $maxLenBlkg = strlen(str_replace(' ', '', $clientName));
            } else {
                $maxLenBlkg = 2;
            }
            $clientId = strtoupper(substr(str_replace(' ', '', $clientName), 0, $maxLenDepan).substr(str_replace(' ', '', $clientName), $maxLenBlkg - 2, $maxLenBlkg)).date('YmdHis');

            $insertResult = DB::table('client')
                ->insert([
                    'client_id' => $clientId,
                    'client_group_id' => $clientGroupId,
                    'client_name' => $clientName,
                    'client_country' => $countryId,
                    'is_active' => true,
                    'business_model' => 'PREPAID',
                    'currency_id' => $currencyId,
                    'pic_name' => $picName,
                    'is_reseller' => false,
                    'upline_client_id' => $uplineClientId
                ]);

            if ($insertResult) {
                return 0;
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    function updateClientStatus(Request $request): int
    {
        $clientId = $request->input('clientid');
        $act = $request->input('act');

        Log::debug('clientId: '.$clientId);

        if ($act === 'ENABLE') {
            $updateResult = DB::table('client')
                ->where('client_id', '=', $clientId)
                ->update(['is_active' => true]);
        } else if ($act === 'DISABLE') {
            $updateResult = DB::table('client')
                ->where('client_id', '=', $clientId)
                ->update(['is_active' => false]);
        } else {
            $updateResult = false;
        }

        if ($updateResult) {
            return 0;
        } else {
            return -1;
        }
    }
}
