@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
{{--            <div class="content-header">--}}
{{--                <h3>--}}
{{--                    Web User--}}
{{--                    <small>Management</small>--}}
{{--                </h3>--}}
{{--                <ol class="breadcrumb">--}}
{{--                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Client Management</a></li>--}}
{{--                    <li class="breadcrumb-item active">Web User </li>--}}
{{--                </ol>--}}
{{--            </div>--}}

            <!-- Modal new webuser -->
            <div class="modal center-modal fade" id="modalNewWebUser">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Web User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newWebUserFullName" class="col-sm-4 col-form-label">Full Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newWebUserFullName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newWebUserEmail" class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="email" id="newWebUserEmail">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newWebUserPassword" class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" id="newWebUserPassword">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newWebUserPrivilege" class="col-sm-4 col-form-label">Privilege</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newWebUserPrivilege">
                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'ROOT')
                                                    <option value="ROOT" selected>Root</option>
                                                    <option value="SYSADMIN">Sys Admin</option>
                                                    <option value="SYSFINANCE">Sys Finance</option>
                                                    <option value="SYSOP">Sys Op</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSADMIN')
                                                        <option value="SYSADMIN">Sys Admin</option>
                                                        <option value="SYSFINANCE">Sys Finance</option>
                                                        <option value="SYSOP">Sys Op</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSFINANCE')
                                                    <option value="SYSFINANCE">Sys Finance</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSOP')
                                                    <option value="SYSOP">Sys Op</option>
                                                @endif

                                                <option value="REPORT">Report</option>
                                                <option value="B2B_RESELLER">B2B Reseller</option>
                                                <option value="B2B_USER">B2B User</option>
                                                <option value="PUBLIC_USER_ADM">Public User Adm</option>
                                                <option value="PUBLIC_USER">Public User</option>
                                                <option value="VOICE_REVIEWER">Voice Reviewer</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if(\Auth::user()->privilege === 'ROOT')
                                        <div class="form-group row">
                                            <label for="newWebUserClientGroupId" class="col-sm-4 col-form-label">Client Group</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" id="newWebUserClientGroupId">
                                                    @if(isset($clientGroupData))
                                                        @php($count = 0)
                                                        @foreach($clientGroupData as $groupData)
                                                            @if($count == 0)
                                                                <option value="{{trim($groupData->group_id)}}" selected>{{ $groupData->group_name }}</option>
                                                            @else
                                                                <option value="{{trim($groupData->group_id)}}">{{ $groupData->group_name }}</option>
                                                            @endif
                                                            @php($count = $count + 1)
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="newWebUserClient" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" id="newWebUserClient">
                                                @if((Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP'))
                                                    @if(isset($clientDataForAdmin))
                                                        @foreach($clientDataForAdmin as $clientData)
                                                            <option value="{{ $clientData->client_id }}">{{ $clientData->client_name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="formCanSendNeuAPIXMessage">
                                        <label for="newCanSendNeuAPIXMessage" class="col-sm-4 col-form-label">Allow Sending SMS</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newCanSendNeuAPIXMessage">
                                                <option value="false">NO</option>
                                                <option value="true" selected>YES</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewWebUser">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit webuser -->
            <div class="modal center-modal fade" id="modalEditWebUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Web User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editWebUserId">
                                    <div class="form-group row">
                                        <label for="editWebUserFullName" class="col-sm-4 col-form-label">Full Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editWebUserFullName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editWebUserEmail" class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="email" id="editWebUserEmail">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editWebUserPassword" class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" id="editWebUserPassword">
                                            <span class="text-danger"><small>Leave it empty if you are not changing the password.</small></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editWebUserPrivilege" class="col-sm-4 col-form-label">Privilege</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editWebUserPrivilege">
                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'ROOT')
                                                    <option value="ROOT" selected>Root</option>
                                                    <option value="SYSADMIN">Sys Admin</option>
                                                    <option value="SYSFINANCE">Sys Finance</option>
                                                    <option value="SYSOP">Sys Op</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSADMIN')
                                                    <option value="SYSADMIN">Sys Admin</option>
                                                    <option value="SYSFINANCE">Sys Finance</option>
                                                    <option value="SYSOP">Sys Op</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSFINANCE')
                                                    <option value="SYSFINANCE">Sys Finance</option>
                                                @endif

                                                @if(\Illuminate\Support\Facades\Auth::user()->privilege === 'SYSOP')
                                                    <option value="SYSOP">Sys Op</option>
                                                @endif

                                                <option value="REPORT">Report</option>
                                                <option value="B2B_RESELLER">B2B Reseller</option>
                                                <option value="B2B_USER">B2B User</option>
                                                <option value="PUBLIC_USER_ADM">Public User Adm</option>
                                                <option value="PUBLIC USER">Public User</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if(\Auth::user()->privilege === 'ROOT')
                                        <div class="form-group row">
                                            <label for="editWebUserClientGroupId" class="col-sm-4 col-form-label">Client Group</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2 w-p100" id="editWebUserClientGroupId">
                                                    @if(isset($clientGroupData))
                                                        @php($count = 0)
                                                        @foreach($clientGroupData as $groupData)
                                                            @if($count == 0)
                                                                <option value="{{ trim($groupData->group_id) }}" selected>{{ trim($groupData->group_name) }}</option>
                                                            @else
                                                                <option value="{{ trim($groupData->group_id) }}">{{ trim($groupData->group_name) }}</option>
                                                            @endif
                                                            @php($count = $count + 1)
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="editWebUserClient" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editWebUserClient">
                                                @if((Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP'))
                                                    @if(isset($clientDataForAdmin))
                                                        @foreach($clientDataForAdmin as $clientData)
                                                            <option value="{{ $clientData->client_id }}">{{ $clientData->client_name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditWebUser">Save</button>
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

            <div class="modal center-modal fade" id="modalFindWebUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Find User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="findField" class="col-sm-4 col-form-label">Find Field</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="findField">
                                                <option value="fullName" selected>Full Name</option>
                                                <option value="email">Email</option>
                                                <option value="client">Client</option>
                                                <option value="privilege">Privilege</option>
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
                                <h3 class="box-title">Web User</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewClient" data-toggle="modal" data-target="#modalNewWebUser">New Web User</button>
                                    <button type="button" class="btn btn-info btn-sm" id="btnFind" data-toggle="modal" data-target="#modalFindWebUser">Find User</button>                                </div>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableWebUser" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Full Name</th>
                                                <th style="text-align: center">Email (username)</th>
                                                <th style="text-align: center">Created At</th>
                                                <th style="text-align: center">Privilege</th>
                                                <th style="text-align: center">Client</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Full Name</th>
                                                <th style="text-align: center">Email (username)</th>
                                                <th style="text-align: center">Created At</th>
                                                <th style="text-align: center">Privilege</th>
                                                <th style="text-align: center">Client</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
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
            let tableWebUser = $('#tableWebUser').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblWebUser",
                    columns: [
                        { data: 'name', name: 'users.name', searchable: true},
                        { data: 'email', name: 'Email (username)'},
                        { data: 'created_at', name: 'Created At'},
                        { data: 'privilege', name: 'Privilege'},
                        { data: 'client_name', name: 'Client'},
                        @if(Auth::user()->privilege === 'ROOT')
                        { data: 'group_name', name: 'Group'},
                        @endif
                        { data: 'action', name: 'Action'}
                    ],
                    columnDefs: [
                        { "targets": [0, 3, 4], "className": "text-center"}
                    ]
                }
            )

            $('#btnFind').on('click', function() {
                tableWebUser.ajax.url("/tblWebUser?&searchcategory=" + $('#findField').val() + "&searchkeyword=" + $('#findKeyword').val()).load()
            })

            $('#modalFindWebUser').on('show.bs.modal', function () {
                $('#findField').prop("selectedIndex", 0)
                $('#findKeyword').val('')
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
                formData.append('canneuapix', $('#newCanSendNeuAPIXMessage').val())

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
                $('#editWebUserClient').val(client).trigger('change')

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

            $('#newWebUserPrivilege').on('change', function() {
                let priv = $('#newWebUserPrivilege').val()

                if(priv === 'B2B_RESELLER' || priv === 'B2B_USER' || priv === 'PUBLIC_USER_ADM' || priv === 'PUBLIC_USER') {
                    $('#formCanSendNeuAPIXMessage').show()
                } else {
                    $('#formCanSendNeuAPIXMessage').hide()
                }
            })

            $('#newWebUserPrivilege').change()
        })

        $("#newWebUserClient").select2({
            dropdownParent: $("#modalNewWebUser"),
            width: '100%',
            theme: 'classic'
        })

        $("#editWebUserClient").select2({
            dropdownParent: $("#modalEditWebUser"),
            width: '100%',
            theme: 'classic'
        })
    </script>
@endsection

