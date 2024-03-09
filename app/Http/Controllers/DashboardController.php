<?php

namespace App\Http\Controllers;

use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    function index() {
        $thisClientId = Auth::user()->client_id;

        // get data balance
        $dataBalance = DB::table('client_balance')
            ->leftJoin('client', 'client_balance.client_id', '=', 'client.client_id')
            ->where('client_balance.client_id', '=', $thisClientId)
            ->select('client_balance.now_balance', 'client.currency_id')
            ->get();

        // get today traffic
        $dataTodayTraffic = DB::table('transaction_sms')
            ->where('transaction_date', '>=', date('Y-m-d 00:00:00'))
            ->where('transaction_date', '<=', date('Y-m-d 23:59:59'))
            ->where('client_id', '=', $thisClientId)
            ->select(
                DB::raw('count(*) as traffic_total'),
                DB::raw("sum(case when status_code = '000' or status_code = '002' then 1 else 0 end) as traffic_success"),
                DB::raw("sum(case when status_code = '003' then 1 else 0 end) as traffic_in_progress"),
                DB::raw("sum(case when status_code = '122' then 1 else 0 end) as traffic_failed_balance"),
                DB::raw("sum(case when status_code != '000' and status_code != '002' and status_code != '003' and status_code != '122' then 1 else 0 end) as traffic_failed_other"),
                DB::raw("sum(case when status_code = '000' or status_code = '002' then client_price_total else 0 end) as usage_success")
            )
            ->get();

        // get last 30 days data
        $nowDateTime = new \DateTime();
        $endDateTimeStr = $nowDateTime->format('Y-m-d 23:59:59');

        $startDateTime = $nowDateTime->sub(new DateInterval('P30D'));
        $startDateTimeStr = $startDateTime->format('Y-m-d 00:00:00');
        Log::debug('startDateTime: '.$startDateTimeStr.' - endDateTime: '.$endDateTimeStr);

        $dataLast30Days = DB::table('transaction_sms')
            ->where('transaction_date', '>=', $startDateTimeStr)
            ->where('transaction_date', '<=', $endDateTimeStr)
            ->where('client_id', '=', $thisClientId)
            ->select(
                DB::raw('count(*) as traffic_total'),
                DB::raw("sum(case when status_code = '000' or status_code = '002' then 1 else 0 end) as traffic_success"),
                DB::raw("sum(case when status_code = '003' then 1 else 0 end) as traffic_in_progress"),
                DB::raw("sum(case when status_code = '000' or status_code = '002' then client_price_total else 0 end) as usage_success")
            )
            ->get();

        return view('dashboard')->with('dataBalance', $dataBalance)->with('dataTodayTraffic', $dataTodayTraffic)->with('dataLast30Days', $dataLast30Days);
    }
}
