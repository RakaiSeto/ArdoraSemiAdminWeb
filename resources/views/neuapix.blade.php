@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    NeuAPIX Management
                    <small>NeuAPIX</small>
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> NeuAPIX Management</a></li>
                    <li class="breadcrumb-item active">NeuAPIX </li>
                </ol>
            </div>

            <!-- Modal new NeuAPIx -->
            <div class="modal center-modal fade" id="modalNewNeuAPIx" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New NeuAPIX Parameters</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newAccId" class="col-sm-4 col-form-label">Acc ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newAccId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newTokenId" class="col-sm-4 col-form-label">Token ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newTokenId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newAppId" class="col-sm-4 col-form-label">App ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newAppId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newDomainName" class="col-sm-4 col-form-label">Domain Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newDomainName">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewNeuAPIx">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit provide group -->
            <div class="modal center-modal fade" id="modalEditNeuAPIx" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit NeuAPIX Parameters</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editAccId">
                                    <div class="form-group row">
                                        <label for="editTokenId" class="col-sm-4 col-form-label">Token ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editTokenId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editAppId" class="col-sm-4 col-form-label">App ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editAppId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editDomainName" class="col-sm-4 col-form-label">Domain Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editDomainName">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditNeuAPIx">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete provide group -->
            <div class="modal center-modal fade" id="modalDelNeuAPIx" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete NeuAPIX Parameters</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delAccId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteNeuAPIx">Delete</button>
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
                                <h3 class="box-title">NeuAPIX Management</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewNeuAPIx" data-toggle="modal" data-target="#modalNewNeuAPIx">New NeuAPIX</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableNeuAPIx" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Acc ID</th>
                                                <th style="text-align: center">Token ID</th>
                                                <th style="text-align: center">App ID</th>
                                                <th style="text-align: center">Domain Name</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Acc ID</th>
                                                <th style="text-align: center">Token ID</th>
                                                <th style="text-align: center">App ID</th>
                                                <th style="text-align: center">Domain Name</th>
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
            let tableNeuAPIx = $('#tableNeuAPIx').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblneuapix",
                    columns: [
                        { data: 'acc_id', name: 'Acc ID'},
                        { data: 'token_id', name: 'Token ID'},
                        { data: 'app_id', name: 'App ID'},
                        { data: 'domain_name', name: 'Domain Name'},
                        { data: 'action', name: 'Action'}
                    ],
                    columnDefs: [
                        { "targets": [0, 1, 2, 3, 4, 5], "className": "text-center"}
                    ]
                }
            )

            $('#modalNewNeuAPIx').on('show.bs.modal', function() {
                $('#newAccId').val('')
                $('#newTokenId').val('')
                $('#newAppId').val('')
                $('#newDomainName').val('')

            })

            $('#btnSaveNewNeuAPIx').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewNeuAPIx = $('#btnSaveNewNeuAPIx')
                btnSaveNewNeuAPIx.attr('disabled', true)
                btnSaveNewNeuAPIx.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewNeuAPIx = $('#modalNewNeuAPIx')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('tokenid', $('#newTokenId').val())
                formData.append('appid', $('#newAppId').val())
                formData.append('domainname', $('#newDomainName').val())
                formData.append('clientgroupid', $('#newClientGroupId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewneuapix',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data NeuAPIx is saved.')
                        } else {
                            alert('Failed to save data NeuAPIx.')
                        }

                        btnSaveNewNeuAPIx.attr('disabled', false)
                        modalNewNeuAPIx.modal('toggle')
                        btnSaveNewNeuAPIx.html('Save')

                        tableNeuAPIx.ajax.reload()
                    },
                    fail: function(){
                        btnSaveNewNeuAPIx.attr('disabled', false)
                        modalNewNeuAPIx.modal('toggle')
                        btnSaveNewNeuAPIx.html('Save')

                        alert('Failed to save data NeuAPIx group.')
                    },
                    error: function(){
                        btnSaveNewNeuAPIx.attr('disabled', false)
                        modalNewNeuAPIx.modal('toggle')
                        btnSaveNewNeuAPIx.html('Save')

                        alert('Failed to save data NeuAPIx.')
                    }
                })
            })

            $('#modalEditNeuAPIx').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let accId = button.attr('data-accId')
                let tokenId = button.attr('data-tokenId')
                let appId = button.attr('data-appId')
                let domainName = button.attr('data-domainName')

                $('#editAccId').val(accId)
                $('#editTokenId').val(tokenId)
                $('#editAppId').val(appId)
                $('#editDomainName').val(domainName)
            })

            $('#btnSaveEditNeuAPIx').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditNeuAPIx = $('#btnSaveEditNeuAPIx')
                btnSaveEditNeuAPIx.attr('disabled', true)
                btnSaveEditNeuAPIx.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditNeuAPIx = $('#modalEditNeuAPIx')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('accid', $('#editAccId').val())
                formData.append('tokenid', $('#editTokenId').val())
                formData.append('appid', $('#editAppId').val())
                formData.append('domainname', $('#editDomainName').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditneuapix',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data NeuAPIx is updated.')
                        } else {
                            alert('Failed to update data NeuAPIx.')
                        }

                        btnSaveEditNeuAPIx.attr('disabled', false)
                        modalEditNeuAPIx.modal('toggle')
                        btnSaveEditNeuAPIx.html('Save')

                        tableNeuAPIx.ajax.reload()
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditNeuAPIx.attr('disabled', false)
                        modalEditNeuAPIx.modal('toggle')
                        btnSaveEditNeuAPIx.html('Save')

                        alert('Failed to update data NeuAPIx.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditNeuAPIx.attr('disabled', false)
                        modalEditNeuAPIx.modal('toggle')
                        btnSaveEditNeuAPIx.html('Save')

                        alert('Failed to update data NeuAPIx.')
                    }
                })
            })

            $('#modalDelNeuAPIx').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let accId = button.attr('data-accId')

                $('#delAccId').val(accId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete NeuAPIx <span style="color:red">' + accId + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteNeuAPIx').on('click', function() {
                let btnDeleteNeuAPIx = $('#btnDeleteNeuAPIx')
                let modalDelNeuAPIx = $('#modalDelNeuAPIx')

                btnDeleteNeuAPIx.attr('disabled', true)
                btnDeleteNeuAPIx.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('accid', $('#delAccId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeleteneuapix',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data NeuAPIx is deleted.')
                        } else {
                            alert('Failed to delete data NeuAPIx.')
                        }

                        btnDeleteNeuAPIx.attr('disabled', false)
                        modalDelNeuAPIx.modal('toggle')
                        btnDeleteNeuAPIx.html('Delete')

                        tableNeuAPIx.ajax.reload()
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data NeuAPIx.')
                        btnDeleteNeuAPIx.attr('disabled', false)
                        modalDelNeuAPIx.modal('toggle')
                        btnDeleteNeuAPIx.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data NeuAPIx.')
                        btnDeleteNeuAPIx.attr('disabled', false)
                        modalDelNeuAPIx.modal('toggle')
                        btnDeleteNeuAPIx.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
