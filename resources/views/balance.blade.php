@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">

            <div class="modal fade" id="modalTopUp" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Clent Balance Top Up</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="tuClientId">
                                    <div class="form-group row">
                                        <label for="tuClientName" class="col-sm-4 col-form-label">Client</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="tuClientName" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tuAdjustmentType" class="col-sm-4 col-form-label">Balance Adjustment Type</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="tuAdjustmentType">
                                                <option value="TOPUP" selected>TOP UP</option>
                                                <option value="TRANSFER">TRANSFER</option>
                                                <option value="ADJUSTMENT">ADJUSTMENT</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tuClientBalance" class="col-sm-4 col-form-label">Client Balance</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="number" id="tuClientBalance" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tuClientCurrency" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="tuClientCurrency" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tuValue" class="col-sm-4 col-form-label">Adjustment Value</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="number" id="tuValue">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnTopUp">Top Up</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <h1 class="page-header text-center mt-30 mb-30">
                            <span class="text-bold text-info">Client Balance</span><br>
                        </h1>
                        <div class="row">
                            <div class="col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="form-group row">
                                            <label for="searchClient" class="col-sm-4 col-form-label">Search Client</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="searchClient">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-success" id="btnView"><i class="fa fa-envelope-o"></i> View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="box">
                                    <div class="box-header no-border">
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table no-margin" id="tableBalance">
                                                <thead>
                                                <tr>
                                                    <th>Client</th>
                                                    <th>Currency</th>
                                                    <th style="text-align: right">Balance</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
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
        $(document).ready(function() {
            let tableBalance = $('#tableBalance').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                scrollX: true,
                ajax: "/tblBalance",
                columns: [
                    { data: 'client_name', name: 'client_name', width: '20%'},
                    { data: 'currency_id', name: 'currency_id', width: '5%'},
                    { data: 'now_balance', name: 'now_balance', width: '20%'}
                ],
                columnDefs: [
                    { "targets": [0, 1, 2], "className": "text-center"},
                ]
            })

            $('#modalTopUp').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let clientId = button.attr('data-clientId')

                $('#tuClientId').val(clientId)

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('selectedClientId', $('#tuClientId').val())

                $.ajax({
                    type: 'POST',
                    url: '/getclientbalance',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        let dataBalance = JSON.parse(dataX)[0]

                        $('#tuClientName').val(dataBalance['client_name'])
                        $('#tuClientBalance').val(dataBalance['client_balance'])
                        $('#tuClientCurrency').val(dataBalance['currency_id'])
                    },
                    fail: function(){
                        $('#tuClientName').val('N/A')
                        $('#tuClientBalance').val('0')
                        $('#tuClientCurrency').val('')
                    },
                    error: function(){
                        $('#tuClientName').val('N/A')
                        $('#tuClientBalance').val('0')
                        $('#tuClientCurrency').val('')
                    }
                })
            })

            $('#btnView').on('click', function (e) {
                tableBalance.ajax.url('/tblBalance?selectedclientid=' + $('#searchClient').val()).load()
            })

            $('#btnTopUp').on('click', function (e) {
                e.preventDefault()

                $('#btnTopUp').attr('disabled', true)
                $('#btnTopUp').html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Topping Up')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientId', $('#tuClientId').val())
                formData.append('value', $('#tuValue').val())

                $.ajax({
                    type: 'POST',
                    url: '/dotopup',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        $('#btnTopUp').attr('disabled', false)
                        $('#modalTopUp').modal('toggle')
                        $('#btnTopUp').html('Top Up')

                        tableBalance.ajax.reload(null, false)
                    },
                    fail: function(){
                        $('#btnTopUp').attr('disabled', false)
                        $('#modalTopUp').modal('toggle')
                        $('#btnTopUp').html('Top Up')

                        alert('Failed to top up client balance.')
                    },
                    error: function(){
                        $('#btnTopUp').attr('disabled', false)
                        $('#modalTopUp').modal('toggle')
                        $('#btnTopUp').html('Top Up')

                        alert('Failed to top up client balance.')
                    }
                })
            })

        })
    </script>
@endsection
