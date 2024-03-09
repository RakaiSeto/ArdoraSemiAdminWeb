@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <h3>
                    Vendor Management
                    <small>Vendors</small>
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Vendor Management</a></li>
                    <li class="breadcrumb-item active">Vendors </li>
                </ol>
            </div>

            <!-- Modal new vendor -->
            <div class="modal center-modal fade" id="modalNewVendor" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Vendor</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newVendorName" class="col-sm-4 col-form-label">Vendor Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorCity" class="col-sm-4 col-form-label">City</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorCity">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorCountry" class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newVendorCountry">
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
                                        <label for="newPIC" class="col-sm-4 col-form-label">Person In Charge (PIC)</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorPIC">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newPICPhoneNumber" class="col-sm-4 col-form-label">PIC Phone Number</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newPICPhoneNumber">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newPICEmail" class="col-sm-4 col-form-label">PIC Email</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newPICEmail">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorCurrency" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="newVendorCurrency">
                                                @if(isset($vendorCurrencyData))
                                                    <?php $count = 0; ?>
                                                    @foreach($vendorCurrencyData as $currencyData)
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
                                    <div class="form-group row">
                                        <label for="newVendorQueue" class="col-sm-4 col-form-label">Queue Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newVendorQueue">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="newVendorTPS" class="col-sm-4 col-form-label">Transactions Per Second</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="number" id="newVendorTPS">
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
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewVendor">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit vendor -->
            <div class="modal center-modal fade" id="modalEditVendor" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Vendor</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editVendorId">
                                    <div class="form-group row">
                                        <label for="editVendorName" class="col-sm-4 col-form-label">Vendor Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorName">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorCity" class="col-sm-4 col-form-label">City</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorCity">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorCountry" class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editVendorCountry">
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
                                        <label for="editPIC" class="col-sm-4 col-form-label">Person In Charge (PIC)</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorPIC">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editPICPhoneNumber" class="col-sm-4 col-form-label">PIC Phone Number</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editPICPhoneNumber">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editPICEmail" class="col-sm-4 col-form-label">PIC Email</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editPICEmail">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorCurrency" class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="editVendorCurrency">
                                                @if(isset($vendorCurrencyData))
                                                    <?php $count = 0; ?>
                                                    @foreach($vendorCurrencyData as $currencyData)
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
                                    <div class="form-group row">
                                        <label for="editVendorQueue" class="col-sm-4 col-form-label">Queue Name</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editVendorQueue">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="editVendorTPS" class="col-sm-4 col-form-label">Transactions Per Second</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="number" id="editVendorTPS">
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
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditVendor">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete vendor -->
            <div class="modal center-modal fade" id="modalDelVendor" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Vendor</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="delVendorId">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteVendor">Delete</button>
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
                                <h3 class="box-title">Vendor Management</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <button type="button" class="btn btn-info btn-sm" id="btnNewVendor" data-toggle="modal" data-target="#modalNewVendor">New Vendor</button>
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableVendor" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Vendor ID</th>
                                                <th style="text-align: center">Vendor Name</th>
                                                <th style="text-align: center">City</th>
                                                <th style="text-align: center">Country</th>
                                                <th style="text-align: center">Queue Name</th>
                                                <th style="text-align: center">TPS</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
                                                <th style="text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Vendor ID</th>
                                                <th style="text-align: center">Vendor Name</th>
                                                <th style="text-align: center">City</th>
                                                <th style="text-align: center">Country</th>
                                                <th style="text-align: center">Queue Name</th>
                                                <th style="text-align: center">TPS</th>
                                                @if(Auth::user()->privilege === 'ROOT')
                                                    <th style="text-align: center">Group</th>
                                                @endif
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
            let tableVendor = $('#tableVendor').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: "/tblVendor",
                    columns: [
                        @if(Auth::user()->privilege === 'ROOT')
                            { data: 'vendor_id', name: 'Client ID'},
                            { data: 'vendor_name', name: 'Client Name'},
                            { data: 'vendor_city', name: 'Vendor City'},
                            { data: 'country_name', name: 'Country Name'},
                            { data: 'queue_name', name: 'Queue Name'},
                            { data: 'vendor_tps', name: 'TPS'},
                            { data: 'group_name', name: 'Group'},
                            { data: 'action', name: 'Action'}
                        @else
                            { data: 'vendor_id', name: 'Client ID'},
                            { data: 'vendor_name', name: 'Client Name'},
                            { data: 'vendor_city', name: 'Vendor City'},
                            { data: 'country_name', name: 'Country Name'},
                            { data: 'queue_name', name: 'Queue Name'},
                            { data: 'vendor_tps', name: 'TPS'},
                            { data: 'action', name: 'Action'}
                        @endif
                    ],
                    columnDefs: [
                        { "targets": "_all", "className": "text-center"}
                    ]
                }
            )

            $('#modalNewVendor').on('show.bs.modal', function() {
                $('#newVendorName').val('')
                $('#newVendorCity').val('')
                $('#newVendorPIC').val('')
                $('#newPICPhoneNumber').val('')
                $('#newPICEmail').val('')
                $('#newVendorQueue').val('')
                $('#newVendorTPS').val('')
            })

            $('#btnSaveNewVendor').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewVendor = $('#btnSaveNewVendor')
                btnSaveNewVendor.attr('disabled', true)
                btnSaveNewVendor.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewVendor = $('#modalNewVendor')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorname', $('#newVendorName').val())
                formData.append('vendorcity', $('#newVendorCity').val())
                formData.append('vendorcountry', $('#newVendorCountry').val())
                formData.append('vendorpic', $('#newVendorPIC').val())
                formData.append('vendorpicphonenumber', $('#newPICPhoneNumber').val())
                formData.append('vendorpicemail', $('#newPICEmail').val())
                formData.append('vendorcurrency', $('#newVendorCurrency').val())
                formData.append('vendorqueuename', $('#newVendorQueue').val())
                formData.append('vendortps', $('#newVendorTPS').val())
                formData.append('vendorclientgroup', $('#newClientGroupId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dosavenewvendor',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data Vendor is saved.')
                        } else {
                            alert('Failed to save data Vendor.')
                        }

                        btnSaveNewVendor.attr('disabled', false)
                        modalNewVendor.modal('toggle')
                        btnSaveNewVendor.html('Save')

                        tableVendor.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewVendor.attr('disabled', false)
                        modalNewVendor.modal('toggle')
                        btnSaveNewVendor.html('Save')

                        alert('Failed to save data Vendor.')
                    },
                    error: function(){
                        btnSaveNewVendor.attr('disabled', false)
                        modalNewVendor.modal('toggle')
                        btnSaveNewVendor.html('Save')

                        alert('Failed to save data Vendor.')
                    }
                })
            })

            $('#modalEditVendor').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let vendorId = button.attr('data-editVendorId')
                let vendorName = button.attr('data-editVendorName')
                let vendorCity = button.attr('data-editVendorCity')
                let vendorCountry = button.attr('data-editVendorCountry')
                let vendorPIC = button.attr('data-editVendorPIC')
                let vendorPICPhoneNumber = button.attr('data-editVendorPICPhoneNumber')
                let vendorPICEmail = button.attr('data-editVendorPICEmail')
                let vendorCurrencyId = button.attr('data-editVendorCurrencyId')
                let vendorQueueName = button.attr('data-editVendorQueueName')
                let vendorTPS = button.attr('data-editVendorTPS')

                @if (Auth::user()->privilege === 'ROOT')
                    let vendorClientGroupId = button.attr('data-editVendorClientGroupId')
                @endif

                $('#editVendorId').val(vendorId)
                $('#editVendorName').val(vendorName)
                $('#editVendorCity').val(vendorCity)
                $('#editVendorCountry').val(vendorCountry)
                $('#editVendorPIC').val(vendorPIC)
                $('#editPICPhoneNumber').val(vendorPICPhoneNumber)
                $('#editPICEmail').val(vendorPICEmail)
                $('#editVendorCurrency').val(vendorCurrencyId)
                $('#editVendorQueue').val(vendorQueueName)
                $('#editVendorTPS').val(vendorTPS)

                @if (Auth::user()->privilege === 'ROOT')
                    $('#editClientGroupId').val(vendorClientGroupId)
                @endif
            })

            $('#btnSaveEditVendor').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditVendor = $('#btnSaveEditVendor')
                btnSaveEditVendor.attr('disabled', true)
                btnSaveEditVendor.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditVendor = $('#modalEditVendor')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorid', $('#editVendorId').val())
                formData.append('vendorname', $('#editVendorName').val())
                formData.append('vendorcity', $('#editVendorCity').val())
                formData.append('vendorcountry', $('#editVendorCountry').val())
                formData.append('vendorpic', $('#editVendorPIC').val())
                formData.append('vendorpicphonenumber', $('#editPICPhoneNumber').val())
                formData.append('vendorpicemail', $('#editPICEmail').val())
                formData.append('vendorcurrency', $('#editVendorCurrency').val())
                formData.append('vendorqueuename', $('#editVendorQueue').val())
                formData.append('vendortps', $('#editVendorTPS').val())
                @if(Auth::user()->privilege === 'ROOT')
                    formData.append('vendorclientgroupid', $('#editClientGroupId').val())
                @endif

                $.ajax({
                    type: 'POST',
                    url: '/dosaveeditvendor',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data vendor is updated.')
                        } else {
                            alert('Failed to update data vendor.')
                        }

                        btnSaveEditVendor.attr('disabled', false)
                        modalEditVendor.modal('toggle')
                        btnSaveEditVendor.html('Save')

                        tableVendor.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditVendor.attr('disabled', false)
                        modalEditVendor.modal('toggle')
                        btnSaveEditVendor.html('Save')

                        alert('Failed to update data vendor.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditVendor.attr('disabled', false)
                        modalEditVendor.modal('toggle')
                        btnSaveEditVendor.html('Save')

                        alert('Failed to update data vendor.')
                    }
                })
            })

            $('#modalDelVendor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let vendorId = button.attr('data-delVendorId')
                let vendorName = button.attr('data-delVendorName')

                $('#delVendorId').val(vendorId)
                $('#delNotificationInModal').html('<span>Are you sure want to delete vendor <span style="color:red">' + vendorName + '</span>? Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteVendor').on('click', function() {
                let btnDeleteVendor = $('#btnDeleteVendor')
                let modalDeleteVendor = $('#modalDelVendor')

                btnDeleteVendor.attr('disabled', true)
                btnDeleteVendor.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('vendorid', $('#delVendorId').val())

                $.ajax({
                    type: 'POST',
                    url: '/dodeletevendor',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Data vendor is deleted.')
                        } else {
                            alert('Failed to delete data vendor.')
                        }

                        btnDeleteVendor.attr('disabled', false)
                        modalDeleteVendor.modal('toggle')
                        btnDeleteVendor.html('Delete')

                        tableVendor.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete data vendor.')
                        btnDeleteVendor.attr('disabled', false)
                        modalDeleteVendor.modal('toggle')
                        btnDeleteVendor.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete data vendor.')
                        btnDeleteVendor.attr('disabled', false)
                        modalDeleteVendor.modal('toggle')
                        btnDeleteVendor.html('Delete')
                    }
                })
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
