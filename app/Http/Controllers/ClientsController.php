<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Yajra\DataTables\Facades\DataTables;

class ClientsController extends Controller
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

        return view('client')->with('clientGroupData', $data)->with('clientCurrencyData', $dataCurrency)->with('countryData', $dataCountry);
    }

    function getDataTable(Request $request) {
        $userGroupId = Auth::user()->group_id;

        $searchField = $request->input("searchcategory");
        $searchKeyword = $request->input("searchkeyword");

        if ($request->ajax()) {
            if (Auth::user()->privilege === 'ROOT') {
                if (strlen(trim($searchKeyword)) == 0) {
                    // No searching
                    $data = DB::table('client')
                        ->leftJoin('client_group', 'client.client_group_id', '=', 'client_group.group_id')
                        ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                        ->select('client.client_id', 'client.client_name', 'client.client_country', 'country.country_name', 'client.client_group_id', 'client_group.group_name', 'client.currency_id', 'client.is_active')
                        ->orderBy('client.client_name')
                        ->get();
                } else {
                    // Searching
                    $theField = 'client.client_id';
                    $comparisan = '=';
                    $incomingData = $searchKeyword;
                    if ($searchField === 'clientId') {
                        $theField = 'client.client_id';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                    } else if ($searchField === 'clientName') {
                        $theField = 'client.client_name';
                        $comparisan = 'like';
                        $incomingData = pg_escape_string('%'.$searchKeyword.'%');
                    } else if ($searchField === 'clientCountry') {
                        $theField = 'country.country_name';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                    } else if ($searchField === 'clientGroupName') {
                        $theField = 'client_group.group_name';
                        $comparisan = 'like';
                        $incomingData = pg_escape_string('%'.$searchKeyword.'%');
                    }

                    $data = DB::table('client')
                        ->leftJoin('client_group', 'client.client_group_id', '=', 'client_group.group_id')
                        ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                        ->select('client.client_id', 'client.client_name', 'client.client_country', 'country.country_name', 'client.client_group_id', 'client_group.group_name', 'client.currency_id', 'client.is_active')
                        ->where($theField, $comparisan, $incomingData)
                        ->orderBy('client.client_name')
                        ->get();
                }
            } else {
                if (strlen(trim($searchKeyword)) == 0) {
                    $data = DB::table('client')
                        ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                        ->select('client.client_id', 'client.client_name', 'client.client_country', 'country.country_name', 'client.currency_id', 'client.is_active')
                        ->where('client.client_group_id', $userGroupId)
                        ->orderBy('client.client_name')
                        ->get();
                } else {
                    // Searching
                    $theField = 'client.client_id';
                    $comparisan = '=';
                    $incomingData = $searchKeyword;
                    if ($searchField === 'clientId') {
                        $theField = 'client.client_id';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                    } else if ($searchField === 'clientName') {
                        $theField = 'client.client_name';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'clientCountry') {
                        $theField = 'country.country_name';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                    }

                    $data = DB::table('client')
                        ->leftJoin('country', 'client.client_country', '=', 'country.country_id')
                        ->select('client.client_id', 'client.client_name', 'client.client_country', 'country.country_name', 'client.currency_id', 'client.is_active')
                        ->where('client.client_group_id', $userGroupId)
                        ->where($theField, $comparisan, $incomingData)
                        ->orderBy('client.client_name')
                        ->get();
                }
            }

            return DataTables::of($data)
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
                    if (Auth::user()->privilege === 'ROOT') {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditClient" data-clientId="'.
                            $row->client_id.'" data-clientName="'.$row->client_name.'" data-clientCountryId="'.$row->client_country.'" data-clientGroupId="'.$row->client_group_id.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelClient" data-clientId="'.
                            $row->client_id.'" data-clientName="'.$row->client_name.'">Delete</a>';
                    } else {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditClient" data-clientId="'.
                            $row->client_id.'" data-clientName="'.$row->client_name.'" data-clientCountryId="'.$row->client_country.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelClient" data-clientId="'.
                            $row->client_id.'" data-clientName="'.$row->client_name.'">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        } else {
            return '-';
        }
    }

    function getClientListByGroupId(Request $request) {
        $clientGroupId = $request->clientgroupid;

        try {
            return DB::table('client')
                ->select('client_id', 'client_name')
                ->where('client_group_id', '=', $clientGroupId)
                ->orderBy('client_name')
                ->get();
        } catch (Exception $e) {
            return '';
        }
    }

    function doSaveNewClient(Request $request): int
    {
        $clientName = $request->input('clientname');
        $clientIsReseller = $request->input('clientisreseller');
        $clientCountryId = $request->input('clientcountryid');
        $clientBusinessModel = $request->input('clientbusinessmodel');
        $clientCurr = $request->input('clientcurrency');

        $clientGroupId = $request->input('clientgroupid');
        if (Auth::user()->privilege != 'ROOT') {
            $clientGroupId = Auth::user()->group_id;
        }

        if (strlen(str_replace(' ', '', $clientName)) > 2) {
            $maxLen = 2;
        } else {
            $maxLen = strlen(str_replace(' ', '', $clientName));
        }
        $clientId = strtoupper(substr(str_replace(' ', '', $clientName), 0, $maxLen)).date('YmdHis');

        $isReseller = false;
        if ($clientIsReseller === 'TRUE') {
            $isReseller = true;
        }

        try {
            DB::table('client')
                ->insert(['client_id' => $clientId, 'client_group_id' => $clientGroupId, 'client_name' => $clientName,
                    'client_country' => $clientCountryId, 'is_active' => true, 'business_model' => $clientBusinessModel,
                    'currency_id' => $clientCurr, 'is_reseller' => $isReseller]);

            // Update redis
            //$trxStatus = $this->reloadRedisClient();

            $trxStatus = 0;
        } catch (Exception $e) {
            Log::debug($e);
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditClient(Request $request): int
    {
        $clientId = $request->input('clientid');
        $clientName = $request->input('clientname');
        $clientCountryId = $request->input('clientcountryid');
        $clientCurr = $request->input('clientcurrency');

        if (Auth::user()->privilege === 'ROOT') {
            $clientGroupId = $request->input('clientgroupid');
        }

        $trxStatus = 0;
        try {
            if (Auth::user()->privilege === 'ROOT') {
                DB::table('client')
                    ->where('client_id', $clientId)
                    ->update(['client_name' => $clientName, 'client_country' => $clientCountryId, 'client_group_id' => $clientGroupId, 'currency_id' => $clientCurr]);
            } else {
                DB::table('client')
                    ->where('client_id', $clientId)
                    ->update(['client_name' => $clientName, 'client_country' => $clientCountryId, 'currency_id' => $clientCurr]);
            }

            // Update redis
            //$this->reloadRedisClient();

            $trxStatus = 0;
        } catch (\Exception $e) {
            Log::debug($e);
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteClient(Request $request): int
    {
        $clientId = $request->input('clientid');

        try {
            DB::table('client')
                ->where('client_id', $clientId)
                ->delete();

            // Update redis
            //$this->reloadRedisClient();

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            Log::debug($e);
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
