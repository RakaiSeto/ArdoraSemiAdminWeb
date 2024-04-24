<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar">
        &nbsp;&nbsp;
        <div>
            <img class="mx-auto d-block" src="{{ asset('/image/Logo.png') }}" alt="{{ env('APP_NAME') }}" width="100">
        </div>
        <div class="user-profile">
            <div class="profile-pic">
                <div class="profile-info"><h5 class="mt-15">{{ Auth::user()->name }}</h5>
                    <small style="color: white;">{{ Auth::user()->email }}</small><br/>
                    <div class="text-center d-inline-block">
                        <a href="/logout" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i class="ion ion-power"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="header nav-small-cap">CONSOLE</li>

{{--            <li class="treeview active">--}}
{{--                <a href="#">--}}
{{--                    <i class="ti-dashboard"></i>--}}
{{--                    <span>Report</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--              <i class="fa fa-angle-right pull-right"></i>--}}
{{--            </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="/report"><i class="ti-printer"></i>Report</a></li>--}}
{{--                    <li><a href="/vendor"><i class="ti-printer"></i>Report Vendor</a></li>--}}

{{--                    @if(AUTH::user()->privilege === 'ROOT')--}}
{{--                    <li><a href="/activity"><i class="ti-mouse"></i>Activity</a></li>--}}

{{--                    @endif--}}
{{--                </ul>--}}
{{--            </li>--}}

            <li>
                <a href="/">
                    <i class="ti-dashboard"></i>
                    <span>Report</span>
                </a>
            </li>

            <li>
                <a href="/messagingrouting">
                    <i class="ti-direction-alt"></i>
                    <span>Routing Table</span>
                </a>
            </li>

            <li>
                <a href="/csvreport"><i class="ti-receipt"></i>Exported Reports</a></li>

            <li>
                <a href="/balance">
                    <i class="ti-money"></i>
                    <span>Balance Management</span>
                </a>
            </li>

            <li>
                <a href="/summaryTrx">
                    <i class="ti-bar-chart"></i>
                    <span>Transaction Summary</span>
                </a>
            </li>

<!--
            <li class="header nav-small-cap">WHATSAPP MONITORING</li>

            <li>
                <a href="/whatsapp">
                    <i class="ti-bar-chart"></i>
                    <span>Device</span>
                </a>
            </li>
-->
        </ul>
    </section>
</aside>
