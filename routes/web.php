<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
//Route::get('/', function () {
//    return view('dashboard');
//})->middleware('auth');

// Report
Route::get('/', 'App\Http\Controllers\TransactionReportController@index')->middleware('authsysboss');
Route::get('/report', 'App\Http\Controllers\TransactionReportController@index')->middleware('authsysboss');
Route::get('/vendor', 'App\Http\Controllers\ReportVendorController@index')->middleware('authsysboss');
Route::post('/tblTransaction', 'App\Http\Controllers\TransactionReportController@getDataTable')->middleware('authsysboss');
Route::get('/csvreport', 'App\Http\Controllers\TransactionReportController@csvReport')->middleware('authsysboss');
Route::post('/tblCSVReport', 'App\Http\Controllers\TransactionReportController@getCSVDataTable')->middleware('authsysboss');
Route::post('/tblCSVReportVendor', 'App\Http\Controllers\ReportVendorController@getCSVDataTableVendor')->middleware('authsysboss');
Route::post('/doexportreporttransaction', 'App\Http\Controllers\TransactionReportController@doExportTransaction')->middleware('authsysboss');

Route::get('/summaryTrx', 'App\Http\Controllers\TransactionReportController@summaryTransaction')->middleware('authsysboss');
Route::post('/tblSummaryTrx', 'App\Http\Controllers\TransactionReportController@getSummaryFinancialDataTable')->middleware('authsysboss');
Route::post('/getdetailtransaction', 'App\Http\Controllers\TransactionReportController@getDetailTransaction')->middleware('authsysboss');
Route::post('/doexportreportsummarytransaction', 'App\Http\Controllers\TransactionReportController@doExportSummaryTransaction')->middleware('authsysboss');

// Interactive - Setup Team
Route::get('/ticketsetupteam', function() {
    return view('ticketsetupteam');
})->middleware('auth');

// Client Group Management
Route::get('/clientgroup', function() {
    return view('clientgroup');
})->middleware('auth', 'authsupersysadmin');

// Client Management
Route::get('/client', function() {
    return view('client');
})->middleware('auth');

