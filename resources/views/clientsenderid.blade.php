@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    Client Sender ID
                    <small>Management</small>
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Client Management</a></li>
                    <li class="breadcrumb-item active">Client Sender ID </li>
                </ol>
            </div>

            <!-- Modal new client sender id -->
            <div class="modal center-modal fade" id="modalNewClientSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Client Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newClientSenderId" class="col-sm-4 col-form-label">Client Sender ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newClientSenderId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientMasking" class="col-sm-4 col-form-label">Masking</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newClientMasking">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientId" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newClientId">
                                                @if(isset($clientData))
                                                    @foreach($clientData as $clientDataX)
                                                        <option value="{{ trim($clientDataX->client_id) }}">{{ trim($clientDataX->client_name) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewClientSenderId">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit new client sender id -->
            <div class="modal center-modal fade" id="modalEditClientSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Client Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editClientSenderIdId">
                                    <div class="form-group row">
                                        <label for="editClientSenderId" class="col-sm-4 col-form-label">Client Sender ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editClientSenderId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editClientMasking" class="col-sm-4 col-form-label">Masking</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editClientMasking">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editClientId" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editClientId">
                                                @if(isset($clientData))
                                                    @foreach($clientData as $clientDataX)
                                                        <option value="{{ trim($clientDataX->client_id) }}">{{ trim($clientDataX->client_name) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditClientSenderId">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete client sender id -->
            <div class="modal center-modal fade" id="modalDelClientSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Client Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delClientSenderIdId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteClientSenderId">Delete</button>
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
                                <h3 class="box-title">Client Sender ID</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewClientSenderId" data-toggle="modal" data-target="#modalNewClientSenderId">New Client Sender ID</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableClientSenderId" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Client Sender ID</th>
                                                <th style="text-align: center">Masking</th>
                                                <th style="text-align: center">Client</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Is Active</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Client Sender ID</th>
                                                <th style="text-align: center">Masking</th>
                                                <th style="text-align: center">Client</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Is Active</th>
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
            let tableClientSenderId = $('#tableClientSenderId').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblClientSenderId",
                    columns: [
                        { data: 'sender_id', name: 'sender_id'},
                        { data: 'masking', name: 'masking'},
                        { data: 'client_name', name: 'client_name'},
                        @if(Auth::user()->privilege === 'ROOT')
                        { data: 'group_name', name: 'group_name'},
                        @endif
                        {data: 'is_active', name: 'is_active'},
                        { data: 'action', name: 'action'}
                    ],
                    columnDefs: [
                        { "targets": "_all", "className": "text-center"}
                    ]
                }
            )

            $('#modalNewClientSenderId').on('show.bs.modal', function() {
                $('#newClientSenderId').val('')
                $('#newClientMasking').val('')
            })

            $('#btnSaveNewClientSenderId').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewClientSenderId = $('#btnSaveNewClientSenderId')
                btnSaveNewClientSenderId.attr('disabled', true)
                btnSaveNewClientSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewClientSenderId = $('#modalNewClientSenderId')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientsenderid', $('#newClientSenderId').val())
                formData.append('clientmasking', $('#newClientMasking').val())
                formData.append('clientid', $('#newClientId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewclientsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Client Sender ID is saved.')
                        } else {
                            alert('Failed to save data Client Sender ID.')
                        }

                        btnSaveNewClientSenderId.attr('disabled', false)
                        modalNewClientSenderId.modal('toggle')
                        btnSaveNewClientSenderId.html('Save')

                        tableClientSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewClientSenderId.attr('disabled', false)
                        modalNewClientSenderId.modal('toggle')
                        btnSaveNewClientSenderId.html('Save')

                        alert('Failed to save data Client Sender ID.')
                    },
                    error: function(){
                        btnSaveNewClientSenderId.attr('disabled', false)
                        modalNewClientSenderId.modal('toggle')
                        btnSaveNewClientSenderId.html('Save')

                        alert('Failed to save data client.')
                    }
                })
            })

            $('#modalEditClientSenderId').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let clientSenderIdId = button.attr('data-editClientSenderIdId')
                let clientSenderId = button.attr('data-editClientSenderId')
                let clientMasking = button.attr('data-editMasking')
                let clientId = button.attr('data-editClientId')

                $('#editClientSenderIdId').val(clientSenderIdId)
                $('#editClientSenderId').val(clientSenderId)
                $('#editClientMasking').val(clientMasking)
                $('#editClientId').val(clientId)
            })

            $('#btnSaveEditClientSenderId').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditClientSenderId = $('#btnSaveEditClientSenderId')
                btnSaveEditClientSenderId.attr('disabled', true)
                btnSaveEditClientSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditClientSenderId = $('#modalEditClientSenderId')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientsenderidid', $('#editClientSenderIdId').val())
                formData.append('clientsenderid', $('#editClientSenderId').val())
                formData.append('clientmasking', $('#editClientMasking').val())
                formData.append('clientid', $('#editClientId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditclientsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Client Sender ID is updated.')
                        } else {
                            alert('Failed to update data Client Sender ID.')
                        }

                        btnSaveEditClientSenderId.attr('disabled', false)
                        modalEditClientSenderId.modal('toggle')
                        btnSaveEditClientSenderId.html('Save')

                        tableClientSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditClientSenderId.attr('disabled', false)
                        modalEditClientSenderId.modal('toggle')
                        btnSaveEditClientSenderId.html('Save')

                        alert('Failed to update data Client Sender ID.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditClientSenderId.attr('disabled', false)
                        modalEditClientSenderId.modal('toggle')
                        btnSaveEditClientSenderId.html('Save')

                        alert('Failed to update data Client Sender ID.')
                    }
                })
            })

            $('#modalDelClientSenderId').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let clientSenderIdId = button.attr('data-delClientSenderIdId')
                let clientSenderId = button.attr('data-delClientSenderId')

                $('#delClientSenderIdId').val(clientSenderIdId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete client sender ID <span style="color:red">' + clientSenderId + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteClientSenderId').on('click', function() {
                let btnDeleteClientSenderId = $('#btnDeleteClientSenderId')
                let modalDeleteClientSenderId = $('#modalDelClientSenderId')

                btnDeleteClientSenderId.attr('disabled', true)
                btnDeleteClientSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientsenderidid', $('#delClientSenderIdId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeleteclientsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client sender id is deleted.')
                        } else {
                            alert('Failed to delete data client sender id.')
                        }

                        btnDeleteClientSenderId.attr('disabled', false)
                        modalDeleteClientSenderId.modal('toggle')
                        btnDeleteClientSenderId.html('Delete')

                        tableClientSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Client Sender ID.')
                        btnDeleteClientSenderId.attr('disabled', false)
                        modalDeleteClientSenderId.modal('toggle')
                        btnDeleteClientSenderId.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Client Sender ID.')
                        btnDeleteClientSenderId.attr('disabled', false)
                        modalDeleteClientSenderId.modal('toggle')
                        btnDeleteClientSenderId.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
