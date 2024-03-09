@extends('layout.app')

@section('content')


    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    Routing Management
                    <small>Routing Table</small>
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Routing Management</a></li>
                    <li class="breadcrumb-item active">Routing Table </li>
                </ol>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h5 class="box-title">Routing Parameter</h5>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="mailbox-messages bg-white col-12">
                                        <form>
                                            <div class="form-group row">
                                                <label for="clientId" class="col-sm-4 col-form-label">Client</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2 w-p100" id="clientId">
                                                        <option value="ALL" selected>ALL</option>
                                                        @if(isset($clientData))
                                                            @foreach($clientData as $data)
                                                                <option value="{{ trim($data->client_id) }}">{{ trim($data->client_name) }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-info pull-right" id="btnViewRouting">View Routing Table</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h5 class="box-title">Routing Table</h5>
                            </div>
                            <div class="box-header pt-0 no-border">
                                <p class="box-title">Please reload the settings after you have finished making any changes</p>
                                <a data-toggle="modal" class="edit btn btn-primary btn-sm text-white" data-target="#modalToggleRouting">reload settings</a>
                            </div>


                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableRouting" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Client</th>
                                                <th style="text-align: center">Telecom</th>
                                                <th style="text-align: center">Active</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Client</th>
                                                <th style="text-align: center">Telecom</th>
                                                <th style="text-align: center">Active</th>
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

    <div class="modal fade" id="modalToggleRouting" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="box-title">Reload Settings</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        {{ csrf_field() }}
                        <input type="hidden" id="tuClientId">
                        <div class="form-group row">
                            <p for="tuClientName" class="text-center col-sm-12 col-form-label">To reload, please input "plsreload" in the box</p>
                        </div>

                        <div class="form-group row">
                            <input type="hidden" id="toggle-routing-id" value="">
                            <input type="hidden" id="toggle-routing-value" value="">
                            <label for="tuAdjustmentType" class="col-sm-4 col-form-label">input the text here</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="toggle-password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary float-right" id="btnToggle" disabled>Restart</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('jscript')
    <script>
        // add click event listener for every element with class 'toggle-routing'



        // add keyup event listener in id 'toggle-password'. if value == "plstoggle" then enable button with id 'btnToggle'
        $('#toggle-password').on('keyup', function(e) {
            e.preventDefault()

            let togglePassword = $('#toggle-password').val()
            let btnToggle = $('#btnToggle')

            if(togglePassword == "plsreload") {
                btnToggle.attr('disabled', false)
            } else {
                btnToggle.attr('disabled', true)
            }
        })

        // if button with id 'btnToggle' is clicked, then redirect to /restartservice
        $('#btnToggle').on('click', function(e) {
            e.preventDefault()
            window.location.href = "/restartservice"
        })

        function getClientId() {
            return $('#clientId').val()
        }

        function getSearchCategory() {
            return $('#searchField').val()
        }

        function getSearchKeyword() {
            return $('#searchKeyword').val()
        }

        $(document).ready( function() {
            var tableRouting = $('#tableRouting').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax:{
                    'type': 'POST',
                    'url': '/tblRouting',
                    'data': function(x) {
                        x._token = '{{ csrf_token() }}'
                        x.searchCategory = getSearchCategory()
                        x.searchKeyword = getSearchKeyword()
                        x.clientId = getClientId()
                    }
                },
                columns: [
                    { data: 'client_name', name: 'client_name'},
                    { data: 'telecom_name', name: 'telecom_name'},
                    { data: 'is_active', name: 'is_active'},
                    { data: 'action', name: 'action'}
                ],
                columnDefs: [
                    { "targets": [0, 1, 2, 3], "className": "text-center"},
                ]
                }
            )

            $(document).on('click', '.button-toggle', function(e) {
                e.preventDefault()

                let dataid = $(this).attr('data-id')
                let isActive = $(this).attr('data-editIsActive')

                let theurl = "/dotoggle/" + dataid + "/" + isActive

                //     do ajax to theurl
                $.ajax({
                    type: 'GET',
                    url: theurl,
                    success: function() {
                        // console.log(dataX)
                        // if (dataX === '0') {
                        //     alert('Data Routing Table is updated.')
                        // } else {
                        //     alert('Failed to update data Routing Table.')
                        // }

                        tableRouting.ajax.reload(null, false)
                    },
                    fail: function(){
                        // alert('Failed to update data Routing Table.')
                    },
                    error: function(){
                        // alert('Failed to update data Routing Table.')
                    }
                })
            })

            $('#btnViewRouting').on('click', function (e) {
                tableRouting.ajax.reload()
            })

            $('#newClientId').on('change', function(e) {
                e.preventDefault()

                let clientId = $('#newClientId').val()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientId', clientId)

                // User API
                $.ajax({
                    type: 'POST',
                    url: '/getapiusernamebyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        //console.log(dataX)

                        // Add dataX to select apiUsername
                        $('#newAPIUserName').empty()
                        for(let x = 0; x < dataX.length; x++) {
                            let dataUsername = dataX[x]['username']
                            if(x === 0) {
                                $('#newAPIUserName').append('<option value="' + dataUsername + '" selected>' + dataUsername + '</option>')
                            } else {
                                $('#newAPIUserName').append('<option value="' + dataUsername + '">' + dataUsername + '</option>')
                            }
                        }
                    }
                })

                // Client Sender ID
                $.ajax({
                    type: 'POST',
                    url: '/getclientsenderidbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataY) {
                        //console.log(dataY)

                        // Add dataX to select client sender id
                        $('#newClientSenderId').empty()
                        for(let x = 0; x < dataY.length; x++) {
                            let clientSenderIdId = dataY[x]['client_sender_id_id']
                            console.log(clientSenderIdId)
                            let clientSenderId = dataY[x]['sender_id']
                            console.log(clientSenderId)

                            if(x === 0) {
                                $('#newClientSenderId').append('<option value="' + clientSenderIdId + '" selected>' + clientSenderId + '</option>')
                            } else {
                                $('#newClientSenderId').append('<option value="' + clientSenderIdId + '">' + clientSenderId + '</option>')
                            }
                        }
                    }
                })

                // Client Currency
                $.ajax({
                    type: 'POST',
                    url: '/getclientpropbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataZ) {
                        //console.log(dataZ)

                        if(dataZ.length > 0) {
                            $('#newClientCurrency').val(dataZ[0]['currency_id'])
                        }
                    }
                })
            })

            $('#newVendorId').on('change', function(e) {
                e.preventDefault()

                let vendorId = $('#newVendorId').val()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorId', vendorId)

                // Get Vendor Sender ID by vendorID
                $.ajax({
                    type: 'POST',
                    url: '/getvendorsenderidbyvendorid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)

                        // Add dataX to select client sender id
                        $('#newVendorSenderId').empty()
                        for(let x = 0; x < dataX.length; x++) {
                            let vendorSenderIdId = dataX[x]['vendor_sender_id_id']
                            let vendorSenderId = dataX[x]['sender_id']

                            if(x === 0) {
                                $('#newVendorSenderId').append('<option value="' + vendorSenderIdId + '" selected>' + vendorSenderId + '</option>')
                            } else {
                                $('#newVendorSenderId').append('<option value="' + vendorSenderIdId + '">' + vendorSenderId + '</option>')
                            }
                        }
                    }
                })
            })

            $('#btnSaveNewRouting').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewRouting = $('#btnSaveNewRouting')
                let modalNewRouting = $('#modalNewRouting')

                let formData = new FormData()
                let clientId = $('#newClientId').val()
                let clientSenderIdId = $('#newClientSenderId').val()
                let apiUserName = $('#newAPIUserName').val()
                let telecomId = $('#newTelecomId').val()
                let clientPricePerSubmit = $('#newClientPricePerSubmit').val()
                let clientPricePerDelivery = $('#newClientPricePerDelivery').val()
                let clientCurrencyId = $('#newClientCurrency').val()
                let vendorId = $('#newVendorId').val()
                let vendorSenderIdId = $('#newVendorSenderId').val()
                let vendorParameter = $('#newVendorParameter').val()
                let vendorPricePerSubmit = $('#newVendorPricePerSubmit').val()
                let vendorPricePerDelivery = $('#newVendorPricePerDelivery').val()
                let voiceUnitSecond = $('#newVoiceUnit').val()
                let voicePricePerUnit = $('#newVoicePricePerUnit').val()
                let dlrType = $('#newDeliveryReportType').val()
                let chargeOnDlr = $('#newChargedDR').val()

                formData.append('clientId', clientId)
                formData.append('clientSenderIdId', clientSenderIdId)
                formData.append('apiUserName', apiUserName)
                formData.append('telecomId', telecomId)
                formData.append('clientPricePerSubmit', clientPricePerSubmit)
                formData.append('clientPricePerDelivery', clientPricePerDelivery)
                formData.append('clientCurrencyId', clientCurrencyId)
                formData.append('vendorId', vendorId)
                formData.append('vendorSenderIdId', vendorSenderIdId)
                formData.append('vendorParameter', vendorParameter)
                formData.append('vendorPricePerSubmit', vendorPricePerSubmit)
                formData.append('vendorPricePerDelivery', vendorPricePerDelivery)
                formData.append('voiceUnitSecond', voiceUnitSecond)
                formData.append('voicePricePerUnit', voicePricePerUnit)
                formData.append('dlrType', dlrType)
                formData.append('chargeOnDlr', chargeOnDlr)

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewroutingtable',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Routing Table is saved.')
                        } else {
                            alert('Failed to save data Routing Table.')
                        }

                        btnSaveNewRouting.attr('disabled', false)
                        modalNewRouting.modal('toggle')
                        btnSaveNewRouting.html('Save')

                        tableRouting.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewRouting.attr('disabled', false)
                        modalNewRouting.modal('toggle')
                        btnSaveNewRouting.html('Save')

                        alert('Failed to save data Table Routing.')
                    },
                    error: function(){
                        btnSaveNewRouting.attr('disabled', false)
                        modalNewRouting.modal('toggle')
                        btnSaveNewRouting.html('Save')

                        alert('Failed to save data Table Routing.')
                    }
                })
            })

            $('#editClientId').on('change', function(e) {
                e.preventDefault()

                let clientId = $('#editClientId').val()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientId', clientId)

                // User API
                $.ajax({
                    type: 'POST',
                    url: '/getapiusernamebyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        //console.log(dataX)

                        // Add dataX to select apiUsername
                        $('#editAPIUserName').empty()
                        for(let x = 0; x < dataX.length; x++) {
                            let dataUsername = dataX[x]['username']
                            if(x === 0) {
                                $('#editAPIUserName').append('<option value="' + dataUsername + '" selected>' + dataUsername + '</option>')
                            } else {
                                $('#editAPIUserName').append('<option value="' + dataUsername + '">' + dataUsername + '</option>')
                            }
                        }
                    }
                })

                // Client Sender ID
                $.ajax({
                    type: 'POST',
                    url: '/getclientsenderidbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataY) {
                        //console.log(dataY)

                        // Add dataX to select client sender id
                        $('#editClientSenderId').empty()
                        for(let x = 0; x < dataY.length; x++) {
                            let clientSenderIdId = dataY[x]['client_sender_id_id']
                            console.log(clientSenderIdId)
                            let clientSenderId = dataY[x]['sender_id']
                            console.log(clientSenderId)

                            //if(x === 0) {
                            //    $('#editClientSenderId').append('<option value="' + clientSenderIdId + '" selected>' + clientSenderId + '</option>')
                            //} else {
                            //    $('#editClientSenderId').append('<option value="' + clientSenderIdId + '">' + clientSenderId + '</option>')
                            //}
                        }
                    }
                })

                // Client Currency
                $.ajax({
                    type: 'POST',
                    url: '/getclientpropbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataZ) {
                        //console.log(dataZ)

                        if(dataZ.length > 0) {
                            $('#editClientCurrency').val(dataZ[0]['currency_id'])
                        }
                    }
                })
            })

            $('#editVendorId').on('change', function(e) {
                e.preventDefault()

                let vendorId = $('#editVendorId').val()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorId', vendorId)

                // Get Vendor Sender ID by vendorID
                $.ajax({
                    type: 'POST',
                    url: '/getvendorsenderidbyvendorid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)

                        // Add dataX to select client sender id
                        $('#editVendorSenderId').empty()
                        for(let x = 0; x < dataX.length; x++) {
                            let vendorSenderIdId = dataX[x]['vendor_sender_id_id']
                            let vendorSenderId = dataX[x]['sender_id']

                            if(x === 0) {
                                $('#editVendorSenderId').append('<option value="' + vendorSenderIdId + '" selected>' + vendorSenderId + '</option>')
                            } else {
                                $('#editVendorSenderId').append('<option value="' + vendorSenderIdId + '">' + vendorSenderId + '</option>')
                            }
                        }
                    }
                })
            })

            $('#modalEditRouting').on('show.bs.modal', function(e) {
                let button = $(e.relatedTarget)
                let routingId = button.attr('data-editRoutingId')

                // Get detail routing to edit
                let clientId = button.attr('data-editClientId')
                let clientSenderIdId = button.attr('data-editClientSenderIdId')
                let apiUserName = button.attr('data-editApiUserName')
                let telecomId = button.attr('data-editTelecomId')
                let clientPricePerSubmit = button.attr('data-editClientPricePerSubmit')
                let clientPricePerDelivery = button.attr('data-editClientPricePerDelivery')
                let clientCurrency = button.attr('data-editCurrencyId')
                let vendorId = button.attr('data-editVendorId')
                let vendorSenderIdId = button.attr('data-editVendorSenderIdId')
                let vendorParameter = button.attr('data-editVendorParameter')
                let vendorPricePerSubmit = button.attr('data-editVendorPricePerSubmit')
                let vendorPricePerDelivery = button.attr('data-editVendorPricePerDelivery')
                let voiceUnitSecond = button.attr('data-editVoiceUnitSecond')
                let voicePricePerUnit = button.attr('data-editVoicePricePerUnit')
                let fakeDR = button.attr('data-editFakeDR')
                let isChargedPerDR = button.attr('data-editIsChargedPerDR')
                //let isActive = button.attr('data-editIsActive')

                console.log('clientSenderIdId: ' + clientSenderIdId)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientId', clientId)

                // User API
                $.ajax({
                    type: 'POST',
                    url: '/getapiusernamebyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        //console.log(dataX)

                        // Add dataX to select apiUsername
                        $('#editAPIUserName').empty()
                        for(let x = 0; x < dataX.length; x++) {
                            let dataUsername = dataX[x]['username']

                            $('#editAPIUserName').append('<option value="' + dataUsername + '">' + dataUsername + '</option>')
                        }

                        $('#editAPIUserName').val(apiUserName)
                    }
                })

                // Client Sender ID
                $.ajax({
                    type: 'POST',
                    url: '/getclientsenderidbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataY) {
                        //console.log(dataY)

                        // Add dataX to select client sender id
                        $('#editClientSenderId').empty()
                        for(let x = 0; x < dataY.length; x++) {
                            let clientSenderIdId = dataY[x]['client_sender_id_id']
                            console.log(clientSenderIdId)
                            let clientSenderId = dataY[x]['sender_id']
                            console.log(clientSenderId)

                            //if(x === 0) {
                            //    $('#editClientSenderId').append('<option value="' + clientSenderIdId + '" selected>' + clientSenderId + '</option>')
                            //} else {
                                $('#editClientSenderId').append('<option value="' + clientSenderIdId + '">' + clientSenderId + '</option>')
                            //}
                        }

                        $('#editClientSenderId').val(clientSenderIdId)
                    }
                })

                // Client Currency
                $.ajax({
                    type: 'POST',
                    url: '/getclientpropbyclientid',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataZ) {
                        //console.log(dataZ)

                        if(dataZ.length > 0) {
                            $('#editClientCurrency').val(dataZ[0]['currency_id'])
                        }

                        $('#editClientCurrency').val(clientCurrency)
                    }
                })

                $('#editRoutingId').val(routingId)
                $('#editClientId').val(clientId)


                $('#editTelecomId').val(telecomId)
                $('#editClientPricePerSubmit').val(clientPricePerSubmit)
                $('#editClientPricePerDelivery').val(clientPricePerDelivery)

                $('#editVendorId').val(vendorId)
                $('#editVendorSenderId').val(vendorSenderIdId)
                $('#editVendorParameter').val(vendorParameter)
                $('#editVendorPricePerSubmit').val(vendorPricePerSubmit)
                $('#editVendorPricePerDelivery').val(vendorPricePerDelivery)
                $('#editVoiceUnit').val(voiceUnitSecond)
                $('#editVoicePricePerUnit').val(voicePricePerUnit)

                if (fakeDR == 'false') {
                    $('#editDeliveryReportType').val('VENDOR')
                } else {
                    $('#editDeliveryReportType').val('AUTOGEN')
                }

                if (isChargedPerDR == 'false') {
                    $('#editChargedDR').val('false')
                } else {
                    $('#editChargedDR').val('true')
                }
            })

            $('#btnSaveEditRouting').on('click', function(e) {
                let btnSaveEditRouting = $('#btnSaveEditRouting')
                let modalEditRouting = $('#modalEditRouting')

                let formData = new FormData()
                let routingId = $('#editRoutingId').val()
                let clientId = $('#editClientId').val()
                let clientSenderIdId = $('#editClientSenderId').val()
                let apiUserName = $('#editAPIUserName').val()
                let telecomId = $('#editTelecomId').val()
                let clientPricePerSubmit = $('#editClientPricePerSubmit').val()
                let clientPricePerDelivery = $('#editClientPricePerDelivery').val()
                let clientCurrencyId = $('#editClientCurrency').val()
                let vendorId = $('#editVendorId').val()
                let vendorSenderIdId = $('#editVendorSenderId').val()
                let vendorParameter = $('#editVendorParameter').val()
                let vendorPricePerSubmit = $('#editVendorPricePerSubmit').val()
                let vendorPricePerDelivery = $('#editVendorPricePerDelivery').val()
                let voiceUnitSecond = $('#editVoiceUnit').val()
                let voicePricePerUnit = $('#editVoicePricePerUnit').val()
                let dlrType = $('#editDeliveryReportType').val()
                let chargeOnDlr = $('#editChargedDR').val()

                formData.append('routingId', routingId)
                formData.append('clientId', clientId)
                formData.append('clientSenderIdId', clientSenderIdId)
                formData.append('apiUserName', apiUserName)
                formData.append('telecomId', telecomId)
                formData.append('clientPricePerSubmit', clientPricePerSubmit)
                formData.append('clientPricePerDelivery', clientPricePerDelivery)
                formData.append('clientCurrencyId', clientCurrencyId)
                formData.append('vendorId', vendorId)
                formData.append('vendorSenderIdId', vendorSenderIdId)
                formData.append('vendorParameter', vendorParameter)
                formData.append('vendorPricePerSubmit', vendorPricePerSubmit)
                formData.append('vendorPricePerDelivery', vendorPricePerDelivery)
                formData.append('voiceUnitSecond', voiceUnitSecond)
                formData.append('voicePricePerUnit', voicePricePerUnit)
                formData.append('dlrType', dlrType)
                formData.append('chargeOnDlr', chargeOnDlr)

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditroutingtable',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Routing Table is saved.')
                        } else {
                            alert('Failed to save data Routing Table.')
                        }

                        btnSaveEditRouting.attr('disabled', false)
                        modalEditRouting.modal('toggle')
                        btnSaveEditRouting.html('Save')

                        tableRouting.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveEditRouting.attr('disabled', false)
                        modalEditRouting.modal('toggle')
                        btnSaveEditRouting.html('Save')

                        alert('Failed to save data Table Routing.')
                    },
                    error: function(){
                        btnSaveEditRouting.attr('disabled', false)
                        modalEditRouting.modal('toggle')
                        btnSaveEditRouting.html('Save')

                        alert('Failed to save data Table Routing.')
                    }
                })

            })

            $('#modalDelRouting').on('show.bs.modal', function(e) {
                let button = $(e.relatedTarget)
                let routingId = button.attr('data-delRoutingId')
                let clientName = button.attr('data-delClientName')
                let clientSenderId = button.attr('data-delClientSenderId')

                $('#delRoutingId').val(routingId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete routing table for client <span style="color:red">' +
                    clientName + '</span> and sender ID <span style="color:red">' + clientSenderId + '</span>? <br><br>Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteRouting').on('click', function (e) {
                let btnDeleteRouting = $('#btnDeleteRouting')
                let modalDeleteRouting = $('#modalDelRouting')

                btnDeleteRouting.attr('disabled', true)
                btnDeleteRouting.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('routingId', $('#delRoutingId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeleteroutingtable',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Routing Table is deleted.')
                        } else {
                            alert('Failed to delete data Routing Table.')
                        }

                        btnDeleteRouting.attr('disabled', false)
                        modalDeleteRouting.modal('toggle')
                        btnDeleteRouting.html('Delete')

                        tableRouting.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Routing Table.')
                        btnDeleteRouting.attr('disabled', false)
                        modalDeleteRouting.modal('toggle')
                        btnDeleteRouting.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data Routing Table.')
                        btnDeleteRouting.attr('disabled', false)
                        modalDeleteRouting.modal('toggle')
                        btnDeleteRouting.html('Delete')
                    }
                })
            })

            function initiateThings() {
                $('#newClientId').change()
                $('#newVendorId').change()

                $('#editClientId').change()
                $('#editVendorId').change()
            }

            initiateThings()
        })

        $("#clientId").select2()
    </script>
@endsection
