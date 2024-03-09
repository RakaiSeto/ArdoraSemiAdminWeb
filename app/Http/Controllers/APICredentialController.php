<?php /** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpUnused */

/** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Nonstandard\Uuid;
use Yajra\DataTables\Facades\DataTables;

class APICredentialController extends Controller
{
    function index() {
        $privilege = Auth::user()->privilege;
        $groupId = Auth::user()->group_id;

        if ($privilege === 'ROOT') {
            $dataClientGroup = DB::table('client_group')
                ->select('group_id', 'group_name')
                ->orderBy('group_name')
                ->get();

            $dataClient = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();

            $dataAPIProduct = DB::table('api_product')
                ->select('api_id', 'api_name')
                ->orderBy('api_name')
                ->get();

            return view('userapi')->with('clientGroup', $dataClientGroup)->with('client', $dataClient)->with('apiProduct', $dataAPIProduct);
        } else {
            $dataClient = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('client_group_id', '=', $groupId)
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();

            $dataAPIProduct = DB::table('api_product')
                ->select('api_id', 'api_name')
                ->orderBy('api_name')
                ->get();

            return view('userapi')->with('client', $dataClient)->with('apiProduct', $dataAPIProduct);
        }
    }

    function getDataTable(Request $request) {
        $privilege = Auth::user()->privilege;
        $groupId = Auth::user()->group_id;

        try {
            if ($request->ajax()) {
                $searchKeyword = trim($request->input('searchkeyword'));
                $searchCategory = $request->input('searchcategory');

                if ($privilege === 'ROOT') {
                    if (strlen($searchKeyword) > 0) {
                        // Ada search
                        $theField = 'user_api.username';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                        if ($searchCategory === 'username') {
                            $theField = 'user_api.username';
                            $comparisan = 'ilike';
                            $incomingData = '%'.$searchKeyword.'%';
                        } else if ($searchCategory === 'clientName') {
                            $theField = 'client.client_name';
                            $comparisan = 'ilike';
                            $incomingData = '%'.$searchKeyword.'%';
                        }

                        $dataAPICredential = DB::table('user_api')
                            ->leftJoin('client', 'user_api.client_id', '=', 'client.client_id')
                            ->leftJoin('api_product', 'user_api.access_type', '=', 'api_product.api_id')
                            ->leftJoin('client_group', 'client.client_group_id', '=', 'client_group.group_id')
                            ->select('user_api.username', 'user_api.client_id', 'client.client_name', DB::raw("coalesce(user_api.access_type, '') as access_type"),
                                DB::raw("coalesce(api_product.api_name, '') as api_name"), DB::raw("coalesce(user_api.registered_ip_address, '') as registered_ip_address"), DB::raw("coalesce(user_api.token, '') as token"),
                                'user_api.is_active', DB::raw("coalesce(client.client_group_id, '') as client_group_id"), DB::raw("coalesce(client_group.group_name, '') as group_name"))
                            ->where($theField, $comparisan, $incomingData)
                            ->orderBy('user_api.username')
                            ->get();
                    } else {
                        // Gak ada search
                        $dataAPICredential = DB::table('user_api')
                            ->leftJoin('client', 'user_api.client_id', '=', 'client.client_id')
                            ->leftJoin('api_product', 'user_api.access_type', '=', 'api_product.api_id')
                            ->leftJoin('client_group', 'client.client_group_id', '=', 'client_group.group_id')
                            ->select('user_api.username', 'user_api.client_id', 'client.client_name', DB::raw("coalesce(user_api.access_type, '') as access_type"),
                                DB::raw("coalesce(api_product.api_name, '') as api_name"), DB::raw("coalesce(user_api.registered_ip_address, '') as registered_ip_address"), DB::raw("coalesce(user_api.token, '') as token"),
                                'user_api.is_active', DB::raw("coalesce(client.client_group_id, '') as client_group_id"), DB::raw("coalesce(client_group.group_name, '') as group_name"))
                            ->orderBy('user_api.username')
                            ->get();
                    }
                } else {
                    if (strlen($searchKeyword) > 0) {
                        // Ada search
                        $theField = 'user_api.username';
                        $comparisan = '=';
                        $incomingData = $searchKeyword;
                        if ($searchCategory === 'username') {
                            $theField = 'user_api.username';
                            $comparisan = 'ilike';
                            $incomingData = '%' . $searchKeyword . '%';
                        } else if ($searchCategory === 'clientName') {
                            $theField = 'client.client_name';
                            $comparisan = 'ilike';
                            $incomingData = '%' . $searchKeyword . '%';
                        }

                        $dataAPICredential = DB::table('user_api')
                            ->leftJoin('client', 'user_api.client_id', '=', 'client.client_id')
                            ->leftJoin('api_product', 'user_api.access_type', '=', 'api_product.api_id')
                            ->where('client.client_group_id', '=', $groupId)
                            ->select('user_api.username', 'user_api.client_id', 'client.client_name', DB::raw("coalesce(user_api.access_type, '') as access_type"),
                                DB::raw("coalesce(api_product.api_name, '') as api_name"), DB::raw("coalesce(user_api.registered_ip_address, '') as registered_ip_address"), DB::raw("coalesce(user_api.token, '') as token"),
                                'user_api.is_active')
                            ->where($theField, $comparisan, $incomingData)
                            ->orderBy(DB::raw("user_api.username"))
                            ->get();
                    } else {
                        $dataAPICredential = DB::table('user_api')
                            ->leftJoin('client', 'user_api.client_id', '=', 'client.client_id')
                            ->leftJoin('api_product', 'user_api.access_type', '=', 'api_product.api_id')
                            ->where('client.client_group_id', '=', $groupId)
                            ->select('user_api.username', 'user_api.client_id', 'client.client_name', DB::raw("coalesce(user_api.access_type, '') as access_type"),
                                DB::raw("coalesce(api_product.api_name, '') as api_name"), DB::raw("coalesce(user_api.registered_ip_address, '') as registered_ip_address"), DB::raw("coalesce(user_api.token, '') as token"),
                                'user_api.is_active')
                            ->orderBy(DB::raw("user_api.username"))
                            ->get();
                    }
                }

                return DataTables::of($dataAPICredential)
                    ->editColumn('is_active', function($row) {
                        if ($row->is_active === true) {
                            $booleanBox = '<i class="fa fa-check" aria-hidden="true" style="color: green"></i>';
                        } else {
                            $booleanBox = '<i class="fa fa-close" aria-hidden="true" style="color: red"></i>';
                        }

                        return $booleanBox;
                    })
                    ->addColumn('action', function($row){
                        if (Auth::user()->privilege === 'ROOT') {
                            $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditAPICredential" data-apiUserName="'.$row->username.'" data-apiClientId="'.
                                $row->client_id.'" data-apiAccessType="'.$row->access_type.'" data-apiRegisteredIPAddress="'.addslashes($row->registered_ip_address).
                                '" data-apiGroupId="'.$row->client_group_id.'" data-apiIsActive="'.$row->is_active.'" data-apiToken="'.$row->token.
                                '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelAPICredential" data-apiUserName="'.
                                $row->username.'" data-apiClientName="'.$row->client_name.'" data-apiName="'.$row->api_name.'">Delete</a>';
                        } else {
                            $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditAPICredential" data-apiUserName="'.$row->username.'" data-apiClientId="'.
                                $row->client_id.'" data-apiAccessType="'.$row->access_type.'" data-apiRegisteredIPAddress="'.addslashes($row->registered_ip_address).
                                '" data-apiIsActive="'.$row->is_active.'" data-apiToken="'.$row->token.
                                '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelAPICredential" data-apiUserName="'.
                                $row->username.'" data-apiClientName="'.$row->client_name.'" data-apiName="'.$row->api_name.'">Delete</a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action', 'is_active'])
                    ->make(true);
            } else {
                return '';
            }
        } catch (Exception $e) {
            //return $e;
            return '';
        }
    }

    function doSaveNewAPICredential(Request $request) {
        try {
            $username = $request->username;
            $password = $request->password;
            $apiProductId = $request->apiproductid;
            $clientId = $request->clientid;
            $registeredIpAddress = $request->registeredapiaddress;

            $generatedToken = str_replace('-', '', Uuid::uuid4());

            if (preg_match('/[\'^£$%&*()}{@#~?<>,|=_+¬-]/', $username))
            {
                // username contain invalid character
                return -2;
            } else {
                DB::table('user_api')
                    ->insert(['username' => $username, 'password' => bcrypt($password), 'client_id' => $clientId, 'access_type' => $apiProductId,
                        'registered_ip_address' => $registeredIpAddress, 'is_active' => true, 'token' => $generatedToken]);

                return $generatedToken;
            }
        } catch (Exception $e) {
            return $e;
            //return -1;
        }
    }

    function doSaveEditAPICredential(Request $request) {
        try {
            $origusername = $request->origusername;
            $username = $request->username;
            $password = $request->password;
            $apiProductId = $request->apiproductid;
            $clientId = $request->clientid;
            $registeredIpAddress = $request->registeredapiaddress;

            if (preg_match('/[\'^£$%&*()}{@#~?<>,|=_+¬-]/', $username))
            {
                // username contain invalid character
                return -2;
            } else {
                if (strlen($password) > 0) {
                    DB::table('user_api')
                        ->where('username', '=', $origusername)
                        ->update(['username' => $username, 'password' => bcrypt($password), 'client_id' => $clientId, 'access_type' => $apiProductId,
                            'registered_ip_address' => $registeredIpAddress]);
                } else {
                    DB::table('user_api')
                        ->where('username', '=', $origusername)
                        ->update(['username' => $username, 'client_id' => $clientId, 'access_type' => $apiProductId,
                            'registered_ip_address' => $registeredIpAddress]);
                }

                return 0;
            }
        } catch (Exception $e) {
            //return $e;
            return -1;
        }
    }
}
