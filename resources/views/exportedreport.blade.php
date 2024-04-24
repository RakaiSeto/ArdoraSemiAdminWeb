@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            @if(session('privilege') == 'ROOT')

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
                                            <label for="findClientField" class="col-sm-4 col-form-label">Find
                                                Field</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2 w-p100" id="findClientField">
                                                    <option value="clientId">Client ID</option>
                                                    <option value="clientName" selected>Client Name</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="findClientKeyword" class="col-sm-4 col-form-label">Find
                                                Keyword</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="findClientKeyword">
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer modal-footer-uniform">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary float-right" id="btnFindClient"
                                        data-dismiss="modal">Find
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h3 class="box-title">Exported Report</h3>
                                <div class="mailbox-controls px-0 pt-20 pb-0">
                                    @if(session('privilege') == 'ROOT')
                                        <button type="button" class="btn btn-info btn-sm" id="btnFindClient"
                                                data-toggle="modal" data-target="#modalFindClient">Find Client
                                        </button></div>
                                @endif
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableReport"
                                               class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Download</th>
                                                <th style="text-align: center">Generating Status</th>
                                                <th style="text-align: center">Req. Date Time</th>
                                                <th style="text-align: center">Client ID</th>
                                                <th style="text-align: center">Client Name</th>
                                                <th style="text-align: center">User Name</th>
                                                <th style="text-align: center">Start Date</th>
                                                <th style="text-align: center">End Date</th>
                                                <th style="text-align: center">Search Parameter</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Download</th>
                                                <th style="text-align: center">Generating Status</th>
                                                <th style="text-align: center">Req. Date Time</th>
                                                <th style="text-align: center">Client ID</th>
                                                <th style="text-align: center">Client Name</th>
                                                <th style="text-align: center">User Name</th>
                                                <th style="text-align: center">Start Date</th>
                                                <th style="text-align: center">End Date</th>
                                                <th style="text-align: center">Search Parameter</th>
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
        function getFindField() {
            return $('#findClientField').val()
        }

        function getFindKeyword() {
            return $('#findClientKeyword').val()
        }

        $(document).ready(function () {
            let tableReport = $('#tableReport').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    'type': 'POST',
                    'url': '/tblCSVReport',
                    'data': function (x) {
                        x._token = '{{ csrf_token() }}'
                        x.searchCategory = getFindField()
                        x.searchKeyword = getFindKeyword()
                    }
                },
                columns: [
                    {data: 'action', name: 'action'},
                    {data: 'is_generated_2', name: 'is_generated_2'},
                    {data: 'request_datetime', name: 'request_datetime'},
                    {data: 'client_id', name: 'client_id'},
                    {data: 'client_name', name: 'client_name'},
                    {data: 'username', name: 'username'},
                    {data: 'start_datetime', name: 'start_datetime'},
                    {data: 'end_datetime', name: 'end_datetime'},
                    {data: 'search_parameter', name: 'search_parameter'}
                ],
                columnDefs: [
                    {"targets": [0, 1, 2, 3, 6, 7], "className": "text-center"}
                ]
            })

            $('#btnFindClient').on('click', function () {
                tableReport.ajax.reload()
            })

            $('#modalFindClient').on('show.bs.modal', function () {
                $('#findClientField').prop("selectedIndex", 0)
                $('#findClientKeyword').val('')
            })
        })
    </script>
@endsection

@section('jscript')
    <script src="{{ asset('/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
@endsection
