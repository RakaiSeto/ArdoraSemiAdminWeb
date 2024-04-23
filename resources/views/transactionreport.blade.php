@extends('layout.app')

@section('content')
    <!-- Modal new client -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaction Report - Parameter</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="form-group row">
                                    <label for="trxDateTime" class="col-sm-4 col-form-label">Transaction Date Range</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="trxDateTime">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="clientId" class="col-sm-4 col-form-label">Client</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 w-p100" id="clientId">
                                            <option value="ALL" selected>ALL</option>
                                            @if(isset($clientData))
                                                <?php $count = 0; ?>
                                                @foreach($clientData as $data)
                                                    @if($count == 0)
                                                        <option value="{{trim($data->client_id)}}">{{ $data->client_name }}</option>
                                                    @else
                                                        <option value="{{trim($data->client_id)}}">{{ $data->client_name }}</option>
                                                    @endif
                                                    <?php $count = $count + 1; ?>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="searchParameter" class="col-sm-4 col-form-label">Search Field/Category</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="searchParameter">
                                            <option selected value="messageid">Message ID</option>
                                            <option value="batchid">Batch ID</option>
                                            <option value="clientsenderid">A# or Sender ID</option>
                                            <option value="msisdn">B# or MSISDN</option>
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
                                <div class="pull-left">
                                    <button type="button" class="btn btn-primary" id="btnExport"><i class="fa fa-download"></i> Export</button>
                                </div>
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
                <h5 class="card-title mb-0">Transaction Report - Data</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="tableTransaction" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                            <tr>
                                <th style="text-align: center">Date Time</th>
                                <th style="text-align: center">Client</th>
                                <th style="text-align: center">Batch ID</th>
                                <th style="text-align: center">Message ID</th>
                                <th style="text-align: center">Sender ID</th>
                                <th style="text-align: center">MSISDN</th>
                                <th style="text-align: center">Message</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Country</th>
                                <th style="text-align: center">Telecom</th>
                                <th style="text-align: center">Encoding</th>
                                <th style="text-align: center">Length</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Balance Before</th>
                                <th style="text-align: center">Price</th>
                                <th style="text-align: center">Balance After</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="text-align: center">Date Time</th>
                                <th style="text-align: center">Client</th>
                                <th style="text-align: center">Batch ID</th>
                                <th style="text-align: center">Message ID</th>
                                <th style="text-align: center">Sender ID</th>
                                <th style="text-align: center">MSISDN</th>
                                <th style="text-align: center">Message</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Country</th>
                                <th style="text-align: center">Telecom</th>
                                <th style="text-align: center">Encoding</th>
                                <th style="text-align: center">Length</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Balance Before</th>
                                <th style="text-align: center">Price</th>
                                <th style="text-align: center">Balance After</th>
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
        function getTrxDateTime() {
            return $('#trxDateTime').val()
        }

        function getSelectedClientId() {
            return $('#clientId').val()
        }

        function getSearchCategory() {
            return $('#searchParameter').val()
        }

        function getSearchKeyword() {
            return $('#searchKeyword').val()
        }

        $(document).ready(function(){
            let tableTransaction = $('#tableTransaction').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    pageLength: 200,
                    lengthMenu: [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                    ajax: {
                        'type': 'POST',
                        'url': '/tblTransaction',
                        'data': function (x) {
                            x._token = '{{ csrf_token() }}'
                            x.daterange = getTrxDateTime()
                            x.clientid = getSelectedClientId()
                            x.searchcategory = getSearchCategory()
                            x.searchkeyword = getSearchKeyword()
                        }
                    },
                    order: [[1, 'desc']],
                    columns: [
                        { data: 'transaction_date', name: 'transaction_date'},
                        { data: 'client_name', name: 'client_name'},
                        { data: 'batch_id', name: 'batch_id'},
                        { data: 'message_id', name: 'message_id'},
                        { data: 'client_sender_id', name: 'client_sender_id'},
                        { data: 'msisdn', name: 'msisdn'},
                        { data: 'message', name: 'message'},
                        { data: 'description', name: 'description'},
                        { data: 'country_name', name: 'country_name'},
                        { data: 'telecom_name', name: 'telecom_name'},
                        { data: 'message_encodng', name: 'message_encodng'},
                        { data: 'message_length', name: 'message_length'},
                        { data: 'sms_count', name: 'sms_count'},
                        { data: 'previous_balance', name: 'previous_balance'},
                        { data: 'client_price_total', name: 'client_price_total'},
                        { data: 'after_balance', name: 'after_balance'},
                    ],
                    columnDefs: [
                        { "targets": [0, 1, 2, 3, 4, 5, 7, 8, 9, 15], "className": "text-center"},
                        { "targets": [10, 11, 12, 13, 14], "className": "text-right"}
                    ]
                }
            )

            $('#btnView').on('click', function(){
                tableTransaction.ajax.reload()
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

            $('#modalDetailTransaction').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let messageId = button.attr('data-messageId')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('messageid', messageId)
                formData.append('daterange', $('#trxDateTime').val())

                $.ajax({
                    type: 'POST',
                    url: '/getdetailtransaction',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        //console.log(dataX)

                        let jsonData = JSON.parse(dataX)
                        if (Object.keys(jsonData).length > 0) {
                            let theData = jsonData[0]
                            console.log(theData)

                            $('#detDateTime').val(theData['transaction_date'])
                            $('#detBatchID').val(theData['batch_id'])
                            $('#detMessageID').val(theData['message_id'])
                            $('#detStatus').val('(' + theData['status_code'].trim() + ') ' + theData['status_name'])
                            $('#detClient').val('(' + theData['client_id'].trim() + ') ' + theData['client_name'])
                            $('#detCountry').val(theData['country_code'])
                            $('#detTelecom').val(theData['telecom_name'])
                            $('#detEncoding').val(theData['message_encodng'])
                            $('#detLength').val(theData['message_length'])
                            $('#detCount').val(theData['sms_count'])

                            $('#detBalanceBefore').val(theData['previous_balance'])
                            $('#detPrice').val(theData['usage'])
                            $('#detBalanceAfter').val(theData['after_balance'])

                            $('#detReceiverAPIUsername').val(theData['api_username'])
                            $('#detReceiverDateTime').val(theData['receiver_date_time'])
                            $('#detReceiverData').val(theData['receiver_data'])
                            $('#detReceiverRespDateTime').val(theData['receiver_client_response_date_time'])
                            $('#detReceiverClientResponse').val(theData['receiver_client_response'])
                            $('#detReceiverClientIPAddress').val(theData['client_ip_address'])

                            $('#detDLRDateTime').val(theData['dlr_date_time'])
                            $('#detDLRBody').val(theData['dlr_body'])
                            $('#detDLRStatus').val(theData['dlr_status'])
                            $('#detDLRPushTo').val(theData['dlr_push_to'])
                            $('#detDLRClientResponse').val(theData['dlr_client_push_response'])

                            $('#detVendor').val('(' + theData['vendor_id'].trim() + ') ' + theData['vendor_name'])
                            $('#detVendorReqDateTime').val(theData['vendor_hit_date_time'])
                            $('#detVendorRequest').val(theData['vendor_hit_request'])
                            $('#detVendorRespDateTime').val(theData['vendor_hit_resp_date_time'])
                            $('#detVendorResponse').val(theData['vendor_hit_response'])
                            $('#detVendorMessageId').val(theData['vendor_message_id'])

                            $('#detVendorCBDateTime').val(theData['vendor_callback_date_time'])
                            $('#detVendorCallback').val(theData['vendor_callback'])
                            $('#detVendorTrxStatus').val(theData['vendor_trx_status'])

                        }
                    },
                    fail: function(){
                        alert('Failed to get detail transaction data.')
                    },
                    error: function(){
                        alert('Failed to get detail transaction data.')
                    }
                })
            })
        })

        $("#clientId").select2()
    </script>
@endsection
