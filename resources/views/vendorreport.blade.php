@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h3 class="box-title">Vendor Status Report</h3>
                            </div>

                            <div class="box-body pt-0">
                                <div class="mailbox-messages bg-white">
                                    <div class="table-responsive">
                                        <table id="tableReport" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Date Report</th>
                                                <th style="text-align: center">Report All Status</th>
                                                <th style="text-align: center">Report Non Success</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align: center">Date Report</th>
                                                <th style="text-align: center">Report All Status</th>
                                                <th style="text-align: center">Report Non Success</th>
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

        $(document).ready( function() {
            let tableReport = $('#tableReport').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax:{
                    'type': 'POST',
                    'url': '/tblCSVReportVendor',
                    'data': function(x) {
                        x._token = '{{ csrf_token() }}'
                        x.searchCategory = getFindField()
                        x.searchKeyword = getFindKeyword()
                    }
                },
                columns: [
                    { data: 'report_datetime', name: 'report_datetime'},
                    { data: 'action1', name: 'action1'},
                    { data: 'action2', name: 'action2'}
                ],
                columnDefs: [
                    { "targets": [0, 1, 2], "className": "text-center"}
                ]
            })

            $('#btnFindClient').on('click', function() {
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
