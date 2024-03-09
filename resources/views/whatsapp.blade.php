@extends('layout.app')

@section('content')
    <style type="text/css">
        iframe {
            margin: 0px;
            padding: 0px;
            height: 100%;
            border: none;
        }

        iframe {
            display: block;
            width: 100%;
            border: none;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
           <div class="content-header">
               <h3>
                   Whatsapp Device Monitoring
               </h3>
               <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Whatsapp</a></li>
                   <li class="breadcrumb-item active">Monitoring </li>
               </ol>
           </div>

            <!-- Modal new keyword -->
            <div class="modal center-modal fade" id="modalNewKeyword">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">New Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="newKeyword" class="col-sm-4 col-form-label">Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="newKeyword">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveNewKeyword">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal edit webuser -->
            <div class="modal center-modal fade" id="modalEditKeyword" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Edit Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="editKeyword">
                                    <div class="form-group row">
                                        <label for="editKeyword" class="col-sm-4 col-form-label">Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="editNewKeyword">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnSaveEditKeyword">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal delete keyword -->
            <div class="modal center-modal fade" id="modalDeleteKeyword" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Delete Keyword</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="deleteKeyword">
                                    <div id="delNotificationInModal"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnDeleteKeyword">Delete</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modaldata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Scan Whatsapp QRCode</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="height: 500px;">
                            <iframe id="frame_data" src="" title="Remote Engine" allowfullscreen width="100%" height="100%"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                       </div>
                    </div>
                </div>
            </div>

            <div class="modal center-modal fade" id="modalFindWebUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="box-title">Find User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-element">
                                <div class="box-body">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="findField" class="col-sm-4 col-form-label">Find Field</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 w-p100" id="findField">
                                                <option value="fullName" selected>Full Name</option>
                                                <option value="email">Email</option>
                                                <option value="client">Client</option>
                                                <option value="privilege">Privilege</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="findKeyword" class="col-sm-4 col-form-label">Find Keyword</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" id="findKeyword">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary float-right" id="btnFind" data-dismiss="modal">Find</button>
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
                                {{-- <h3 class="box-title">Web User</h3> --}}
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    <div class="row">
                                        <div class="col-lg-3 col-xs-3">
                                            <div class="small-box bg-success">
                                                <div class="inner">
                                                    <h3>{{ $totalSlot[0]->total }}</h3>
                                                    <p>AVAILABLE SLOT</p>
                                                </div>
                                                <div class="icon"><i class="fa fa-database"></i></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-xs-3">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>{{ $totalActive[0]->total }}</h3>
                                                    <p>ACTIVE SLOT</p>
                                                </div>
                                                <div class="icon"><i class="fa fa-check"></i></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-xs-3">
                                            <div class="small-box bg-danger">
                                                <div class="inner">
                                                    <h3>{{ $totalInActive[0]->total }}</h3>
                                                    <p>INACTIVE SLOT</p>
                                                </div>
                                                <div class="icon"><i class="fa fa-warning"></i></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-xs-3">
                                            <div class="small-box bg-dark">
                                                <div class="inner">
                                                    <h3>{{ $totalDown[0]->total }}</h3>
                                                    <p>SERVICE DOWN</p>
                                                </div>
                                                <div class="icon"><i class="fa fa-toggle-off"></i></div>
                                            </div>
                                        </div>
                                    </div>

{{--                                    <button type="button" class="btn btn-info btn-sm" id="btnNewKeyword" data-toggle="modal" data-target="#modalNewKeyword">New Keyword</button>--}}
                                    {{-- <button type="button" class="btn btn-info btn-sm" id="btnFind" data-toggle="modal" data-target="#modalFindWebUser">Find User</button>                                </div> --}}
                                </div>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">

                                        <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Node</th>
                                                <th style="text-align: center">WA Number</th>
                                                <th style="text-align: center">IP Local</th>
                                                <th style="text-align: center">IP Public</th>
                                                <th style="text-align: center">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i=0; $i<count($keywordData); $i++)
                                                @if ($keywordData[$i]->device_status == 'NOK')
                                                    <tr style="background-color:#faecec;">
                                                @elseif ($keywordData[$i]->device_status == 'NOKLOGIN')
                                                    <tr style="background-color:#cecece;">
                                                @else
                                                    <tr>
                                                @endif

                                                    <td style="text-align: center; font-weight: bold">{{ $keywordData[$i]->device_location  }}</td>
                                                    <td style="text-align: center; font-weight: bold">{{ $keywordData[$i]->device_number  }}</td>
                                                    <td style="text-align: center">{{ $keywordData[$i]->device_local  }}</td>
                                                    <td style="text-align: center">{{ $keywordData[$i]->device_public  }}</td>
                                                    @if ($keywordData[$i]->device_status == 'OK')
                                                        <td style="text-align: center; font-weight: bold">
                                                            <span class="badge badge-md badge-info" style="text-align: center; font-weight: bold"> ACTIVE </span>
                                                        </td>
                                                    @elseif ($keywordData[$i]->device_status == 'NOKLOGIN')
                                                        <td style="text-align: center; font-weight: bold">
                                                            <span class="badge badge-md badge-dark" style="text-align: center; font-weight: bold"> SERVICE DOWN </span>
                                                        </td>
                                                    @else
                                                        <td style="text-align: center; color: red">
                                                            <span class="badge badge-md badge-danger" style="text-align: center; font-weight: bold"> INACTIVE </span>
                                                            {{-- <span class="badge badge-md badge-danger" style="text-align: center; font-weight: bold" onclick="remote('{{ $keywordData[$i]->device_idx  }}')"> INACTIVE </span>--}}
{{--                                                            <button type="button" class="btn-md btn-primary" onclick="remote('{{ $keywordData[$i]->device_idx  }}')"> ACTIVATED </button>--}}
                                                        </td>
                                                    @endif
                                                </tr>

                                            @endfor
                                                <tr>

                                                </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Node</th>
                                                <th style="text-align: center">WA Number</th>
                                                <th style="text-align: center">IP Local</th>
                                                <th style="text-align: center">IP Public</th>
                                                <th style="text-align: center">Status</th>
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
        function remote(id){
            var url = "http://54.179.6.250/" + 'device/remote/' + id;
            $("#modaldata").modal("hide");
            $('#frame_data').attr('src', url);
            $("#modaldata").modal("show");
        }


        $(document).ready( function() {
            let tableKeyword = $('#tableKeyword').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    paging: false,
                    ajax: "/tblWhatsapp",
                    columns: [
                        { data: 'device_location', name: 'Node'},
                        { data: 'device_number', name: 'WA Number'},
                        { data: 'device_local', name: 'IP Local'},
                        { data: 'device_public', name: 'IP Public'},
                        { data: 'device_status', name: 'Status'}
                    ],
                    columnDefs: [
                        { "targets": [0, 1, 2, 3, 4], "className": "text-center"}
                    ],

                }
            )

            $('#btnFind').on('click', function() {
                tableKeyword.ajax.url("/tblWebUser?&searchcategory=" + $('#findField').val() + "&searchkeyword=" + $('#findKeyword').val()).load()
            })

            $('#modalFindWebUser').on('show.bs.modal', function () {
                $('#findField').prop("selectedIndex", 0)
                $('#findKeyword').val('')
            })

            @if(Auth::user()->privilege === 'ROOT')
            $('#newWebUserClientGroupId').on('change', function(e) {
                e.preventDefault()

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('clientgroupid', $('#newWebUserClientGroupId').val())

                $('#newWebUserClient').empty()
                $.ajax({
                    type: 'POST',
                    url: '/getclientlistbygroup',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        for (let x = 0; x < dataX.length; x++) {
                            $('#newWebUserClient')
                                .append('<option value="' + dataX[x]["client_id"] + '">' +
                                    dataX[x]["client_name"] + '</option>>')
                        }
                    }
                })
            })

            $('#newWebUserClientGroupId').change()
            @endif

            $('#modalNewKeyword').on('show.bs.modal', function() {
                $('#newKeyword').val('')
            })

            $('#btnSaveNewKeyword').on('click', function(e) {
                e.preventDefault()

                let btnSaveNewKeyword = $('#btnSaveNewKeyword')
                btnSaveNewKeyword.attr('disabled', true)
                btnSaveNewKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Saving')

                let modalNewKeyword = $('#modalNewKeyword')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#newKeyword').val())
                // formData.append('email', $('#newWebUserEmail').val())
                // formData.append('password', $('#newWebUserPassword').val())
                // formData.append('privilege', $('#newWebUserPrivilege').val())
                // @if(Auth::user()->privilege === 'ROOT')
                //     formData.append('clientgroup', $('#newWebUserClientGroupId').val())
                // @endif
                // @if((Auth::user()->privilege === 'ROOT') || (Auth::user()->privilege === 'SYSADMIN') || (Auth::user()->privilege === 'SYSFINANCE') || (Auth::user()->privilege === 'SYSOP'))
                //     formData.append('client', $('#newWebUserClient').val())
                // @endif
                // formData.append('canneuapix', $('#newCanSendNeuAPIXMessage').val())

                $.ajax({
                    type: 'POST',
                    url: '/doSaveNewKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('New blacklist keyword is saved.')
                        } else {
                            alert('Failed to save blacklist keyword.')
                        }

                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        alert('Failed to save blacklist keyword.')
                    },
                    error: function(){
                        btnSaveNewKeyword.attr('disabled', false)
                        modalNewKeyword.modal('toggle')
                        btnSaveNewKeyword.html('Save')

                        alert('Failed to save blacklist keyword.')
                    }
                })
            })

            $('#modalEditKeyword').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let keyword = button.attr('data-editKeyword')

                $('#editKeyword').val(keyword)
                $('#editNewKeyword').val(keyword)
            })

            $('#btnSaveEditKeyword').on('click', function(e) {
                e.preventDefault()

                let btnSaveEditKeyword = $('#btnSaveEditKeyword')
                btnSaveEditKeyword.attr('disabled', true)
                btnSaveEditKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Updating')

                let modalEditKeyword = $('#modalEditKeyword')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#editKeyword').val())
                formData.append('newkeyword', $('#editNewKeyword').val())
                // formData.append('webusername', $('#editWebUserFullName').val())
                // formData.append('webuseremail', $('#editWebUserEmail').val())
                // formData.append('webuserpassword', $('#editWebUserPassword').val())
                // formData.append('webuserprivilege', $('#editWebUserPrivilege').val())
                // formData.append('webuserclient', $('#editWebUserClient').val())
                // @if (Auth::user()->privilege === 'ROOT')
                //     formData.append('webuserclientgroupid', $('#editWebUserClientGroupId').val())
                // @endif

                $.ajax({
                    type: 'POST',
                    url: '/doSaveEditKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Blacklist keyword is updated.')
                        } else {
                            alert('Failed to update blacklist keyword.')
                        }

                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        alert('Failed to update blacklist keyword.')
                    },
                    error: function(){
                        //console.log('failed.')
                        btnSaveEditKeyword.attr('disabled', false)
                        modalEditKeyword.modal('toggle')
                        btnSaveEditKeyword.html('Save')

                        alert('Failed to update blacklist keyword.')
                    }
                })
            })

            @if (Auth::user()->privilege === 'ROOT')
                $('#editWebUserClientGroupId').on('change', function (e) {
                    e.preventDefault()

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    let formData = new FormData()
                    formData.append('clientgroupid', $('#editWebUserClientGroupId').val())

                    $.ajax({
                        type: 'POST',
                        url: '/getclientlistbygroup',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(dataX) {
                            $('#editWebUserClient').empty()
                            for (let x = 0; x < dataX.length; x++) {
                                let jsonDetail = dataX[x]

                                if (x == 0) {
                                    $('#editWebUserClient').append('<option selected="selected" value="' + jsonDetail["client_id"] + '">' + jsonDetail["client_name"] + '</option>')
                                } else {
                                    $('#editWebUserClient').append('<option value="' + jsonDetail["client_id"] + '">' + jsonDetail["client_name"] + '</option>')
                                }
                            }
                        },
                        fail: function(){
                            //console.log('failed.')
                        },
                        error: function(){
                            //console.log('failed.')
                        }
                    })
                })
            @endif

            $('#modalDeleteKeyword').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let deleteKeyword = button.attr('data-deleteKeyword')

                $('#deleteKeyword').val(deleteKeyword)
                $('#delNotificationInModal').html('<span>Are you sure want to delete keyword below :<br><br> <span style="color:red; weight:bold;">' + deleteKeyword + '</span> <br><br>Deleting data can not be reversed.</span>')
            })

            $('#btnDeleteKeyword').on('click', function() {
                let btnDeleteKeyword = $('#btnDeleteKeyword')
                let modalDeleteKeyword = $('#modalDeleteKeyword')

                btnDeleteKeyword.attr('disabled', true)
                btnDeleteKeyword.html('<span class="spinner-grow spinner-grow-sm align-middle" role="status" aria-hidden="true"></span> Deleting')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                let formData = new FormData()
                formData.append('keyword', $('#deleteKeyword').val())

                $.ajax({
                    type: 'POST',
                    url: '/doDeleteKeyword',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataX) {
                        console.log(dataX)
                        if (dataX === '0') {
                            alert('Keyword is deleted.')
                        } else {
                            alert('Failed to delete keyword.')
                        }

                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')

                        tableKeyword.ajax.reload(null, false)
                    },
                    fail: function(){
                        //console.log('failed.')
                        alert('Failed to delete keyword.')
                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')
                    },
                    error: function(){
                        //console.log('failed.')
                        alert('Failed to delete keyword.')
                        btnDeleteKeyword.attr('disabled', false)
                        modalDeleteKeyword.modal('toggle')
                        btnDeleteKeyword.html('Delete')
                    }
                })
            })

            $('#newWebUserPrivilege').on('change', function() {
                let priv = $('#newWebUserPrivilege').val()

                if(priv === 'B2B_RESELLER' || priv === 'B2B_USER' || priv === 'PUBLIC_USER_ADM' || priv === 'PUBLIC_USER') {
                    $('#formCanSendNeuAPIXMessage').show()
                } else {
                    $('#formCanSendNeuAPIXMessage').hide()
                }
            })

            $('#newWebUserPrivilege').change()
        })

        $("#newWebUserClient").select2({
            dropdownParent: $("#modalNewKeyword"),
            width: '100%',
            theme: 'classic'
        })

        $("#editWebUserClient").select2({
            dropdownParent: $("#modalEditKeyword"),
            width: '100%',
            theme: 'classic'
        })
    </script>
@endsection

