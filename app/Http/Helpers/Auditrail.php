<?php
    namespace App\Http\Helpers;

    use Illuminate\Support\Facades\DB;

    date_default_timezone_set('Asia/Jakarta');

    class Auditrail {
        public static function save_auditrail($username, $remoteIPAddress, $menu, $activity, $webURL) {
            $activityId = $username.'-'.date('YmdHis').'-'.strtoupper(str_replace(' ', '_', $menu));

            DB::table('activity')
                ->insert([
                    'activity_id' => $activityId,
                    'username' => $username,
                    'date_time' => date('Y-m-d H:i:s'),
                    'remote_ip_address' => $remoteIPAddress,
                    'menu' => $menu,
                    'activity' => $activity,
                    'web_url' => $webURL
                ]);
        }
    }

