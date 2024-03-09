<?php

namespace App\Http\Controllers;

use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ResellerWebUserController extends Controller
{
    function index() {
        $dataClient = DB::table('client')
            ->where('upline_client_id', '=', Auth::user()->client_id)
            ->where('client_group_id', '=', Auth::user()->group_id)
            ->select('client_id', 'client_name')
            ->orderBy('client_name')
            ->get();

        return view('resellerwebuser')->with('dataClient', $dataClient);
    }

    function getDataTable(Request $request) {
        $email = $request->input('email');
        $clientId = Auth::user()->client_id;
        $groupId = Auth::user()->group_id;

        if (strlen($email) > 0) {
            // Search by email
            $dataWebUser = DB::table('users')
                ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                ->where('users.email', '=', $email)
                ->where('client.upline_client_id', '=', $clientId)
                ->where('client.client_group_id', '=', $groupId)
                ->where('users.is_active', '=', true)
                ->where('client.is_active', '=', true)
                ->select('users.email', 'users.name', 'users.client_id', 'client.client_name', 'users.is_active')
                ->orderBy('users.email');
        } else {
            $dataWebUser = DB::table('users')
                ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                ->where('client.upline_client_id', '=', $clientId)
                ->where('client.client_group_id', '=', $groupId)
                ->where('users.is_active', '=', true)
                ->where('client.is_active', '=', true)
                ->select('users.email', 'users.name', 'users.client_id', 'client.client_name', 'users.is_active')
                ->orderBy('users.email');
        }

        return DataTables::of($dataWebUser)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::user()->privilege === 'B2B_RESELLER') {
                    return '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDeleteClient" data-userEmail="'.
                        $row->email.'" data-isActive="'.$row->is_active.'" data-userName="'.$row->name.'">Delete</a>';
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    function saveNewWebUser(Request $request) {
        $email = $request->input('email');
        $name = $request->input('name');
        $password01 = $request->input('password01');
        $password02 = $request->input('password02');
        $clientId = $request->input('clientid');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return -4;
        } else {
            $groupId = Auth::user()->group_id;
            $encPassword = bcrypt($password01);

            if (strlen($password01) > 0 && $password01 === $password02) {
                // Password valid
                try {
                    $insertResult = DB::table('users')
                        ->insert([
                            'name' => $name,
                            'email' => $email,
                            'password' => $encPassword,
                            'created_at' => date('Y-m-d H:i:s'),
                            'privilege' => 'B2B_USER',
                            'client_id' => $clientId,
                            'group_id' => $groupId,
                            'is_active' => true,
                            'is_client_reseller' => false,
                            'can_neuapix' => false,
                            'phone_number' => '',
                            'username' => $email
                        ]);

                    if ($insertResult) {
                        return 0;
                    } else {
                        return -1;
                    }
                } catch (\Exception $e) {
                    Log::debug('Error saving new web user -> e: '.$e->getMessage());

                    return -5;
                }
            } else {
                return -3;
            }
        }
    }

    function deleteWebUser(Request $request) {
        try {
            $email = $request->input('email');

            $deleteResult = DB::table('users')
                ->where('email', $email)
                ->update([
                    'is_active' => false
                ]);

            if ($deleteResult) {
                return 0;
            } else {
                return -2;
            }
        } catch (\Exception $e) {
            return -1;
        }
    }
}
