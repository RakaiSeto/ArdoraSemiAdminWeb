@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <h3>
                Interactive
                <small>Team Setup</small>
            </h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Interactive</a></li>
                <li class="breadcrumb-item active">Setup</li>
            </ol>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header no-border">
                            <h3 class="box-title">Teams</h3>
                            <div class="box-controls pull-right align-items-baseline">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-info btn-sm"><i class="fa fa-chevron-right"></i></button>
                                </div>
                                <h5 class="mb-0 pr-10">1-50/200</h5>
                            </div>

                            <div class="mailbox-controls px-0 pt-20 pb-0">
                                <button type="button" class="btn btn-info btn-sm">New Team</button>
                                <button type="button" class="btn btn-info btn-sm">(Un)Assign Member</button>

                                <div class="lookup lookup-lg lookup-right pull-right">
                                    <input type="text" name="s">
                                </div>
                            </div>
                        </div>

                        <div class="box-body pt-0">
                            <div class="mailbox-messages bg-white">
                                <div class="table-responsive">
                                    <table class="table table-hover b-2">
                                        <tbody>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Andrew</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">3 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">James</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">14 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">David</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">15 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Benjamin</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">25 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Logan</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">3 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Christopher</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">14 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Joseph</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">15 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Jackson</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">25 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Gabriel</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">3 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Ryan</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">14 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Samuel</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">15 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">John</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">25 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Christian</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">3 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Brayden</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">14 mins ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-warning"></i></a></td>
                                            <td class="mailbox-name">Evan</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">15 hours ago</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-warning"></i></a></td>
                                            <td class="mailbox-name">Jordan</td>
                                            <td class="mailbox-subject"><a href="read-mail.html"><b>Lorem Ipsum</b> - There are many variations of Ipsum available...</a>
                                            </td>
                                            <td class="mailbox-attachment"></td>
                                            <td class="mailbox-date">25 hours ago</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- /.table -->
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="mailbox-controls pb-0">
                                <div class="pull-right">
                                    1-50/200
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm"><i class="fa fa-chevron-left"></i></button>
                                        <button type="button" class="btn btn-info btn-sm"><i class="fa fa-chevron-right"></i></button>
                                    </div>
                                    <!-- /.btn-group -->
                                </div>
                                <!-- /.pull-right -->
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
