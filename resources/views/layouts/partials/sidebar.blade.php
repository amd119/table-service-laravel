<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="index.html" class="b-brand">
                <!-- ========   change your logo here   ============ -->
                <img src="{{ asset('img/logo-full.png')}}" alt="" class="logo logo-lg" />
                <img src="{{ asset('img/logo-abbr.png')}}" alt="" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboards</span>
                    </a>
                </li>
                @if (in_array(Auth::user()?->role, ['administrator', 'waiter']))
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-layout"></i></span>
                            <span class="nxl-mtext">Data Master</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('menu.index') }}">Menu List</a></li>
                            @if (Auth::user()?->role == 'administrator')
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('table.index') }}">Table Data</a></li>
                            @endif
                            {{-- <li class="nxl-item"><a class="nxl-link" href="#">Customers</a></li> --}}
                        </ul>
                    </li>
                    @if (Auth::user()?->role == 'administrator')
                        <li class="nxl-item nxl-hasmenu">
                            <a href="{{ route('user.index') }}" class="nxl-link">
                                <span class="nxl-micon"><i class="feather-users"></i></span>
                                <span class="nxl-mtext">Users</span>
                            </a>
                        </li>
                    @endif
                @endif

                @if (Auth::user()?->role == 'waiter')
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('order.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-archive"></i></span>
                            <span class="nxl-mtext">Order</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('order.report.form') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-cast"></i></span>
                            <span class="nxl-mtext">Order Reports</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()?->role == 'owner')
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('order.report.form') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cast"></i></span>
                        <span class="nxl-mtext">Order Reports</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('transaksi.report.form') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cast"></i></span>
                        <span class="nxl-mtext">Transaction Reports</span>
                    </a>
                </li>
                @endif

                @if (Auth::user()?->role == 'kasir')
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('transaksi.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-archive"></i></span>
                            <span class="nxl-mtext">Transaction</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="{{ route('transaksi.report.form') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-cast"></i></span>
                            <span class="nxl-mtext">Transaction Reports</span>
                        </a>
                    </li>
                @endif

                {{-- @if (in_array(Auth::user()?->role, ['owner']))
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-cast"></i></span>
                            <span class="nxl-mtext">Reports</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="reports-sales.html">Sales Report</a></li>
                            <li class="nxl-item"><a class="nxl-link" href="reports-leads.html">Leads Report</a></li>
                        </ul>
                    </li>
                @endif --}}

                <li class="nxl-item nxl-hasmenu">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                    </form>
                    <a href="#" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-log-out"></i></span>
                        <span class="nxl-mtext">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>