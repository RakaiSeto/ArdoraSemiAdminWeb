<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    function index() {
        return view('currencymanagement');
    }

    function getDataTable(Request $request) {
        if ($request->ajax()) {
            $data = DB::table('currency')
                ->select('currency_id', 'currency_name', 'currency_description')
                ->orderBy('currency_name')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditCurrency" data-currencyId="'.
                            $row->currency_id.'" data-currencyName="'.$row->currency_name.'" data-currencyDesc="'.$row->currency_description.
                            '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelCurrency" data-currencyId="'.
                            $row->currency_id.'" data-currencyName="'.$row->currency_name.'">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return '-';
        }
    }

    function doSaveNewCurrency(Request $request) {
        $currencyId = $request->currencyid;
        $currencyName = $request->currencyname;
        $currencyDescription = $request->currencydescription;

        try {
            DB::table('currency')
                ->insert(['currency_id' => $currencyId, 'currency_name' => $currencyName, 'currency_description' => $currencyDescription, 'is_active' => true]);

            $trxStatus = 0;
        } catch (\Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditCurrency(Request $request) {
        $currentCurrencyId = $request->currentcurrencyid;
        $currencyId = $request->currencyid;
        $currencyName = $request->currencyname;
        $currencyDescription = $request->currencydescription;

        try {
            DB::table('currency')
                ->where('currency_id', $currentCurrencyId)
                ->update(['currency_id' => $currencyId, 'currency_name' => $currencyName, 'currency_description' => $currencyDescription]);

            $trxStatus = 0;
        } catch (\Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteCurrency(Request $request) {
        $currencyId = $request->currencyid;

        try {
            DB::table('currency')
                ->where('currency_id', $currencyId)
                ->delete();

            $trxStatus = 0;
        } catch (\Exception $e) {
            $trxStatus = -1;
        }

        return $trxStatus;
    }
}
