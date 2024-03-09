<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    function index() {
        return view('livewire.blastme_activity');
    }

    function getDataTable(Request $request) {
        $clientGroupId = env('APP_CLIENT_GROUP_ID');

        $generalSearchPar = $request->input('generalsearch');
        $dateRange = $request->input('daterange');

        // Get dateRange
        $startDate = date('Y-m-d 00:00:00');
        $endDate = date('Y-m-d 23:59:59');

        if (strlen($dateRange) > 0 ) {
            // Split by -
            $splittedDate = explode('-', $dateRange);

            $xStartDate = trim($splittedDate[0]);
            $splitXStartDate = explode('/', $xStartDate);

            $splitXStartDateDay = $splitXStartDate[0];
            $splitXStartDateMonth = $splitXStartDate[1];
            $splitXStartDateYear = $splitXStartDate[2];

            $xEndDate = trim($splittedDate[1]);
            $splitXEndDate = explode('/', $xEndDate);

            $splitXEndDateDay = $splitXEndDate[0];
            $splitXEndDateMonth = $splitXEndDate[1];
            $splitXEndDateYear = $splitXEndDate[2];

            $startDate = $splitXStartDateYear.'-'.$splitXStartDateMonth.'-'.$splitXStartDateDay.' 00:00:00';
            $endDate = $splitXEndDateYear.'-'.$splitXEndDateMonth.'-'.$splitXEndDateDay.' 23:59:59';
        }
        Log::debug('startDate: '.$startDate.' - endDate: '.$endDate);

        $dataActivity = DB::select(DB::raw('select act.activity_id, act.username, act.date_time, act.remote_ip_address, act.menu, act.activity, act.web_url, 
        lusr.client_id, lusr.group_id, lusr.privilege, lusr.name, lccc.client_name 
        from activity as act 
            left join lateral (select client_id, group_id, privilege, name from users as usr where act.username = usr.email limit 1) lusr on true 
            left join lateral (select client_name from client as ccc where lusr.client_id = ccc.client_id limit 1) lccc on true 
            where lusr.group_id = :groupId and act.date_time >= :startDate and act.date_time <= :endDate and (act.username ilike :theUserName or act.menu ilike :theMenu or act.activity ilike :theActivity)'),
            array('groupId' => $clientGroupId, 'startDate' => $startDate, 'endDate' => $endDate, 'theUserName' => '%'.$generalSearchPar.'%', 'theMenu' => '%'.$generalSearchPar.'%', 'theActivity' => '%'.$generalSearchPar.'%'));

        return DataTables::of($dataActivity)
            ->addIndexColumn()
            ->make(true);
    }
}
