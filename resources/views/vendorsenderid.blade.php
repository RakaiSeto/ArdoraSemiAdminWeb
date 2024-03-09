@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    Vendor Sender ID
                    <small>Management</small>
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Vendor Management</a></li>
                    <li class="breadcrumb-item active">Vendor Sender ID </li>
                </ol>
            </div>

            <!-- Modal new vendor sender id -->
            <div class="modal center-modal fade" id="modalNewVendorSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Vendor Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newVendorSenderId" class="col-sm-4 col-form-label">Vendor Sender ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorSenderId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorMasking" class="col-sm-4 col-form-label">Masking</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorMasking">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorId" class="col-sm-4 col-form-label">Vendor</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newVendorId">
                                                @if(isset($vendorData))
                                                    @foreach($vendorData as $vendorDataX)
                                                        <option value="{{ trim($vendorDataX->vendor_id) }}">{{ trim($vendorDataX->vendor_name) }}</option>
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
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewVendorSenderId">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit vendor sender id -->
            <div class="modal center-modal fade" id="modalEditVendorSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Vendor Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editVendorSenderIdId">
                                    <div class="form-group row">
                                        <label for="editVendorSenderId" class="col-sm-4 col-form-label">Vendor Sender ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorSenderId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorMasking" class="col-sm-4 col-form-label">Masking</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorMasking">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorId" class="col-sm-4 col-form-label">Vendor</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editVendorId">
                                                @if(isset($vendorData))
                                                    @foreach($vendorData as $vendorDataX)
                                                        <option value="{{ trim($vendorDataX->vendor_id) }}">{{ trim($vendorDataX->vendor_name) }}</option>
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
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditVendorSenderId">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete client sender id -->
            <div class="modal center-modal fade" id="modalDelVendorSenderId" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Vendor Sender ID</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delVendorSenderIdId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteVendorSenderId">Delete</button>
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
                                <h3 class="box-title">Vendor Sender ID</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewVendorSenderId" data-toggle="modal" data-target="#modalNewVendorSenderId">New Vendor Sender ID</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableVendorSenderId" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Vendor Sender ID</th>
                                                <th style="text-align: center">Masking</th>
                                                <th style="text-align: center">Vendor</th>
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
                                                <th style="text-align: center">Vendor Sender ID</th>
                                                <th style="text-align: center">Masking</th>
                                                <th style="text-align: center">Vendor</th>
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
            let tableVendorSenderId = $('#tableVendorSenderId').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblVendorSenderId",
                    columns: [
                        { data: 'sender_id', name: 'sender_id'},
                        { data: 'masking', name: 'masking'},
                        { data: 'vendor_name', name: 'vendor_name'},
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

            $('#modalNewVendorSenderId').on('show.bs.modal', function() {
                $('#newVendorSenderId').val('')
                $('#newVendorMasking').val('')
            })

            $('#btnSaveNewVendorSenderId').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewVendorSenderId = $('#btnSaveNewVendorSenderId')
                btnSaveNewVendorSenderId.attr('disabled', true)
                btnSaveNewVendorSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewVendorSenderId = $('#modalNewVendorSenderId')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorsenderid', $('#newVendorSenderId').val())
                formData.append('vendormasking', $('#newVendorMasking').val())
                formData.append('vendorid', $('#newVendorId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewvendorsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Vendor Sender ID is saved.')
                        } else {
                            alert('Failed to save data Vendor Sender ID.')
                        }

                        btnSaveNewVendorSenderId.attr('disabled', false)
                        modalNewVendorSenderId.modal('toggle')
                        btnSaveNewVendorSenderId.html('Save')

                        tableVendorSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewVendorSenderId.attr('disabled', false)
                        modalNewVendorSenderId.modal('toggle')
                        btnSaveNewVendorSenderId.html('Save')

                        alert('Failed to save data Vendor Sender ID.')
                    },
                    error: function(){
                        btnSaveNewVendorSenderId.attr('disabled', false)
                        modalNewVendorSenderId.modal('toggle')
                        btnSaveNewVendorSenderId.html('Save')

                        alert('Failed to save data Vendor Sender ID.')
                    }
                })
            })

            $('#modalEditVendorSenderId').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let vendorSenderIdId = button.attr('data-editVendorSenderIdId')
                let vendorSenderId = button.attr('data-editVendorSenderId')
                let vendorMasking = button.attr('data-editVendorMasking')
                let vendorId = button.attr('data-editVendorId')

                $('#editVendorSenderIdId').val(vendorSenderIdId)
                $('#editVendorSenderId').val(vendorSenderId)
                $('#editVendorMasking').val(vendorMasking)
                $('#editVendorId').val(vendorId)
            })

            $('#btnSaveEditVendorSenderId').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditVendorSenderId = $('#btnSaveEditVendorSenderId')
                btnSaveEditVendorSenderId.attr('disabled', true)
                btnSaveEditVendorSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditVendorSenderId = $('#modalEditVendorSenderId')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorsenderidid', $('#editVendorSenderIdId').val())
                formData.append('vendorsenderid', $('#editVendorSenderId').val())
                formData.append('vendormasking', $('#editVendorMasking').val())
                formData.append('vendorid', $('#editVendorId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditvendorsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Vendor Sender ID is updated.')
                        } else {
                            alert('Failed to update data Vendor Sender ID.')
                        }

                        btnSaveEditVendorSenderId.attr('disabled', false)
                        modalEditVendorSenderId.modal('toggle')
                        btnSaveEditVendorSenderId.html('Save')

                        tableVendorSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditVendorSenderId.attr('disabled', false)
                        modalEditVendorSenderId.modal('toggle')
                        btnSaveEditVendorSenderId.html('Save')

                        alert('Failed to update data Vendor Sender ID.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditVendorSenderId.attr('disabled', false)
                        modalEditVendorSenderId.modal('toggle')
                        btnSaveEditVendorSenderId.html('Save')

                        alert('Failed to update data Vendor Sender ID.')
                    }
                })
            })

            $('#modalDelVendorSenderId').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let vendorSenderIdId = button.attr('data-delVendorSenderIdId')
                let vendorSenderId = button.attr('data-delVendorSenderId')

                $('#delVendorSenderIdId').val(vendorSenderIdId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete client sender ID <span style="color:red">' + vendorSenderId + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteVendorSenderId').on('click', function() {
                let btnDeleteVendorSenderId = $('#btnDeleteVendorSenderId')
                let modalDeleteVendorSenderId = $('#modalDelVendorSenderId')

                btnDeleteVendorSenderId.attr('disabled', true)
                btnDeleteVendorSenderId.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorsenderidid', $('#delVendorSenderIdId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeletevendorsenderid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data vendor sender id is deleted.')
                        } else {
                            alert('Failed to delete data vendor sender id.')
                        }

                        btnDeleteVendorSenderId.attr('disabled', false)
                        modalDeleteVendorSenderId.modal('toggle')
                        btnDeleteVendorSenderId.html('Delete')

                        tableVendorSenderId.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Vendor Sender ID.')
                        btnDeleteVendorSenderId.attr('disabled', false)
                        modalDeleteVendorSenderId.modal('toggle')
                        btnDeleteVendorSenderId.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Vendor Sender ID.')
                        btnDeleteVendorSenderId.attr('disabled', false)
                        modalDeleteVendorSenderId.modal('toggle')
                        btnDeleteVendorSenderId.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
