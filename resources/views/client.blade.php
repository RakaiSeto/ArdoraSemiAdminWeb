@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
{{--            <div class="content-header">--}}
{{--                <h3>--}}
{{--                    Client Management--}}
{{--                    <small>Clients</small>--}}
{{--                </h3>--}}
{{--                <ol class="breadcrumb">--}}
{{--                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Client Management</a></li>--}}
{{--                    <li class="breadcrumb-item active">Clients </li>--}}
{{--                </ol>--}}
{{--            </div>--}}

            <!-- Modal new client -->
            <div class="modal center-modal fade" id="modalNewClient" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Client</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newClientName" class="col-sm-4 col-form-label">Client Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newClientName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientCountry" class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newClientCountry">
                                                @if(isset($countryData))
                                                    <?php $count = 0; ?>
                                                    @foreach($countryData as $data)
                                                        @if($count == 0)
                                                            <option value="{{trim($data->country_id)}}" selected>{{ $data->country_name }}</option>
                                                        @else
                                                            <option value="{{trim($data->country_id)}}">{{ $data->country_name }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientIsReseller" class="col-sm-4 col-form-label">Reseller Client</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newClientIsReseller">
                                                <option value="YES" selected>YES</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientBusinessModel" class="col-sm-4 col-form-label">Business Model</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newClientBusinessModel">
                                                <option value="PREPAID" selected>PREPAID</option>
                                                <option value="POSTPAID">POSTPAID</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newClientCurrency" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newClientCurrency">
                                                @if(isset($clientCurrencyData))
                                                    <?php $count = 0; ?>
                                                    @foreach($clientCurrencyData as $currencyData)
                                                        @if($count == 0)
                                                            <option value="{{trim($currencyData->currency_id)}}" selected>{{ $currencyData->currency_name }}</option>
                                                        @else
                                                            <option value="{{trim($currencyData->currency_id)}}">{{ $currencyData->currency_name }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @if(\Auth::user()->privilege === 'ROOT')
                                        <div class="form-group row">
                                            <label for="newClientGroupId" class="col-sm-4 col-form-label">Client Group</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2 w-p100" id="newClientGroupId">
                                                    @if(isset($clientGroupData))
                                                        <?php $count = 0; ?>
                                                        @foreach($clientGroupData as $groupData)
                                                            @if($count == 0)
                                                                <option value="{{ trim($groupData->group_id) }}" selected>{{ $groupData->group_name }}</option>
                                                            @else
                                                                <option value="{{ trim($groupData->group_id) }}">{{ $groupData->group_name }}</option>
                                                            @endif
                                                            <?php $count = $count + 1; ?>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewClient">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit provide group -->
            <div class="modal center-modal fade" id="modalEditClient" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Client</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editClientId">
                                    <div class="form-group row">
                                        <label for="editClientName" class="col-sm-4 col-form-label">Client Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editClientName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editClientCountry" class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editClientCountry">
                                                @if(isset($countryData))
                                                    <?php $count = 0; ?>
                                                    @foreach($countryData as $data)
                                                        @if($count == 0)
                                                            <option value="{{trim($data->country_id)}}" selected>{{ $data->country_name }}</option>
                                                        @else
                                                            <option value="{{trim($data->country_id)}}">{{ $data->country_name }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editClientBusinessModel" class="col-sm-4 col-form-label">Business Model</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editClientBusinessModel">
                                                <option value="PREPAID" selected>PREPAID</option>
                                                <option value="POSTPAID">POSTPAID</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editClientCurrency" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editClientCurrency">
                                                @if(isset($clientCurrencyData))
                                                    <?php $count = 0; ?>
                                                    @foreach($clientCurrencyData as $currencyData)
                                                        @if($count == 0)
                                                            <option value="{{trim($currencyData->currency_id)}}" selected>{{ $currencyData->currency_name }}</option>
                                                        @else
                                                            <option value="{{trim($currencyData->currency_id)}}">{{ $currencyData->currency_name }}</option>
                                                        @endif
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @if(\Auth::user()->privilege === 'ROOT')
                                        <div class="form-group row">
                                            <label for="editClientGroupId" class="col-sm-4 col-form-label">Client Group</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2 w-p100" id="editClientGroupId">
                                                    @if(isset($clientGroupData))
                                                        <?php $count = 0; ?>
                                                        @foreach($clientGroupData as $groupData)
                                                            @if($count == 0)
                                                                <option value="{{ trim($groupData->group_id) }}" selected>{{ $groupData->group_name }}</option>
                                                            @else
                                                                <option value="{{ trim($groupData->group_id) }}">{{ $groupData->group_name }}</option>
                                                            @endif
                                                            <?php $count = $count + 1; ?>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditClient">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete provide group -->
            <div class="modal center-modal fade" id="modalDelClient" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Client</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delClientId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteClient">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal center-modal fade" id="modalFindClient" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Find Client</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="findClientField" class="col-sm-4 col-form-label">Find Field</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="findClientField">
                                                <option value="clientId" selected>Client ID</option>
                                                <option value="clientName">Client Name</option>
                                                <option value="clientCountry">Client Country</option>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <option value="clientGroupName">Client Group</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="findClientKeyword" class="col-sm-4 col-form-label">Find Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="findClientKeyword">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnFindClient" data-dismiss="modal">Find</button>
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
                                <h3 class="box-title">Client Management</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewClient" data-toggle="modal" data-target="#modalNewClient">New Client</button>
                                    <button type="button" class="btn btn-info btn-sm" id="btnFindClient" data-toggle="modal" data-target="#modalFindClient">Find Client</button>                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableClient" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Client ID</th>
                                                <th style="text-align: center">Client Name</th>
                                                <th style="text-align: center">Country Name</th>
                                                <th style="text-align: center">Currency</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Active</th>
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Client ID</th>
                                                <th style="text-align: center">Client Name</th>
                                                <th style="text-align: center">Country Name</th>
                                                <th style="text-align: center">Currency</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
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
@endsection

@section('jscript')
    <script>
        $(document).ready( function() {
            let tableClient = $('#tableClient').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblClient",
                    columns: [
                        @if(Auth::user()->privilege === 'ROOT')
                            { data: 'client_id', name: 'Client ID'},
                            { data: 'client_name', name: 'Client Name'},
                            { data: 'country_name', name: 'Country Name'},
                            { data: 'currency_id', name: 'Currency'},
                            { data: 'group_name', name: 'Group'},
                            { data: 'is_active', name: 'Active'},
                            { data: 'action', name: 'Action'}
                        @else
                            { data: 'client_id', name: 'Client ID'},
                            { data: 'client_name', name: 'Client Name'},
                            { data: 'country_name', name: 'Country Name'},
                            { data: 'currency_id', name: 'Currency'},
                            { data: 'is_active', name: 'Active'},
                            { data: 'action', name: 'Action'}
                        @endif
                    ],
                    columnDefs: [
                        { "targets": [0, 2, 3, 4], "className": "text-center"}
                    ]
                }
            )

            $('#btnFindClient').on('click', function() {
                tableClient.ajax.url("/tblClient?searchcategory=" + $('#findClientField').val() + "&searchkeyword=" + $('#findClientKeyword').val()).load()
            })

            $('#modalFindClient').on('show.bs.modal', function () {
                $('#findClientField').prop("selectedIndex", 0)
                $('#findClientKeyword').val('')
            })

            $('#modalNewClient').on('show.bs.modal', function() {
                $('#newClientName').val('')
                $('#newClientDescription').val('')
                $('#newClientBusinessModel').val('PREPAID')

            })

            $('#btnSaveNewClient').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewClient = $('#btnSaveNewClient')
                btnSaveNewClient.attr('disabled', true)
                btnSaveNewClient.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewClient = $('#modalNewClient')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientname', $('#newClientName').val())
                formData.append('clientisreseller', $('#newClientIsReseller').val())
                formData.append('clientbusinessmodel', $('#newClientBusinessModel').val())
                formData.append('clientcountryid', $('#newClientCountry').val())
                formData.append('clientcurrency', $('#newClientCurrency').val())
                formData.append('clientgroupid', $('#newClientGroupId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewclient',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client is saved.')
                        } else {
                            alert('Failed to save data client.')
                        }

                        btnSaveNewClient.attr('disabled', false)
                        modalNewClient.modal('toggle')
                        btnSaveNewClient.html('Save')

                        tableClient.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewClient.attr('disabled', false)
                        modalNewClient.modal('toggle')
                        btnSaveNewClient.html('Save')

                        alert('Failed to save data client group.')
                    },
                    error: function(){
                        btnSaveNewClient.attr('disabled', false)
                        modalNewClient.modal('toggle')
                        btnSaveNewClient.html('Save')

                        alert('Failed to save data client.')
                    }
                })
            })

            $('#modalEditClient').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let clientId = button.attr('data-clientId')
                let clientName = button.attr('data-clientName')
                let clientCountry = button.attr('data-clientCountryId')

                $('#editClientId').val(clientId)
                $('#editClientName').val(clientName)
                $('#editClientCountry').val(clientCountry)

                @if(Auth::user()->privilege === 'ROOT')
                    let groupId = button.attr('data-clientGroupId')
                    $('#editClientGroupId').val(groupId)
                @endif
            })

            $('#btnSaveEditClient').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditClient = $('#btnSaveEditClient')
                btnSaveEditClient.attr('disabled', true)
                btnSaveEditClient.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditClient = $('#modalEditClient')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientid', $('#editClientId').val())
                formData.append('clientname', $('#editClientName').val())
                formData.append('clientcountryid', $('#editClientCountry').val())
                formData.append('clientcurrency', $('#editClientCurrency').val())
                @if(Auth::user()->privilege === 'ROOT')
                    formData.append('clientgroupid', $('#editClientGroupId').val())
                @endif

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditclient',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client is updated.')
                        } else {
                            alert('Failed to update data client.')
                        }

                        btnSaveEditClient.attr('disabled', false)
                        modalEditClient.modal('toggle')
                        btnSaveEditClient.html('Save')

                        tableClient.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditClient.attr('disabled', false)
                        modalEditClient.modal('toggle')
                        btnSaveEditClient.html('Save')

                        alert('Failed to update data client.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditClient.attr('disabled', false)
                        modalEditClient.modal('toggle')
                        btnSaveEditClient.html('Save')

                        alert('Failed to update data client.')
                    }
                })
            })

            $('#modalDelClient').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let clientId = button.attr('data-clientId')
                let clientName = button.attr('data-clientName')

                $('#delClientId').val(clientId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete client <span style="color:red">' + clientName + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteClient').on('click', function() {
                let btnDeleteClient = $('#btnDeleteClient')
                let modalDeleteClient = $('#modalDelClient')

                btnDeleteClient.attr('disabled', true)
                btnDeleteClient.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientid', $('#delClientId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeleteclient',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data client is deleted.')
                        } else {
                            alert('Failed to delete data client.')
                        }

                        btnDeleteClient.attr('disabled', false)
                        modalDeleteClient.modal('toggle')
                        btnDeleteClient.html('Delete')

                        tableClient.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data client.')
                        btnDeleteClient.attr('disabled', false)
                        modalDeleteClient.modal('toggle')
                        btnDeleteClient.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data client.')
                        btnDeleteClient.attr('disabled', false)
                        modalDeleteClient.modal('toggle')
                        btnDeleteClient.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
