@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaction Summary - Parameter</h5>
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
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success" id="btnView"><i class="fa fa-envelope-o"></i> View</button>
                                </div>
                                <button type="reset" class="btn btn-danger" id="btnExport"><i class="fa fa-file"></i> Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaction Summary - Data</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="tableTransaction" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                            <tr>
                                <th style="text-align: center">Date</th>
                                <th style="text-align: center">Client ID</th>
                                <th style="text-align: center">Client Name</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Total Price</th>
                                <th style="text-align: center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="text-align: center">Date</th>
                                <th style="text-align: center">Client ID</th>
                                <th style="text-align: center">Client Name</th>
                                <th style="text-align: center">SMS Count</th>
                                <th style="text-align: center">Total Price</th>
                                <th style="text-align: center">Status</th>
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
        function getDateRange() {
            return $('#trxDateTime').val()
        }

        function getClientId() {
            return $('#clientId').val()
        }

        $(document).ready(function(){
            let tableTransaction = $('#tableTransaction').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax:{
                    'type': 'POST',
                    'url': '/tblSummaryTrx',
		    'timeout': '60000',
                    'data': function(x) {
                        x._token = '{{ csrf_token() }}'
                        x.daterange = getDateRange()
                        x.selectedclientid = getClientId()
                    }
                },
                columns: [
                    { data: 'day', name: 'day'},
                    { data: 'client_id', name: 'client_id'},
                    { data: 'client_name', name: 'client_name'},
                    { data: 'sms_count', name: 'sms_count'},
                    { data: 'price_total', name: 'price_total'},
                    { data: 'status_name', name: 'status_name'}
                    ],
                    columnDefs: [
                        { "targets": [0, 1, 5], "className": "text-center"},
                        { "targets": [3, 4], "className": "text-right"}
                    ]
                }
            )

            $('#btnView').on('click', function(){
                tableTransaction.ajax.reload()
            })

            $('#btnExport').on('click', function (e){
                e.preventDefault()
                console.log('Kesini laaaaaaaaaaahhhhhhh')
                let btnExport = $('#btnExport')
                btnExport.attr('disabled', true)
                btnExport.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Exporting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('selectedclientid', $('#clientId').val())

                $.ajax({
                    type: 'POST',
                    url: '/doexportreportsummarytransaction',
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
