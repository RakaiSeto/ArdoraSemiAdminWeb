@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
{{--            <!-- Content Header (Page header) -->--}}
{{--            <div class="content-header">--}}
{{--                <h3>--}}
{{--                    API Credential--}}
{{--                    <small>Management</small>--}}
{{--                </h3>--}}
{{--                <ol class="breadcrumb">--}}
{{--                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Client Management</a></li>--}}
{{--                    <li class="breadcrumb-item active">API Management </li>--}}
{{--                </ol>--}}
{{--            </div>--}}

            <!-- Modal new API Credential -->
            <div class="modal center-modal fade" id="modalNewAPICredential" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New API Credential</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newAPIUserName" class="col-sm-4 col-form-label">API Username</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newAPIUserName">
                                            <span style="color: red; font-size: small">Without characters: [\'^£$%&*()}{@#~?><>,|=_+¬-]/ and white space.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newAPIPassword" class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" id="newAPIPassword">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newAPIProductAccess" class="col-sm-4 col-form-label">API Product Access</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newAPIProductAccess">
                                                @if(isset($apiProduct))
                                                    <?php $count = 0; ?>
                                                    @foreach($apiProduct as $product)
                                                        @if($count == 0)
                                                            <option value="{{trim($product->api_id)}}" selected>{{ trim($product->api_name) }}</option>
                                                        @else
                                                            <option value="{{trim($product->api_id)}}">{{ trim($product->api_name) }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="newAPIClientId" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newAPIClientId">
                                                @if(isset($client))
                                                    <?php $count = 0; ?>
                                                    @foreach($client as $clientData)
                                                        @if($count == 0)
                                                            <option value="{{trim($clientData->client_id)}}" selected>{{ trim($clientData->client_name) }}</option>
                                                        @else
                                                            <option value="{{trim($clientData->client_id)}}">{{ trim($clientData->client_name) }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="newAPIRemoteIPAddress" class="col-sm-4 col-form-label">Remote IP Address</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newAPIRemoteIPAddress">
                                            <span style="color: red; font-size: small">Separate by comma (,) for multi IP Addresses.</span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewAPICredential">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit API Credential -->
            <div class="modal center-modal fade" id="modalEditAPICredential" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit API Credential</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editOrigAPIUserName">
                                    <div class="form-group row">
                                        <label for="editAPIUserName" class="col-sm-4 col-form-label">API Username</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editAPIUserName">
                                            <span style="color: red; font-size: small">Without characters: [\'^£$%&*()}{@#~?><>,|=_+¬-]/ and white space.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editAPIPassword" class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" id="editAPIPassword">
                                            <span style="color: red; font-size: small">Leave it empty if you do not want to change the password.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editAPIProductAccess" class="col-sm-4 col-form-label">API Product Access</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editAPIProductAccess">
                                                @if(isset($apiProduct))
                                                    <?php $count = 0; ?>
                                                    @foreach($apiProduct as $product)
                                                        @if($count == 0)
                                                            <option value="{{trim($product->api_id)}}" selected>{{ trim($product->api_name) }}</option>
                                                        @else
                                                            <option value="{{trim($product->api_id)}}">{{ trim($product->api_name) }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="editAPIClientId" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editAPIClientId">
                                                @if(isset($client))
                                                    <?php $count = 0; ?>
                                                    @foreach($client as $clientData)
                                                        @if($count == 0)
                                                            <option value="{{trim($clientData->client_id)}}">{{ trim($clientData->client_name) }}</option>
                                                        @else
                                                            <option value="{{trim($clientData->client_id)}}">{{ trim($clientData->client_name) }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="editAPIRemoteIPAddress" class="col-sm-4 col-form-label">Remote IP Address</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editAPIRemoteIPAddress">
                                            <span style="color: red; font-size: small">Separate by comma (,) for multi IP Addresses.</span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditAPICredential">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete provide group -->
            <div class="modal center-modal fade" id="modalDelWebUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Web User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delWebUserId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteWebUserClient">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal center-modal fade" id="modalFind" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Find API User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="findField" class="col-sm-4 col-form-label">Find API User</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="findField">
                                                <option value="username" selected>Username</option>
                                                <option value="client">Client</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="findKeyword" class="col-sm-4 col-form-label">Find Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="findKeyword">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnFind" data-dismiss="modal">Find</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h3 class="box-title">API Credential</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewAPICredential" data-toggle="modal" data-target="#modalNewAPICredential">New API Credential</button>
                                    <button type="button" class="btn btn-info btn-sm" id="btnFind" data-toggle="modal" data-target="#modalFind">Find API Credential</button>                                </div>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableAPICredential" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Username</th>
                                                <th style="text-align: center">Client</th>
                                                <th style="text-align: center">API Product</th>
                                                <th style="text-align: center">Access Type</th>
                                                <th style="text-align: center">Registered API Address</th>
                                                <th style="text-align: center">Access Token</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Active</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Username</th>
                                                <th style="text-align: center">Client</th>
                                                <th style="text-align: center">API Product</th>
                                                <th style="text-align: center">Access Type</th>
                                                <th style="text-align: center">Registered API Address</th>
                                                <th style="text-align: center">Access Token</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Active</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
@endsection

@section('jscript')
    <script>
        $(document).ready( function() {
            let tableAPICredential = $('#tableAPICredential').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblAPICredential",
                    columns: [
                        { data: 'username', name: 'Username'},
                        { data: 'client_name', name: 'Client Name'},
                        { data: 'api_name', name: 'API Product'},
                        { data: 'access_type', name: 'Access Type'},
                        { data: 'registered_ip_address', name: 'Registered IP Address'},
                        { data: 'token', name: 'Access Token'},
                        @if(Auth::user()->privilege === 'ROOT')
                            { data: 'group_name', name: 'Group'},
                        @endif
                        { data: 'is_active', name: 'Active'},
                        { data: 'action', name: 'Action'}
                    ],
                     columnDefs: [
                        { "targets": "_all", "className": "text-center"}
                    ]
                }
            )

            $('#btnFind').on('click', function() {
                tableAPICredential.ajax.url("/tblAPICredential?searchcategory=" + $('#findField').val() + "&searchkeyword=" + $('#findKeyword').val()).load()
            })

            $('#modalFind').on('show.bs.modal', function() {
                $('#findClientField').prop("selectedIndex", 0)
                $('#findClientKeyword').val('')
            })

            $('#modalNewAPICredential').on('show.bs.modal', function(e) {
                $('#newAPIUserName').val('')
                $('#newAPIPassword').val('')
                $('#newAPIRemoteIPAddress').val('')
            })

            $('#btnSaveNewAPICredential').on('click', function (e) {
                e.preventDefault()

                let btnSaveNewAPICredential = $('#btnSaveNewAPICredential')
                btnSaveNewAPICredential.attr('disabled', true)
                btnSaveNewAPICredential.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewAPICredential = $('#modalNewAPICredential')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('username', $('#newAPIUserName').val())
                formData.append('password', $('#newAPIPassword').val())
                formData.append('apiproductid', $('#newAPIProductAccess').val())
                formData.append('clientid', $('#newAPIClientId').val())
                formData.append('registeredapiaddress', $('#newAPIRemoteIPAddress').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewapicredential',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX !== '-1' && dataX !== '-2' && dataX.length > 2) {
                            if (dataX.length === 32) {
                                alert('Data API Credential is saved. The API Token: ' + dataX)
                            } else {
                                alert('Data API Credential is saved.')
                            }
                        } else if (dataX === '-2') {
                            alert('Failed to save data API Credential. Username can not contain special characters and white space.')
                        } else {
                            if (dataX.indexOf('duplicate key value violates unique constraint') > -1) {
                                alert('Failed to save data API Credential. The Username is already taken.')
                            } else {
                                alert('Failed to save data API Credential.')
                            }
                        }

                        btnSaveNewAPICredential.attr('disabled', false)
                        modalNewAPICredential.modal('toggle')
                        btnSaveNewAPICredential.html('Save')

                        tableAPICredential.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewAPICredential.attr('disabled', false)
                        modalNewAPICredential.modal('toggle')
                        btnSaveNewAPICredential.html('Save')

                        alert('Failed to save data API Credential.')
                    },
                    error: function(){
                        btnSaveNewAPICredential.attr('disabled', false)
                        modalNewAPICredential.modal('toggle')
                        btnSaveNewAPICredential.html('Save')

                        alert('Failed to save data API Credential.')
                    }
                })
            })

            $('#modalEditAPICredential').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let userName = button.attr('data-apiUserName')
                let apiProductId = button.attr('data-apiAccessType')
                let clientId = button.attr('data-apiClientId')
                let remoteIpAddress = button.attr('data-apiRegisteredIPAddress')

                console.log('clientId: ' + clientId)

                $('#editOrigAPIUserName').val(userName)
                $('#editAPIUserName').val(userName)
                $('#editAPIProductAccess').val(apiProductId)
                $('#editAPIClientId').val(clientId)
                $('#editAPIRemoteIPAddress').val(remoteIpAddress)
            })

            $('#btnSaveEditAPICredential').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditAPICredential = $('#btnSaveEditAPICredential')
                btnSaveEditAPICredential.attr('disabled', true)
                btnSaveEditAPICredential.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalEditAPICredential = $('#modalEditAPICredential')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('origusername', $('#editOrigAPIUserName').val())
                formData.append('username', $('#editAPIUserName').val())
                formData.append('password', $('#editAPIPassword').val())
                formData.append('apiproductid', $('#editAPIProductAccess').val())
                formData.append('clientid', $('#editAPIClientId').val())
                formData.append('registeredapiaddress', $('#editAPIRemoteIPAddress').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditapicredential',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                                alert('Data API Credential is saved.')
                        } else {
                            if (dataX.indexOf('duplicate key value violates unique constraint') > -1) {
                                alert('Failed to save data API Credential. The Username is already taken.')
                            } else {
                                alert('Failed to save data API Credential.')
                            }
                        }

                        btnSaveEditAPICredential.attr('disabled', false)
                        modalEditAPICredential.modal('toggle')
                        btnSaveEditAPICredential.html('Save')

                        tableAPICredential.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveEditAPICredential.attr('disabled', false)
                        modalEditAPICredential.modal('toggle')
                        btnSaveEditAPICredential.html('Save')

                        alert('Failed to save data API Credential.')
                    },
                    error: function(){
                        btnSaveEditAPICredential.attr('disabled', false)
                        modalEditAPICredential.modal('toggle')
                        btnSaveEditAPICredential.html('Save')

                        alert('Failed to save data API Credential.')
                    }
                })
            })





            @if(Auth::user()->privilege === 'ROOT')
            $('#newWebUserClientGroupId').on('change', function(e) {
                e.preventDefault()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientgroupid', $('#newWebUserClientGroupId').val())

                $('#newWebUserClient').empty()
                $.ajax({
                    type: 'POST',
                    url: '/getclientlistbygroup',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        for (let x = 0; x < dataX.length; x++) {
                            $('#newWebUserClient')
                                .append('<option value="' + dataX[x]["client_id"] + '">' +
                                    dataX[x]["client_name"] + '</option>>')
                        }
                    }
                })
            })

            $('#newWebUserClientGroupId').change()
            @endif

            $('#modalNewWebUser').on('show.bs.modal', function() {
                $('#newWebUserFullName').val('')
                $('#newWebUserEmail').val('')
                $('#newWebUserPassword').val('')

            })

            $('#btnSaveNewWebUser').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewWebUser = $('#btnSaveNewWebUser')
                btnSaveNewWebUser.attr('disabled', true)
                btnSaveNewWebUser.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewWebUser = $('#modalNewWebUser')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('fullname', $('#newWebUserFullName').val())
                formData.append('email', $('#newWebUserEmail').val())
                formData.append('password', $('#newWebUserPassword').val())
                formData.append('privilege', $('#newWebUserPrivilege').val())
                @if(Auth::user()->privilege === 'ROOT')
                formData.append('clientgroup', $('#newWebUserClientGroupId').val())
                @endif
                @if((Auth::user()->privilege === 'ROOT') || (Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP'))
                formData.append('client', $('#newWebUserClient').val())
                @endif

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewwebuser',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data web user is saved.')
                        } else {
                            alert('Failed to save data web user.')
                        }

                        btnSaveNewWebUser.attr('disabled', false)
                        modalNewWebUser.modal('toggle')
                        btnSaveNewWebUser.html('Save')

                        tableWebUser.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewWebUser.attr('disabled', false)
                        modalNewWebUser.modal('toggle')
                        btnSaveNewWebUser.html('Save')

                        alert('Failed to save data client group.')
                    },
                    error: function(){
                        btnSaveNewWebUser.attr('disabled', false)
                        modalNewWebUser.modal('toggle')
                        btnSaveNewWebUser.html('Save')

                        alert('Failed to save data client.')
                    }
                })
            })

            $('#modalEditWebUser').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let userId = button.attr('data-webUserId')
                let fullName = button.attr('data-webUserFullName')
                let email = button.attr('data-webUserEmail')
                let privilege = button.attr('data-webUserPrivilege')
                let client = button.attr('data-webUserClient')
                @if (Auth::user()->privilege === 'ROOT')
                let groupId = button.attr('data-webUserGroupId').trim()
                @endif

                $('#editWebUserId').val(userId)
                $('#editWebUserFullName').val(fullName)
                $('#editWebUserEmail').val(email)
                $('#editWebUserPrivilege').val(privilege)
                $('#editWebUserClient').val(client)

                @if (Auth::user()->privilege === 'ROOT')
                $('#editWebUserClientGroupId').val(groupId.trim())
                @endif

                $('#editWebUserClientGroupId').change()
            })

            $('#btnSaveEditWebUser').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditWebUser = $('#btnSaveEditWebUser')
                btnSaveEditWebUser.attr('disabled', true)
                btnSaveEditWebUser.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditWebUser = $('#modalEditWebUser')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('webuserid', $('#editWebUserId').val())
                formData.append('webusername', $('#editWebUserFullName').val())
                formData.append('webuseremail', $('#editWebUserEmail').val())
                formData.append('webuserpassword', $('#editWebUserPassword').val())
                formData.append('webuserprivilege', $('#editWebUserPrivilege').val())
                formData.append('webuserclient', $('#editWebUserClient').val())
                @if (Auth::user()->privilege === 'ROOT')
                formData.append('webuserclientgroupid', $('#editWebUserClientGroupId').val())
                @endif

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditwebuser',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data webuser is updated.')
                        } else {
                            alert('Failed to update data webuser.')
                        }

                        btnSaveEditWebUser.attr('disabled', false)
                        modalEditWebUser.modal('toggle')
                        btnSaveEditWebUser.html('Save')

                        tableWebUser.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditWebUser.attr('disabled', false)
                        modalEditWebUser.modal('toggle')
                        btnSaveEditWebUser.html('Save')

                        alert('Failed to update data webuser.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditWebUser.attr('disabled', false)
                        modalEditWebUser.modal('toggle')
                        btnSaveEditWebUser.html('Save')

                        alert('Failed to update data webuser.')
                    }
                })
            })

            @if (Auth::user()->privilege === 'ROOT')
            $('#editWebUserClientGroupId').on('change', function (e) {
                e.preventDefault()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientgroupid', $('#editWebUserClientGroupId').val())

                $.ajax({
                    type: 'POST',
                    url: '/getclientlistbygroup',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        $('#editWebUserClient').empty()
                        for (let x = 0; x < dataX.length; x++) {
                            let jsonDetail = dataX[x]

                            if (x == 0) {
                                $('#editWebUserClient').append('<option selected="selected" value="' + jsonDetail["client_id"] + '">' + jsonDetail["client_name"] + '</option>')
                            } else {
                                $('#editWebUserClient').append('<option value="' + jsonDetail["client_id"] + '">' + jsonDetail["client_name"] + '</option>')
                            }
                        }
                    },
                    fail: function(){
                        //console.log('failed.')
                    },
                    error: function(){
                        //console.log('failed.')
                    }
                })
            })
            @endif

            $('#modalDelWebUser').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let webUserId = button.attr('data-webUserId')
                let webUserEmail = button.attr('data-webUserEmail')

                $('#delWebUserId').val(webUserId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete web user <span style="color:red">' + webUserEmail + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteWebUserClient').on('click', function() {
                let btnDeleteWebUser = $('#btnDeleteWebUserClient')
                let modalDeleteWebUser = $('#modalDelWebUser')

                btnDeleteWebUser.attr('disabled', true)
                btnDeleteWebUser.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('webuserid', $('#delWebUserId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeletewebuser',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data web user is deleted.')
                        } else {
                            alert('Failed to delete data web user.')
                        }

                        btnDeleteWebUser.attr('disabled', false)
                        modalDeleteWebUser.modal('toggle')
                        btnDeleteWebUser.html('Delete')

                        tableWebUser.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Web User.')
                        btnDeleteWebUser.attr('disabled', false)
                        modalDeleteWebUser.modal('toggle')
                        btnDeleteWebUser.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Web User.')
                        btnDeleteWebUser.attr('disabled', false)
                        modalDeleteWebUser.modal('toggle')
                        btnDeleteWebUser.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
@endsection
