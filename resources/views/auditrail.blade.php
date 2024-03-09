@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Auditrail - Parameter</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="form-group row">
                                    <label for="trxDateTime" class="col-sm-4 col-form-label">Activity Date Range</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="trxDateTime">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="searchParameter" class="col-sm-4 col-form-label">Search Field/Category</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="searchParameter">
                                            <option selected value="username">User Name</option>
                                            <option value="menu">Menu Page</option>
                                            <option value="clientName">Client Name</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="searchKeyword" class="col-sm-4 col-form-label">Keyword</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="searchKeyword">
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
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Activity Report - Data</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="tableTransaction" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                            <tr>
                                <th style="text-align: center">Date Time</th>
                                <th style="text-align: center">Batch ID</th>
                                <th style="text-align: center">Message ID</th>
                                <th style="text-align: center">Sender ID</th>
                                <th style="text-align: center">MSISDN</th>
                                <th style="text-align: center">Message</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Client</th>
                                <th style="text-align: center">Country</th>
                                <th style="text-align: center">Encoding</th>
                                <th style="text-align: center">Length</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Voice Duration (seconds)</th>
                                <th style="text-align: right">Price</th>
                                <th style="text-align: left">Receiver Date Time</th>
                                <th style="text-align: left">Receiver Data</th>
                                <th style="text-align: left">Receiver Response Date Time</th>
                                <th style="text-align: left">Receiver ClientResponse</th>
                                <th style="text-align: left">Receiver IP Address</th>
                                <th style="text-align: center">Vendor ID</th>
                                <th style="text-align: left">Vendor Req. Date Time</th>
                                <th style="text-align: left">Vendor Request</th>
                                <th style="text-align: left">Vendor Resp. Date Time</th>
                                <th style="text-align: left">Vendor Response</th>
                                <th style="text-align: left">Vendor Message ID</th>
                                <th style="text-align: left">Vendor Callback Date Time</th>
                                <th style="text-align: left">Vendor Callback</th>
                                <th style="text-align: left">Vendor Trx. Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="text-align: center">Date Time</th>
                                <th style="text-align: center">Batch ID</th>
                                <th style="text-align: center">Message ID</th>
                                <th style="text-align: center">Sender ID</th>
                                <th style="text-align: center">MSISDN</th>
                                <th style="text-align: center">Message</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Client</th>
                                <th style="text-align: center">Country</th>
                                <th style="text-align: center">Encoding</th>
                                <th style="text-align: center">Length</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Voice Duration (seconds)</th>
                                <th style="text-align: center">Price</th>
                                <th style="text-align: left">Receiver Date Time</th>
                                <th style="text-align: left">Receiver Data</th>
                                <th style="text-align: left">Receiver Response Date Time</th>
                                <th style="text-align: left">Receiver ClientResponse</th>
                                <th style="text-align: left">Receiver IP Address</th>
                                <th style="text-align: center">Vendor ID</th>
                                <th style="text-align: left">Vendor Req. Date Time</th>
                                <th style="text-align: left">Vendor Request</th>
                                <th style="text-align: left">Vendor Resp. Date Time</th>
                                <th style="text-align: left">Vendor Response</th>
                                <th style="text-align: left">Vendor Message ID</th>
                                <th style="text-align: left">Vendor Callback Date Time</th>
                                <th style="text-align: left">Vendor Callback</th>
                                <th style="text-align: left">Vendor Trx. Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jscript')
    <script>
        $('#trxDateTime').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
    </script>

    <script>
        $(document).ready(function(){
            let tableTransaction = $('#tableTransaction').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblTransaction?daterange=" + $('#trxDateTime').val() + "&selectedclientid=" + $('#clientId').val() +
                        "&searchcategory=" + $('#searchParameter').val() + "&searchkeyword=" + $('#searchKeyword').val(),
                    columns: [
                        { data: 'transaction_date', name: 'Date Time'},
                        { data: 'batch_id', name: 'Batch ID'},
                        { data: 'message_id', name: 'Message ID'},
                        { data: 'client_sender_id', name: 'Sender ID'},
                        { data: 'msisdn', name: 'MSISDN'},
                        { data: 'message', name: 'Message'},
                        { data: 'description', name: 'Status'},
                        { data: 'client_name', name: 'Client'},
                        { data: 'country_name', name: 'Country'},
                        { data: 'message_encodng', name: 'Encoding'},
                        { data: 'message_length', name: 'Length'},
                        { data: 'sms_count', name: 'SMS Count'},
                        { data: 'call_duration', name: 'Voice Duration'},
                        { data: 'client_price_total', name: 'Price'},
                        { data: 'receiver_date_time', name: 'Receiver Date Time'},
                        { data: 'receiver_data', name: 'Receiver Data'},
                        { data: 'receiver_client_response_date_time', name: 'Receiver Response Date Time'},
                        { data: 'receiver_client_response', name: 'Receiver Client Response'},
                        { data: 'client_ip_address', name: 'Receiver IP Address'},
                        { data: 'vendor_id', name: 'Vendor ID'},
                        { data: 'vendor_hit_date_time', name: 'Vendor Req. Date Time'},
                        { data: 'vendor_hit_request', name: 'Vendor Request'},
                        { data: 'vendor_hit_resp_date_time', name: 'Vendor Resp. Date Time'},
                        { data: 'vendor_hit_response', name: 'Vendor Response'},
                        { data: 'vendor_message_id', name: 'Vendor Message ID'},
                        { data: 'vendor_callback_date_time', name: 'Vendor Callback Date Time'},
                        { data: 'vendor_callback', name: 'Vendor Callback'},
                        { data: 'vendor_trx_status', name: 'Vendor Trx. Status'}
                    ],
                    columnDefs: [
                        { "targets": [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12], "className": "text-center"},
                        { "targets": [13], "className": "text-right"}
                    ]
                }
            )

            $('#btnView').on('click', function(){
                tableTransaction.ajax.url("/tblTransaction?daterange=" + $('#trxDateTime').val() + "&selectedclientid=" + $('#clientId').val() +
                    "&searchcategory=" + $('#searchParameter').val() + "&searchkeyword=" + $('#searchKeyword').val()).load()
            })

            $('#btnExport').on('click', function (e){
                e.preventDefault()

                let btnExport = $('#btnExport')
                btnExport.attr('disabled', true)
                btnExport.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Exporting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('transactiondatetime', $('#trxDateTime').val())
                formData.append('selectedclientid', $('#clientId').val())
                formData.append('searchcategory', $('#searchParameter').val())
                formData.append('searchkeyword', $('#searchKeyword').val())

                $.ajax({
                    type: 'POST',
                    url: '/doexportreporttransaction',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data exporting is being processed.')
                        } else {
                            alert('Failed to export transaction data.')
                        }

                        btnExport.attr('disabled', false)
                        btnExport.html('Export')
                    },
                    fail: function(){
                        btnExport.attr('disabled', false)
                        btnExport.html('Export')

                        alert('Failed to export transaction data.')
                    },
                    error: function(){
                        btnExport.attr('disabled', false)
                        btnExport.html('Export')

                        alert('Failed to export transaction data.')
                    }
                })
            })
        })

        $("#clientId").select2()
    </script>
@endsection
