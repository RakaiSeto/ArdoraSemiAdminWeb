@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    Currency Management
                </h3>
            </div>

            <!-- Modal new currency -->
            <div class="modal center-modal fade" id="modalNewCurrency" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Currency</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newCurrencyID" class="col-sm-4 col-form-label">Currency ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newCurrencyID">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newCurrencyName" class="col-sm-4 col-form-label">Currency Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newCurrencyName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newCurrencyDescription" class="col-sm-4 col-form-label">Description</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newCurrencyDescription">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewCurrency">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit currency -->
            <div class="modal center-modal fade" id="modalEditCurrency" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Currency</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editCurrentCurrencyId">
                                    <div class="form-group row">
                                        <label for="editCurrencyId" class="col-sm-4 col-form-label">Currency ID</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editCurrencyId">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editCurrencyName" class="col-sm-4 col-form-label">Currency Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editCurrencyName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editCurrencyDescription" class="col-sm-4 col-form-label">Description</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editCurrencyDescription">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditCurrency">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete currency -->
            <div class="modal center-modal fade" id="modalDelCurrency" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Currency</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delCurrencyId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteCurrency">Delete</button>
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
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewCurrency" data-toggle="modal" data-target="#modalNewCurrency">New Currency</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableCurrency" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Currency ID</th>
                                                <th style="text-align: center">Currency Name</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Currency ID</th>
                                                <th style="text-align: center">Currency Name</th>
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
            let tableCurrency = $('#tableCurrency').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblCurrency",
                    columns: [
                        { data: 'currency_id', name: 'currency_id'},
                        { data: 'currency_name', name: 'currency_name'},
                        { data: 'currency_description', name: 'currency_description'},
                        { data: 'action', name: 'action'}
                    ]
                }
            )

            $('#modalNewCurrency').on('show.bs.modal', function() {
                $('#newCurrencyID').val('')
                $('#newCurrencyName').val('')
                $('#newCurrencyDescription').val('')
            })

            $('#btnSaveNewCurrency').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewCurrency = $('#btnSaveNewCurrency')
                let modalNewCurrency = $('#modalNewCurrency')

                btnSaveNewCurrency.attr('disabled', true)
                btnSaveNewCurrency.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('currencyid', $('#newCurrencyID').val())
                formData.append('currencyname', $('#newCurrencyName').val())
                formData.append('currencydescription', $('#newCurrencyDescription').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewcurrency',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data currency is saved.')
                        } else {
                            alert('Failed to save data currency.')
                        }

                        btnSaveNewCurrency.attr('disabled', false)
                        modalNewCurrency.modal('toggle')
                        btnSaveNewCurrency.html('Save')

                        tableCurrency.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        btnSaveNewCurrency.attr('disabled', false)
                        modalNewCurrency.modal('toggle')
                        btnSaveNewCurrency.html('Save')

                        alert('Failed to save data currency.')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        btnSaveNewCurrency.attr('disabled', false)
                        modalNewCurrency.modal('toggle')
                        btnSaveNewCurrency.html('Save')

                        alert('Failed to save data currency.')
                    }
                })
            })

            $('#modalEditCurrency').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let currencyId = button.attr('data-currencyId')
                let currencyName = button.attr('data-currencyName')
                let currencyDesc = button.attr('data-currencyDesc')

                $('#editCurrentCurrencyId').val(currencyId)
                $('#editCurrencyId').val(currencyId)
                $('#editCurrencyName').val(currencyName)
                $('#editCurrencyDescription').val(currencyDesc)
            })

            $('#btnSaveEditCurrency').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditCurrency = $('#btnSaveEditCurrency')
                let modalEditCurrency = $('#modalEditCurrency')

                btnSaveEditCurrency.attr('disabled', true)
                btnSaveEditCurrency.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('currentcurrencyid', $('#editCurrentCurrencyId').val())
                formData.append('currencyid', $('#editCurrencyId').val())
                formData.append('currencyname', $('#editCurrencyName').val())
                formData.append('currencydescription', $('#editCurrencyDescription').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditcurrency',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data currency is updated.')
                        } else {
                            alert('Failed to update data currency.')
                        }

                        btnSaveEditCurrency.attr('disabled', false)
                        modalEditCurrency.modal('toggle')
                        btnSaveEditCurrency.html('Save')

                        tableCurrency.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        btnSaveEditCurrency.attr('disabled', false)
                        modalEditCurrency.modal('toggle')
                        btnSaveEditCurrency.html('Save')

                        alert('Failed to update data currency.')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        btnSaveEditCurrency.attr('disabled', false)
                        modalEditCurrency.modal('toggle')
                        btnSaveEditCurrency.html('Save')

                        alert('Failed to update data currency.')
                    }
                })
            })

            $('#modalDelCurrency').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let currencyId = button.attr('data-currencyId')
                let currencyName = button.attr('data-currencyName')

                $('#delCurrencyId').val(currencyId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete currency <span style="color:red">' + currencyName + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteCurrency').on('click', function(e) {
                let btnDeleteCurrency = $('#btnDeleteCurrency')
                let modalDeleteCurrency = $('#modalDelCurrency')

                btnDeleteCurrency.attr('disabled', true)
                btnDeleteCurrency.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('currencyid', $('#delCurrencyId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeletecurrency',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data currency is deleted.')
                        } else {
                            alert('Failed to delete data currency.')
                        }

                        btnDeleteCurrency.attr('disabled', false)
                        modalDeleteCurrency.modal('toggle')
                        btnDeleteCurrency.html('Delete')

                        tableCurrency.ajax.reload(null, false)
                    },
                    fail: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        alert('Failed to delete data currency.')
                        btnDeleteCurrency.attr('disabled', false)
                        modalDeleteCurrency.modal('toggle')
                        btnDeleteCurrency.html('Delete')
                    },
                    error: function(xhr, textStatus, errorThrown){
                        //console.log('failed.')
                        alert('Failed to delete data currency.')
                        btnDeleteCurrency.attr('disabled', false)
                        modalDeleteCurrency.modal('toggle')
                        btnDeleteCurrency.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection
