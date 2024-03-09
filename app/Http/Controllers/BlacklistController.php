<?php

namespace App\Http\Controllers;


use App\Http\Helpers\Auditrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BlacklistController extends Controller
{
    function index() {
        // Load select - group

        $data = DB::table('message_blacklist')
            ->select('keyword')
            ->get();

            return view('blacklist')->with('keywordData', $data);
        }

    function getDataTable(Request $request) {
        $userGroupId = Auth::user()->group_id;

        if ($request->ajax()) {
            $data = DB::table('message_blacklist')
                ->select('keyword')
                ->get();
           
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditKeyword" data-editKeyword="'.
                        $row->keyword.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDeleteKeyword" data-deleteKeyword="'.
                        $row->keyword.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function doSaveNewKeyword(Request $request) {
        $keyword = $request->keyword;
        $active = true;
        $username = Auth::user()->email;

        try {
            DB::table('message_blacklist')
                ->insert(['keyword' => $keyword, 'is_active' => $active, 'username' => $username]);

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "BLACKLIST", "ADD : ".$keyword. " || SUCCESS", $request->getHttpHost());

            $trxStatus = 0;
        } catch (Exception $e) {

            $trxStatus = -1;
        }


        return $trxStatus;
    }

    function doSaveEditKeyword(Request $request) {
        $keyword = $request->keyword;
        $newKeyword = $request->newkeyword;
        $active = true;
        $username = Auth::user()->email;


        try {
            DB::table('message_blacklist')
                ->where('keyword', '=', $keyword)
                ->update(['keyword' => $newKeyword, 'username' => $username]);

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "BLACKLIST", "UPDATE : ".$keyword. " TO : ".$newKeyword." || SUCCESS", $request->getHttpHost());

        return $trxStatus;
    }

    function doDeleteKeyword(Request $request) {
        $keyword = $request->keyword;

        try {
            DB::table('message_blacklist')
                ->where('keyword', '=', $keyword)
                ->delete();

            $trxStatus = 0;
        } catch (Exception $e) {
            $trxStatus = -1;
        }

        Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "BLACKLIST", "DELETE : ".$keyword. " || SUCCESS", $request->getHttpHost());

        return $trxStatus;
    }
}
