<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DataController extends Controller
{
    function getDataTable(Request $request) {
        if ($request->ajax()) {
            $data = DB::table('master_client_group')
                ->select('group_id', 'group_name', 'group_desc', 'is_active')
                ->orderBy('group_id')
                ->get();

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
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditProvider" data-groupId="'.
                        $row->group_id.'" data-groupName="'.$row->group_name.'" data-groupDesc="'.$row->group_desc.
                        '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelProvider" data-groupId="'.
                        $row->group_id.'" data-groupName="'.$row->group_name.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        } else {
            return '-';
        }
    }


}
