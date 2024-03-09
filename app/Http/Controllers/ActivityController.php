<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    function index() {
        $today = date('Y-m-d', strtotime(now()));
        $dataActivity = DB::table('activity')
            ->select('date_time', 'username', 'menu', 'activity', 'remote_ip_address')
            ->whereDate('date_time', '=', $today)
            ->orderBy('date_time', 'DESC')
            ->get();

            return view('activity')->with('activityData', $dataActivity);
        
    }

    function getDataTable(Request $request) {
        $today = date('Y-m-d', strtotime(now()));
        $loginUserPrivilege = Auth::user()->privilege;
        $loginUserGroupId = Auth::user()->group_id;

        $searchField = $request->input("searchcategory");
        $searchKeyword = $request->input("searchkeyword");

        if ($request->ajax()) {
                if (strlen(trim($searchKeyword)) == 0) {
                    // Not Searching
                    $dataActivity = DB::table('activity')
                    ->select('date_time', 'username', 'menu', 'activity', 'remote_ip_address')
                    ->whereDate('date_time', '=', $today)
                    ->orderBy('date_time', 'DESC')
                    ->get();
                } else {
                    // Searching
                    $theField = 'username';
                    $comparisan = 'ilike';
                    $incomingData = '%'.$searchKeyword.'%';
                    if ($searchField === 'username') {
                        $theField = 'username';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'menu') {
                        $theField = 'menu';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'remote_ip_address') {
                        $theField = 'remote_ip_address';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    }

                    $dataActivity = DB::table('activity')
                        ->select('date_time', 'username', 'menu', 'activity', 'web_url', 'remote_ip_address')
                        ->where($theField, $comparisan, $incomingData)
                        ->orderBy('date_time', 'DESC')
                        ->get();
                }
            

            return DataTables::of($dataActivity)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditWebUser" data-username="'.
                //         $row->username.'">Detail</a>';
                // })
                // ->rawColumns(['action'])
                ->make(true);
        } else {
            return '-';
        }
    }

    function doSaveNewWebUser(Request $request) {
        $email = $request->email;
        $fullname = $request->fullname;
        $password = $request->password;
        $privilege = $request->privilege;
        $canneuapix = $request->canneuapix;

        $clientGroupID = '';
        if (Auth::user()->privilege === 'ROOT') {
            $clientGroupID = $request->clientgroup;
        }

        $clientId = $request->client;

        // Overrider client group id for non ROOT
        if (Auth::user()->privilege != 'ROOT') {
            $clientGroupID = Auth::user()->group_id;
        }

        $encPassword = bcrypt($password);

        $isReseller = false;
        if ($privilege === 'B2B_RESELLER') {
            $isReseller = true;
        }

        try {
            if ($canneuapix === 'true') {
                // Prepare for its API username - email replace @ and . with _
                $apiUserName = str_replace('@', '_', $email);
                $apiUserName = str_replace('.', '_', $apiUserName);

                //return $apiUserName;

                // Buat new account di neuapix - Hanya utk provisioning ke Neu APIX
//                $defaultNeuApixPassword = 'W3lc0m3Tele';
//                $post = [
//                    'FriendlyName' => $fullname,
//                    'EmailAddress' => $email,
//                    'Password'   => $defaultNeuApixPassword
//                ];
//
//                $ch = curl_init();
//
//                // NEUAPIX Pintar
//                curl_setopt($ch, CURLOPT_URL,"https://ACac478ba2be840ebb61e3068bd18f53c4:4b7a5bc000682b815e3f96a5835ab7f8@jatis.neuapix.com/restcomm/2012-04-24/Accounts/");
//
//                // NEXUAPIX TAU https://AC67dc3da594a57fdb9145e217e33e15cd:4b7a5bc000682b815e3f96a5835ab7f8@onecontact.neuapix.com/restcomm/2012-04-24/Accounts/
//                //curl_setopt($ch, CURLOPT_URL, "https://AC67dc3da594a57fdb9145e217e33e15cd:4b7a5bc000682b815e3f96a5835ab7f8@onecontact.neuapix.com/restcomm/2012-04-24/Accounts/");
//
//                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                //curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, "FriendlyName=".$fullname."&EmailAddress=".$email."&Password=$defaultNeuApixPassword");
//
//                $response = curl_exec($ch);
//
//                curl_close($ch);
//
//                //return $response;
//
//                try{
//                    if(strpos($response, 'The email address used for the new account is already in use.') > -1) {
//                        $trxStatus = -9; // Email address is already in use
//                    } else {
//                        $theXML = simplexml_load_string($response);
//                        //print_r($theXML);
//
//                        $theArray = json_decode(json_encode((array)$theXML), TRUE);
//                        //						var_dump($theArray);
//
//                        if(property_exists($theXML, 'Account')){
//                            // echo 'CROT';
//                            $accountId = $theArray["Account"]["Sid"];
//                            $tokenId = $theArray["Account"]["AuthToken"];
//                            $uri = $theArray["Account"]["Uri"];
//
//                            // echo "accountId: ".$accountId.", tokenId: ".$tokenId;
//                            // Composer URL, insert into transaceiver_telin_property
//
//                            // NEUAPIX Pintar
//                            $urlTelin = "https://".$accountId.":".$tokenId."@jatis.neuapix.com/restcomm".$uri."/SMS/Messages";
//
//                            // NEUAPIX TAU
//                            //$urlTelin = "https://".$accountId.":".$tokenId."@onecontact.neuapix.com/restcomm".$uri."/SMS/Messages";
//                            //echo "urlTelin: ".$urlTelin;
//
//                            // Insert into table transceiver_telin_property
//                            $insertTelinProp = DB::table('transceiver_telin_property')
//                                ->insert(['api_username' => $apiUserName, 'telin_url' => $urlTelin, 'is_active' => true]);
//
//                            if ($insertTelinProp) {
                                DB::table('users')
                                    ->insert(['name' => $fullname, 'email' => $email, 'password' => $encPassword, 'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'), 'privilege' => $privilege, 'client_id' => $clientId, 'group_id' => $clientGroupID,
                                        'is_active' => true, 'is_client_reseller' => $isReseller]);

                                DB::table('user_api')
                                    ->insert(['username' => $apiUserName, 'password' => $encPassword, 'client_id' => $clientId,
                                        'access_type' => 'AUTOGENSMS', 'registered_ip_address' => '127.0.0.1, localhost, '.$request->ip(), 'is_active' => true]);

                                $trxStatus = 0;
//                            } else {
//                                $trxStatus = -5; // Failed to insert into table telin property
//                            }
//                        } else {
//                            // Hit ke telin gagal
//                            if(array_key_exists("Message", $theArray)){
//                                $theTelinMessage = $theArray["Message"];
//
//                                if(trim($theTelinMessage) === "Password too weak"){
//                                    $trxStatus = -4; // Password too weak
//                                } else {
//                                    $trxStatus = -3; // Other telin error
//                                }
//                            } else {
//                                $trxStatus = -5;
//                            }
//                        }
//                    }
//                } catch (Exception $e) {
//                    $trxStatus = -7; // Failed hit telin
//                }
            } else {
                DB::table('users')
                    ->insert(['name' => $fullname, 'email' => $email, 'password' => $encPassword, 'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'), 'privilege' => $privilege, 'client_id' => $clientId, 'group_id' => $clientGroupID,
                        'is_active' => true, 'is_client_reseller' => $isReseller]);

                $trxStatus = 0;
            }
        } catch (Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doSaveEditWebUser(Request $request) {
        $userId = $request->webuserid;
        $userName = $request->webusername;
        $userEmail = $request->webuseremail;
        $userPassword = $request->webuserpassword;
        $userPrivilege = $request->webuserprivilege;
        $userClient = $request->webuserclient;
        if (Auth::user()->privilege === 'ROOT') {
            $userGroupId = $request->webuserclientgroupid;
        } else {
            $userGroupId = Auth::user()->group_id;
        }

        try {
            if (Auth::user()->privilege === 'ROOT') {
                if (isset($userPassword) == null || trim($userPassword) === '') {
                    DB::table('users')
                        ->where('id', '=', $userId)
                        ->update(['name' => $userName, 'email' => $userEmail, 'updated_at' => date('Y-m-d H:i:s'),
                            'privilege' => $userPrivilege, 'client_id' => $userClient, 'group_id' => $userGroupId]);
                } else {
                    DB::table('users')
                        ->where('id', '=', $userId)
                        ->update(['name' => $userName, 'email' => $userEmail, 'password' => $userPassword, 'updated_at' => date('Y-m-d H:i:s'),
                            'privilge' => $userPrivilege, 'client_id' => $userClient, 'group_id' => $userGroupId]);
                }
            } else {
                if (isset($userPassword) == null || trim($userPassword) === '') {
                    DB::table('users')
                        ->where('id', '=', $userId)
                        ->update(['name' => $userName, 'email' => $userEmail, 'updated_at' => date('Y-m-d H:i:s'),
                            'privilege' => $userPrivilege, 'client_id' => $userClient, 'group_id' => $userGroupId]);
                } else {
                    DB::table('users')
                        ->where('id', '=', $userId)
                        ->update(['name' => $userName, 'email' => $userEmail, 'password' => $userPassword, 'updated_at' => date('Y-m-d H:i:s'),
                            'privilege' => $userPrivilege, 'client_id' => $userClient, 'group_id' => $userGroupId]);
                }
            }

            $trxStatus = 0;
        } catch (Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

    function doDeleteWebUser(Request $request) {
        $webUserId = $request->webuserid;

        $trxStatus = 0;
        try {
            DB::table('users')
                ->where('id', $webUserId)
                ->delete();

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

}