Route::get('/login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::post('/dologin', 'App\Http\Controllers\AuthController@dologin');
Route::get('logout', 'App\Http\Controllers\AuthController@logout');

// Register Client
Route::get('/register_client', function() {
    return view('signup');
});


// Get datatable data for new client group
Route::get('/tblClientGroup', 'App\Http\Controllers\ClientGroupController@getDataTable')->middleware('auth', 'authsupersysadmin');
// Save new group data
Route::post('/dosavenewprovider','App\Http\Controllers\ClientGroupController@doSaveNewClientGroup')->middleware('auth', 'authsupersysadmin');
// Save edit group data
Route::post('/dosaveeditprovider', 'App\Http\Controllers\ClientGroupController@doSaveEditClientGroup')->middleware('auth', 'authsupersysadmin');
// Delete group data
Route::post('/dodeleteprovider', 'App\Http\Controllers\ClientGroupController@doDeleteClientGroup')->middleware('auth', 'authsupersysadmin');

// Client Management
Route::get('/clients', 'App\Http\Controllers\ClientsController@index')->middleware('auth', 'authsysboss');
// Get datatable for clients
Route::get('/tblClient', 'App\Http\Controllers\ClientsController@getDataTable')->middleware('auth', 'authsysboss');
// Save new client in Client Management
Route::post('/dosavenewclient', 'App\Http\Controllers\ClientsController@doSaveNewClient')->middleware('auth', 'authsysboss');
// Save edit client in Client Management
Route::post('/dosaveeditclient', 'App\Http\Controllers\ClientsController@doSaveEditClient')->middleware('auth', 'authsysboss');
// Delete client
Route::post('/dodeleteclient', 'App\Http\Controllers\ClientsController@doDeleteClient')->middleware('auth', 'authsysboss');

// User Management
Route::get('/webusers', 'App\Http\Controllers\WebUserController@index')->middleware('auth', 'authsysboss');
// Get datatables for webUser
Route::get('/tblWebUser', 'App\Http\Controllers\WebUserController@getDataTable')->middleware('auth', 'authsysboss');
// Save new web user in client management
Route::post('/dosavenewwebuser', 'App\Http\Controllers\WebUserController@doSaveNewWebUser')->middleware('auth', 'authsysboss');
// Edit new web user in client management
Route::post('/dosaveeditwebuser', 'App\Http\Controllers\WebUserController@doSaveEditWebUser')->middleware('auth', 'authsysboss');
// Delete web user int client management
Route::post('/dodeletewebuser', 'App\Http\Controllers\WebUserController@doDeleteWebUser')->middleware('auth', 'authsysboss');

// API Credential Management
Route::get('/apicred', 'App\Http\Controllers\APICredentialController@index')->middleware('auth', 'authsysboss');
Route::get('/tblAPICredential', 'App\Http\Controllers\APICredentialController@getDataTable')->middleware('auth', 'authsysboss');
Route::post('/dosavenewapicredential', 'App\Http\Controllers\APICredentialController@doSaveNewAPICredential')->middleware('auth', 'authsysboss');
Route::post('/dosaveeditapicredential', 'App\Http\Controllers\APICredentialController@doSaveEditAPICredential')->middleware('auth', 'authsysboss');

// Currency Management
Route::get('/currencymanagement', 'App\Http\Controllers\CurrencyController@index')->middleware('auth', 'authsysboss');
// Get datatables for currency
Route::get('/tblCurrency', 'App\Http\Controllers\CurrencyController@getDataTable')->middleware('auth', 'authsysboss');
// Save new currency
Route::post('/dosavenewcurrency', 'App\Http\Controllers\CurrencyController@doSaveNewCurrency')->middleware('auth', 'authsysboss');
// Edit currency
Route::post('/dosaveeditcurrency', 'App\Http\Controllers\CurrencyController@doSaveEditCurrency')->middleware('auth', 'authsysboss');
// Delete currency
Route::post('/dodeletecurrency', 'App\Http\Controllers\CurrencyController@doDeleteCurrency')->middleware('auth', 'authsysboss');

// Client Sender ID
Route::get('/clientsenderid', 'App\Http\Controllers\ClientSenderIdController@index')->middleware('auth', 'authsysboss');
Route::get('/tblClientSenderId', 'App\Http\Controllers\ClientSenderIdController@getDataTable')->middleware('auth', 'authsysboss');
Route::post('/dosavenewclientsenderid', 'App\Http\Controllers\ClientSenderIdController@doSaveNewClientSenderId')->middleware('auth', 'authsysboss');
Route::post('/dosaveeditclientsenderid', 'App\Http\Controllers\ClientSenderIdController@doSaveEditClientSenderId')->middleware('auth', 'authsysboss');
Route::post('/dodeleteclientsenderid', 'App\Http\Controllers\ClientSenderIdController@doDeleteClientSenderId')->middleware('auth', 'authsysboss');

// Vendor Management
Route::get('/vendors', 'App\Http\Controllers\VendorController@index')->middleware('auth', 'authsysboss');
Route::get('/tblVendor', 'App\Http\Controllers\VendorController@getDataTable')->middleware('auth', 'authsysboss');
Route::post('/dosavenewvendor', 'App\Http\Controllers\VendorController@doSaveNewVendor')->middleware('auth', 'authsysboss');
Route::post('/dosaveeditvendor', 'App\Http\Controllers\VendorController@doSaveEditVendor')->middleware('auth', 'authsysboss');
Route::post('/dodeletevendor', 'App\Http\Controllers\VendorController@doDeleteVendor')->middleware('auth', 'authsysboss');

// Vendor Sender ID
Route::get('/vendorsenderid', 'App\Http\Controllers\VendorSenderIdController@index')->middleware('auth', 'authsysboss');
Route::get('/tblVendorSenderId', 'App\Http\Controllers\VendorSenderIdController@getDataTable')->middleware('auth', 'authsysboss');
Route::post('/dosavenewvendorsenderid', 'App\Http\Controllers\VendorSenderIdController@doSaveNewVendorSenderId')->middleware('auth', 'authsysboss');
Route::post('/dosaveeditvendorsenderid', 'App\Http\Controllers\VendorSenderIdController@doSaveEditVendorSenderId')->middleware('auth', 'authsysboss');
Route::post('/dodeletevendorsenderid', 'App\Http\Controllers\VendorSenderIdController@doDeleteVendorSenderId')->middleware('auth', 'authsysboss');

// Routing Management
Route::get('/messagingrouting', 'App\Http\Controllers\RoutingController@index')->middleware('auth', 'authsysboss');
Route::post('/tblRouting', 'App\Http\Controllers\RoutingController@getDataTable')->middleware('auth', 'authsysboss');
Route::get('/dotoggle/{id}/{state}', 'App\Http\Controllers\RoutingController@doToggleRoutingTable')->middleware('auth', 'authsysboss');

// NeuAPIX Management
// NeuAPIx Management
Route::get('/neuapixmanagement', 'App\Http\Controllers\NeuAPIXController@index')->middleware('auth', 'authsysboss');
// Get datatable for NeuAPIx
Route::get('/tblneuapix', 'App\Http\Controllers\NeuAPIXController@getDataTable')->middleware('auth', 'authsysboss');
// Save new NeuAPIx in NeuAPIx Management
Route::post('/dosavenewneuapix', 'App\Http\Controllers\NeuAPIXController@doSaveNewNeuAPIx')->middleware('auth', 'authsysboss');
// Save edit NeuAPIx in NeuAPIx Management
Route::post('/dosaveeditneuapix', 'App\Http\Controllers\NeuAPIXController@doSaveEditNeuAPIx')->middleware('auth', 'authsysboss');
// Delete NeuAPIx
Route::post('/dodeleteneuapix', 'App\Http\Controllers\NeuAPIXController@doDeleteNeuAPIx')->middleware('auth', 'authsysboss');

// Balance Management
Route::get('/balance', 'App\Http\Controllers\BalanceController@index')->middleware('authsysboss');
Route::get('/tblBalance', 'App\Http\Controllers\BalanceController@getDataTable')->middleware('authsysboss');
Route::post('/getclientbalance', 'App\Http\Controllers\BalanceController@getClientBalance')->middleware('authsysboss');
Route::post('/dotopup', 'App\Http\Controllers\BalanceController@topupClientBalance')->middleware('authsysboss');

// Blacklist Keyword
Route::get('/blacklist', 'App\Http\Controllers\BlacklistController@index')->middleware('authsysboss');
Route::get('/tblKeyword', 'App\Http\Controllers\BlacklistController@getDataTable')->middleware('authsysboss');
Route::post('/doSaveNewKeyword', 'App\Http\Controllers\BlacklistController@doSaveNewKeyword')->middleware('authsysboss');
Route::post('/doSaveEditKeyword', 'App\Http\Controllers\BlacklistController@doSaveEditKeyword')->middleware('authsysboss');
Route::post('/doDeleteKeyword', 'App\Http\Controllers\BlacklistController@doDeleteKeyword')->middleware('authsysboss');

// Activity Keyword
Route::get('/activity', 'App\Http\Controllers\ActivityController@index')->middleware('authsysboss');
Route::get('/tblActivity', 'App\Http\Controllers\ActivityController@getDataTable')->middleware('authsysboss');
// Route::post('/doSaveNewKeyword', 'App\Http\Controllers\ActivityController@doSaveNewKeyword')->middleware('authsysboss');
// Route::post('/doSaveEditKeyword', 'App\Http\Controllers\ActivityController@doSaveEditKeyword')->middleware('authsysboss');
// Route::post('/doDeleteKeyword', 'App\Http\Controllers\ActivityController@doDeleteKeyword')->middleware('authsysboss');

// Whatsapp
Route::get('/whatsapp', 'App\Http\Controllers\WhatsappController@index')->middleware('authsysboss');
Route::get('/tblWhatsapp', 'App\Http\Controllers\WhatsappController@getDataTable')->middleware('authsysboss');


// Get Client List By Group ID - OTool
Route::post('/getclientlistbygroup', 'App\Http\Controllers\ClientsController@getClientListByGroupId')->middleware('auth', 'authsysboss');
// Get Client Sender ID by ClientId - OTool
Route::post('/getclientsenderidbyclientid', 'App\Http\Controllers\ToolController@getClientSenderIdByClientId')->middleware('auth', 'authsysboss');
// Get API UserName by ClientId - OTool
Route::post('/getapiusernamebyclientid', 'App\Http\Controllers\ToolController@getAPIUserNameByClientId')->middleware('auth', 'authsysboss');
// Get Client property by ClientId - OTool
Route::post('/getclientpropbyclientid', 'App\Http\Controllers\ToolController@getClientPropertyByClientId')->middleware('auth', 'authsysboss');
// Get Vendor Sender ID by vendorId - OTool
Route::post('/getvendorsenderidbyvendorid', 'App\Http\Controllers\ToolController@getVendorSenderIdByVendorId')->middleware('auth', 'authsysboss');
