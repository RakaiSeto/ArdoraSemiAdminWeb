<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientGroupController extends Controller
{
    function getDataTable(Request $request) {
        if ($request->ajax()) {
            $data = DB::table('client_group')
                ->select('group_id', 'group_name', 'description')
                ->orderBy('group_id')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditProvider" data-groupId="'.
                        $row->group_id.'" data-groupName="'.$row->group_name.'" data-groupDesc="'.$row->description.
                        '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelProvider" data-groupId="'.
                        $row->group_id.'" data-groupName="'.$row->group_name.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return '-';
        }
    }

    function doSaveNewClientGroup(Request $request) {
        $groupName = $request->groupname;
        $groupDesc = $request->groupdesc;

        $maxLen = 2;
        if (strlen(str_replace(' ', '', $groupName)) > 2) {
            $maxLen = 2;
        } else {
            $maxLen = strlen(str_replace(' ', '', $groupName));
        }
        $groupId = strtoupper(substr(str_replace(' ', '', $groupName), 0, $maxLen)).date('YmdHis');

        $trxStatus = 0;
        try {
            DB::table('client_group')
                ->insert(['group_id' => $groupId, 'group_name' => $groupName, 'description' => $groupDesc]);

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditClientGroup(Request $request) {
        $groupId = $request->groupid;
        $groupName = $request->groupname;
        $groupDesc = $request->groupdesc;

        $trxStatus = 0;
        try {
            DB::table('client_group')
                ->where('group_id', $groupId)
                ->update(['group_name' => $groupName, 'description' => $groupDesc]);

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteClientGroup(Request $request) {
        $groupId = $request->groupid;

        $trxStatus = 0;
        try {
            DB::table('client_group')
                ->where('group_id', $groupId)
                ->delete();

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
