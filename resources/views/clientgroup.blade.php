@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
{{--            <div class="content-header">--}}
{{--                <h3>--}}
{{--                    Client Management--}}
{{--                    <small>Provider</small>--}}
{{--                </h3>--}}
{{--                <ol class="breadcrumb">--}}
{{--                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Client Management</a></li>--}}
{{--                    <li class="breadcrumb-item active">Provider </li>--}}
{{--                </ol>--}}
{{--            </div>--}}

            <!-- Modal new provide group -->
            <div class="modal center-modal fade" id="modalNewProvider" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Client Group</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newGroupName" class="col-sm-4 col-form-label">Group Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newGroupName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newGroupDescription" class="col-sm-4 col-form-label">Description</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newGroupDescription">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewProvider">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit provide group -->
            <div class="modal center-modal fade" id="modalEditProvider" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Client Group</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editGroupId">
                                    <div class="form-group row">
                                        <label for="editGroupName" class="col-sm-4 col-form-label">Group Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editGroupName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editGroupDescription" class="col-sm-4 col-form-label">Description</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editGroupDescription">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditProvider">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete provide group -->
            <div class="modal center-modal fade" id="modalDelProvider" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Client Group</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delGroupId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteProvider">Delete</button>
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
                                <h3 class="box-title">Client Group</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewGroup" data-toggle="modal" data-target="#modalNewProvider">New Group</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableClientGroup" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Group ID</th>
                                                <th style="text-align: center">Group Name</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Group ID</th>
                                                <th style="text-align: center">Group Name</th>
                                                <th style="text-align: center">Description</th>
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
            let tableClientGroup = $('#tableClientGroup').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblClientGroup",
                    columns: [
                        { data: 'group_id', name: 'Group ID'},
                        { data: 'group_name', name: 'Group Name'},
                        { data: 'description', name: 'Description'},
                        { data: 'action', name: 'Action'}
                    ],
                    columnDefs: [
                        { "targets": [0, 3], "className": "text-center"}
                    ]
                }
            )

            $('#modalNewProvider').on('show.bs.modal', function() {
                $('#newGroupName').val('')
                $('#newGroupDescription').val('')
            })

            $('#btnSaveNewProvider').on('click', function(e) {
                e.preventDefault()

                $('#btnSaveNewProvider').attr('disabled', true)
                $('#btnSaveNewProvider').html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('groupname', $('#newGroupName').val())
                formData.append('groupdesc', $('#newGroupDescription').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewprovider',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client group is saved.')
                        } else {
                            alert('Failed to save data client group.')
                        }

                        $('#btnSaveNewProvider').attr('disabled', false)
                        $('#modalNewProvider').modal('toggle')
                        $('#btnSaveNewProvider').html('Save')

                        tableClientGroup.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        $('#btnSaveNewProvider').attr('disabled', false)
                        $('#modalNewProvider').modal('toggle')
                        $('#btnSaveNewProvider').html('Save')

                        alert('Failed to save data client group.')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        $('#btnSaveNewProvider').attr('disabled', false)
                        $('#modalNewProvider').modal('toggle')
                        $('#btnSaveNewProvider').html('Save')

                        alert('Failed to save data client group.')
                    }
                })
            })

            $('#modalEditProvider').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let groupId = button.attr('data-groupId')
                let groupName = button.attr('data-groupName')
                let groupDesc = button.attr('data-groupDesc')

                $('#editGroupId').val(groupId)
                $('#editGroupName').val(groupName)
                $('#editGroupDescription').val(groupDesc)
            })

            $('#btnSaveEditProvider').on('click', function(e) {
                e.preventDefault()

                $('#btnSaveEditProvider').attr('disabled', true)
                $('#btnSaveEditProvider').html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('groupid', $('#editGroupId').val())
                formData.append('groupname', $('#editGroupName').val())
                formData.append('groupdesc', $('#editGroupDescription').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditprovider',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client group is updated.')
                        } else {
                            alert('Failed to update data client group.')
                        }

                        $('#btnSaveEditProvider').attr('disabled', false)
                        $('#modalEditProvider').modal('toggle')
                        $('#btnSaveEditProvider').html('Save')

                        tableClientGroup.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        $('#btnSaveEditProvider').attr('disabled', false)
                        $('#modalEditProvider').modal('toggle')
                        $('#btnSaveEditProvider').html('Save')

                        alert('Failed to update data client group.')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        $('#btnSaveEditProvider').attr('disabled', false)
                        $('#modalEditProvider').modal('toggle')
                        $('#btnSaveEditProvider').html('Save')

                        alert('Failed to update data client group.')
                    }
                })
            })

            $('#modalDelProvider').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let groupId = button.attr('data-groupId')
                let groupName = button.attr('data-groupName')

                $('#delGroupId').val(groupId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete client group <span style="color:red">' + groupName + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteProvider').on('click', function(e) {
                $('#btnDeleteProvider').attr('disabled', true)
                $('#btnDeleteProvider').html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('groupid', $('#delGroupId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeleteprovider',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client group is deleted.')
                        } else {
                            alert('Failed to delete data client group.')
                        }

                        $('#btnDeleteProvider').attr('disabled', false)
                        $('#modalDelProvider').modal('toggle')
                        $('#btnDeleteProvider').html('Delete')

                        tableClientGroup.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        alert('Failed to delete data client group.')
                        $('#btnDeleteProvider').attr('disabled', false)
                        $('#modalDelProvider').modal('toggle')
                        $('#btnDeleteProvider').html('Delete')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        alert('Failed to delete data client group.')
                        $('#btnDeleteProvider').attr('disabled', false)
                        $('#modalDelProvider').modal('toggle')
                        $('#btnDeleteProvider').html('Delete')
                    }
                })
            })
        })
    </script>
@endsection
