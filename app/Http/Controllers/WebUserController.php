<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Http\Helpers\Auditrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WebUserController extends Controller
{
    function index() {
        if (Auth::user()->privilege === 'ROOT') {
            // Load select - group
            $data = DB::table('client_group')
                ->select('group_id', 'group_name')
                ->orderBy('group_name')
                ->get();

            return view('webuser')->with('clientGroupData', $data);
        } else if ((Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP')) {
            // Load sysadmin client list as logged-in
            $dataClient = DB::table('client')
                ->select('client_id', 'client_name')
                ->where('client_group_id', '=', Auth::user()->group_id)
                ->where('is_active', '=', true)
                ->orderBy('client_name')
                ->get();

            return view('webuser')->with('clientDataForAdmin', $dataClient);
        }
    }

    function getDataTable(Request $request) {
        $loginUserPrivilege = Auth::user()->privilege;
        $loginUserGroupId = Auth::user()->group_id;

        $searchField = $request->input("searchcategory");
        $searchKeyword = $request->input("searchkeyword");

        if ($request->ajax()) {
            if ($loginUserPrivilege === 'ROOT') {
                if (strlen(trim($searchKeyword)) == 0) {
                    // Not Searching
                    $dataUser = DB::table('users')
                        ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                        ->leftJoin('client_group', 'users.group_id', '=', 'client_group.group_id')
                        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.privilege', 'users.client_id', 'client.client_name', 'users.group_id', 'client_group.group_name')
                        ->orderBy('users.name')
                        ->get();
                } else {
                    // Searching
                    $theField = 'users.name';
                    $comparisan = 'ilike';
                    $incomingData = '%'.$searchKeyword.'%';
                    if ($searchField === 'fullName') {
                        $theField = 'users.name';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'email') {
                        $theField = 'users.email';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'client') {
                        $theField = 'client.client_name';
                        $comparisan = '=';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'privilege') {
                        $theField = 'users.privilege';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    }

                    $dataUser = DB::table('users')
                        ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                        ->leftJoin('client_group', 'users.group_id', '=', 'client_group.group_id')
                        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.privilege', 'users.client_id', 'client.client_name', 'users.group_id', 'client_group.group_name')
                        ->where($theField, $comparisan, $incomingData)
                        ->orderBy('users.name')
                        ->get();
                }
            } else {
                if (strlen(trim($searchKeyword)) == 0) {
                    $dataUser = DB::table('users')
                        ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.privilege', 'users.client_id', 'client.client_name', 'users.group_id')
                        ->where('users.privilege', '!=', 'ROOT')
                        ->where('users.group_id', '=', $loginUserGroupId)
                        ->orderBy('users.name')
                        ->get();
                } else {
                    // Searching
                    $theField = 'users.name';
                    $comparisan = 'ilike';
                    $incomingData = '%'.$searchKeyword.'%';
                    if ($searchField === 'fullName') {
                        $theField = 'users.name';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'email') {
                        $theField = 'users.email';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'client') {
                        $theField = 'client.client_name';
                        $comparisan = '=';
                        $incomingData = '%'.$searchKeyword.'%';
                    } else if ($searchField === 'privilege') {
                        $theField = 'users.privilege';
                        $comparisan = 'ilike';
                        $incomingData = '%'.$searchKeyword.'%';
                    }

                    $dataUser = DB::table('users')
                        ->leftJoin('client', 'users.client_id', '=', 'client.client_id')
                        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.privilege', 'users.client_id', 'client.client_name', 'users.group_id')
                        ->where('users.privilege', '!=', 'ROOT')
                        ->where('users.group_id', '=', $loginUserGroupId)
                        ->where($theField, $comparisan, $incomingData)
                        ->orderBy('users.name')
                        ->get();
                }
            }

            return DataTables::of($dataUser)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if (Auth::user()->privilege === 'ROOT') {
                        return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditWebUser" data-webUserId="'.$row->id.'" data-webUserFullName="'.
                            $row->name.'" data-webUserEmail="'.$row->email.'" data-webUserPrivilege="'.$row->privilege.
                            '" data-webUserClient="'.$row->client_id.'" data-webUserGroupId="'.$row->group_id.
                            '">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelWebUser" data-webUserId="'.
                            $row->id.'" data-webUserFullName="'.$row->name.'" data-webUserEmail="'.$row->email.'">Delete</a>';
                    } else {
                        return '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditWebUser" data-webUserId="'.$row->id.'" data-webUserFullName="'.
                            $row->name.'" data-webUserEmail="'.$row->email.'" data-webUserPrivilege="'.$row->privilege.
                            '" data-webUserClient="'.$row->client_id.'">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelWebUser" data-webUserId="'.
                            $row->id.'" data-webUserFullName="'.$row->name.'" data-webUserEmail="'.$row->email.'">Delete</a>';
                    }
                })
                ->rawColumns(['action'])
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

                                    Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "USER", "ADD : ".$clientId." -- ".$fullname." -- ".$email." (".$encPassword.")"." -- ".$privilege." -- ".$clientGroupID." || SUCCESS", $request->getHttpHost());
                                    Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "USERAPI", "ADD API : ".$clientId." -- ".$apiUserName." (".$encPassword.")"." || SUCCESS", $request->getHttpHost());

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

                    Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "USER", "ADD : ".$clientId." -- ".$fullname." -- ".$email." (".$encPassword.")"." -- ".$privilege." -- ".$clientGroupID." || SUCCESS", $request->getHttpHost());

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

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "USER", "UPDATE : ".$userClient." TO : ".$userName." -- ".$userEmail." (".$userPassword.")"." -- ".$userPrivilege." -- ".$userGroupID." || SUCCESS", $request->getHttpHost());

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

            Auditrail::save_auditrail(Auth::user()->email, $request->ip(), "USER", "DELETE : ".$webUserId." || SUCCESS", $request->getHttpHost());

            $trxStatus = 0;
        } catch (\Exception $e) {
            //$trxStatus = $e;
            $trxStatus = -1;
        }

        return $trxStatus;
    }

}

