<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Helpers\Auditrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BalanceController extends Controller
{
    function index() {
        return view('balance');
    }

    function getDataTable(Request $request) {
        try {
            if ($request->session()->get('privilege') === 'ROOT') {
                if (isset($request->selectedclientid)) {
                    $dataBalance = DB::table('client')
                        ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                        ->where('client.client_name', 'like', '%'.$request->selectedclientid.'%')
                        ->select('client.client_id', 'client.client_name', 'client.currency_id', DB::raw('coalesce(client_balance.now_balance, 0) as now_balance'),
                            DB::raw('coalesce(client_balance.last_usage_value, 0) as last_usage_value'), 'client_balance.last_usage_date_time', 'client_balance.expiry_date')
                        ->orderBy('client.client_name')
                        ->get();
                } else {
                    $dataBalance = DB::table('client')
                        ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                        ->select('client.client_id', 'client.client_name', 'client.currency_id', DB::raw('coalesce(client_balance.now_balance, 0) as now_balance'),
                            DB::raw('coalesce(client_balance.last_usage_value, 0) as last_usage_value'), 'client_balance.last_usage_date_time', 'client_balance.expiry_date')
                        ->orderBy('client.client_name')
                        ->get();
                }
            } else if ($request->session()->get('privilege') === 'SYSADMIN' || $request->session()->get('privilege') === 'SYSFINANCE' || $request->session()->get('privilege') === 'SYSOP' || $request->session()->get('privilege') === 'REPORT' || $request->session()->get('privilege') === "B2B_USER" || $request->session()->get('privilege') === "B2B_RESELLER")  {
                if (isset($request->selectedclientid)) {
                    $dataBalance = DB::table('client')
                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
                        ->where('client.client_name', 'like', '%'.$request->selectedclientid.'%')
                        ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->select('client.client_id', 'client.client_name', 'client.currency_id', DB::raw('coalesce(client_balance.now_balance, 0) as now_balance'),
                            DB::raw('coalesce(client_balance.last_usage_value, 0) as last_usage_value'), 'client_balance.last_usage_date_time', 'client_balance.expiry_date')
                        ->orderBy('client.client_name')
                        ->get();
                } else {
                    $dataBalance = DB::table('client')
                        ->where('client_to_reseller.reseller_id', '=', $request->session()->get('reseller_id'))
                        ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                        ->leftJoin('client_to_reseller', 'client_to_reseller.client_id', '=', 'client.client_id')
                        ->select('client.client_id', 'client.client_name', 'client.currency_id', DB::raw('coalesce(client_balance.now_balance, 0) as now_balance'),
                            DB::raw('coalesce(client_balance.last_usage_value, 0) as last_usage_value'), 'client_balance.last_usage_date_time', 'client_balance.expiry_date')
                        ->orderBy('client.client_name')
                        ->get();
                }
            }

            return DataTables::of($dataBalance)
                ->addColumn('action', function($row){
                    if (Auth::user()->privilege === 'ROOT' || Auth::user()->privilege === 'SYSADMIN' || Auth::user()->privilege === 'SYSFINANCE' || Auth::user()->privilege === 'SYSOPE') {
                        return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalTopUp" data-clientId="'.
                            $row->client_id.'">Top Up</a>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (Exception $e) {
            //return $e;
            return '-';
        }
    }

    function getClientBalance(Request $request) {
        try {
            $selectedClientId = $request->selectedClientId;

            $dataBalance = DB::table('client')
                ->leftJoin('client_balance', 'client.client_id', '=', 'client_balance.client_id')
                ->where('client.client_id', '=', $selectedClientId)
                ->select('client.client_name', DB::raw('coalesce(client_balance.now_balance, 0) as client_balance'), 'client.currency_id')
                ->get();

            return json_encode($dataBalance);
        } catch (Exception $e) {
            return '';
        }
    }

    function topupClientBalance(Request $request) {
        try {
            $username = Auth::user()->email;

            $clientId = $request->clientId;
            $topupValue = $request->value;
            $expiryDate = date('Y-m-d 23:59:59', strtotime("+3 month"));

            $messageId = Uuid::uuid4()->toString();
            $messageId = str_replace('-', '', $messageId);

            $countRecord = DB::table('client_balance')
                ->where('client_id', '=', $clientId)
                ->count();

            $prevBalance = 0.00;
            $nowBalance = 0.00;
            $lastUsageValue = 0.00;
            if ($countRecord > 0) {
                // Update
//                $hasilTopUp = DB::table('client_balance')
//                    ->where('client_id', '=', $clientId)
//                    ->increment('now_balance', $topupValue, ['last_usage_value' => $topupValue, 'last_usage_date_time' => date('Y-m-d H:i:s'),
//                        'last_usage_type' => 'TOPUP', 'last_usage_by' => 'WEB_ADMIN', 'expiry_date' => $expiryDate, 'last_usage_message_id' => '']);

                $hasilTopUp = DB::selectOne("update client_balance set now_balance = now_balance + ?, last_usage_value = ?,
                          last_usage_date_time = ?, last_usage_type = ?, last_usage_by = ?, expiry_date = ?,
                          last_usage_message_id = ? where client_id = ? returning now_balance, last_usage_value",
                        [$topupValue, $topupValue, date('Y-m-d H:i:s'), 'TOP_UP', 'WEB_ADMIN', $expiryDate, $messageId, $clientId]);

                //Log::debug('hasilTopUp: '.$hasilTopUp);
                $nowBalance = $hasilTopUp->now_balance;
                $lastUsageValue = $hasilTopUp->last_usage_value;
                $prevBalance = $nowBalance - $lastUsageValue;
            } else {
                // Insert
                $hasilTopUp = DB::table('client_balance')
                    ->insert(['client_id' => $clientId, 'now_balance' => $topupValue, 'last_usage_value' => $topupValue, 'last_usage_date_time' => date('Y-m-d H:i:s'),
                        'last_usage_type' => 'TOPUP', 'last_usage_by' => 'WEB_ADMIN', 'expiry_date' => $expiryDate, 'last_usage_message_id' => '']);

                $nowBalance = $topupValue;
                $lastUsageValue = $topupValue;
                $prevBalance = 0.00;
            }
            Log::debug('nowBalance: '.$nowBalance.' - lastUsageValue: '.$lastUsageValue.' - prevBalance: '.$prevBalance);

            // Insert into balance Adjustment history
            $hasilBalanceHistory = DB::table('client_balance_adjustment_history')
                ->insert([
                    'message_id' => $messageId,
                    'adjustment_type' => 'TOP_UP',
                    'value' => $topupValue,
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'advance_topup_message_id' => '',
                    'advance_payment_balance' => 0.00,
                    'is_advance_payment' => false,
                    'client_id' => $clientId,
                    'username' => $username,
                    'previous_balance' => $prevBalance,
                    'after_balance' => $nowBalance,
                    'bank_transfer_image_path' => ''
                ]);
            Log::debug('clientId: '.$clientId.' -> hasilBalanceHistory: '.$hasilBalanceHistory);

            // Insert into transaction_sms_financial
            $hasilInsertTrxSMSFinancial = DB::table('transaction_sms_financial')
                ->insert([
                    'message_id' => $messageId,
                    'usage_type' => 'TOP_UP',
                    'usage_by' => 'WEB_ADMIN',
                    'client_id' => $clientId,
                    'transaction_datetime' => date('Y-m-d H:i:s'),
                    'description' => 'TOP UP FOR CLIENT ID '.$clientId.' BY USER '.$username,
                    'previous_balance' => $prevBalance,
                    'usage' => $topupValue,
                    'after_balance' => $nowBalance,
                    'vendor_id' => '',
                    'vendor_price' => 0.00,
                    'vendor_currency' => ''
                ]);
            Log::debug('clientId: '.$clientId.' -> hasilInsertTrxSMSFinancial: '.$hasilInsertTrxSMSFinancial);

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "BALANCE", "TOP UP : PREVIOUS (".$prevBalance.") -> ADD (".$topupValue.") -> NOW (".$nowBalance. ") TO : ".$clientId. " || SUCCESS", $request->getHttpHost());

            return 0;
        } catch (Exception $e) {
            return $e;
            return -1;
        }
    }
}
