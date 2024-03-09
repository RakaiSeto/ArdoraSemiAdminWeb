@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
           <div class="content-header">
               <h3>
                   Blacklist Keyword
                   <small>Management</small>
               </h3>
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Blacklist Keyword</a></li>
                   <li class="breadcrumb-item active">Blacklist </li>
               </ol>
           </div>

            <!-- Modal new keyword -->
            <div class="modal center-modal fade" id="modalNewKeyword">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newKeyword" class="col-sm-4 col-form-label">Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newKeyword">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewKeyword">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit webuser -->
            <div class="modal center-modal fade" id="modalEditKeyword" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editKeyword">
                                    <div class="form-group row">
                                        <label for="editKeyword" class="col-sm-4 col-form-label">Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editNewKeyword">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditKeyword">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete keyword -->
            <div class="modal center-modal fade" id="modalDeleteKeyword" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="deleteKeyword">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteKeyword">Delete</button>
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
                                {{-- <h3 class="box-title">Web User</h3> --}}
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewKeyword" data-toggle="modal" data-target="#modalNewKeyword">New Keyword</button>
                                    {{-- <button type="button" class="btn btn-info btn-sm" id="btnFind" data-toggle="modal" data-target="#modalFindWebUser">Find User</button>                                </div> --}}
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableKeyword" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Blacklist Keyword</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Blacklist Keyword</th>
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
            let tableKeyword = $('#tableKeyword').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblKeyword",
                    columns: [
                        { data: 'keyword', name: 'Blacklist Keyword'},
                        { data: 'action', name: 'Action'}
                    ],
                    columnDefs: [
                        { "targets": [0, 1], "className": "text-center"}
                    ]
                }
            )

            $('#btnFind').on('click', function() {
                tableKeyword.ajax.url("/tblWebUser?&searchcategory=" + $('#findField').val() + "&searchkeyword=" + $('#findKeyword').val()).load()
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

            $('#modalNewKeyword').on('show.bs.modal', function() {
                $('#newKeyword').val('')
            })

            $('#btnSaveNewKeyword').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewKeyword = $('#btnSaveNewKeyword')
                btnSaveNewKeyword.attr('disabled', true)
                btnSaveNewKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewKeyword = $('#modalNewKeyword')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#newKeyword').val())
                // formData.append('email', $('#newWebUserEmail').val())
                // formData.append('password', $('#newWebUserPassword').val())
                // formData.append('privilege', $('#newWebUserPrivilege').val())
                // @if(Auth::user()->privilege === 'ROOT')
                //     formData.append('clientgroup', $('#newWebUserClientGroupId').val())
                // @endif
                // @if((Auth::user()->privilege === 'ROOT') || (Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP'))
                //     formData.append('client', $('#newWebUserClient').val())
                // @endif
                // formData.append('canneuapix', $('#newCanSendNeuAPIXMessage').val())

                $.ajax({
                    type: 'POST',
                    url: '/doSaveNewKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('New blacklist keyword is saved.')
                        } else {
                            alert('Failed to save blacklist keyword.')
                        }

                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        alert('Failed to save blacklist keyword.')
                    },
                    error: function(){
                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        alert('Failed to save blacklist keyword.')
                    }
                })
            })

            $('#modalEditKeyword').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let keyword = button.attr('data-editKeyword')

                $('#editKeyword').val(keyword)
                $('#editNewKeyword').val(keyword)
            })

            $('#btnSaveEditKeyword').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditKeyword = $('#btnSaveEditKeyword')
                btnSaveEditKeyword.attr('disabled', true)
                btnSaveEditKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditKeyword = $('#modalEditKeyword')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#editKeyword').val())
                formData.append('newkeyword', $('#editNewKeyword').val())
                // formData.append('webusername', $('#editWebUserFullName').val())
                // formData.append('webuseremail', $('#editWebUserEmail').val())
                // formData.append('webuserpassword', $('#editWebUserPassword').val())
                // formData.append('webuserprivilege', $('#editWebUserPrivilege').val())
                // formData.append('webuserclient', $('#editWebUserClient').val())
                // @if (Auth::user()->privilege === 'ROOT')
                //     formData.append('webuserclientgroupid', $('#editWebUserClientGroupId').val())
                // @endif

                $.ajax({
                    type: 'POST',
                    url: '/doSaveEditKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Blacklist keyword is updated.')
                        } else {
                            alert('Failed to update blacklist keyword.')
                        }

                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        alert('Failed to update blacklist keyword.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        alert('Failed to update blacklist keyword.')
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

            $('#modalDeleteKeyword').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let deleteKeyword = button.attr('data-deleteKeyword')

                $('#deleteKeyword').val(deleteKeyword)
                $('#delNotificationInModal').html('<span>Are you sure want to delete keyword below :<br><br> <span style="color:red; weight:bold;">' + deleteKeyword + '</span> <br><br>Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteKeyword').on('click', function() {
                let btnDeleteKeyword = $('#btnDeleteKeyword')
                let modalDeleteKeyword = $('#modalDeleteKeyword')

                btnDeleteKeyword.attr('disabled', true)
                btnDeleteKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#deleteKeyword').val())

                $.ajax({
                    type: 'POST',
                    url: '/doDeleteKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Keyword is deleted.')
                        } else {
                            alert('Failed to delete keyword.')
                        }

                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete keyword.')
                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete keyword.')
                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')
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
            dropdownParent: $("#modalNewKeyword"),
            width: '100%',
            theme: 'classic'
        })

        $("#editWebUserClient").select2({
            dropdownParent: $("#modalEditKeyword"),
            width: '100%',
            theme: 'classic'
        })
    </script>
@endsection

