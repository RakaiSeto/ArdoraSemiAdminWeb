<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    function getProductsByCategory(Request $request): string
    {
        $productCategory = $request->input('productcategory');

        return DB::table('product')
            ->where('category', '=', $productCategory)
            ->where('is_active', '=', true)
            ->select('product_id', 'product_name')
            ->get()->toJson();
    }

    function getAPIUserNameByClientId(Request $request): string
    {
        $clientId = $request->input('clientid');

        return DB::table('user_api')
            ->where('client_id', '=', $clientId)
            ->where('is_active', '=', true)
            ->select('username')
            ->get()->toJson();
    }
}
