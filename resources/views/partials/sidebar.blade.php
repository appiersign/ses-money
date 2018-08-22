<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed"><a class="sidebar-link td-n" href="index.html" class="td-n">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo"><img src="assets/static/images/logo.png" alt=""></div>
                            </div>
                            <div class="peer peer-greed"><h5 class="lh-1 mB-0 logo-text">SESMONEY</h5></div>
                        </div>
                    </a></div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle"><a href="#" class="td-n"><i
                                    class="ti-arrow-circle-left"></i></a></div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-30 active">
                <a class="sidebar-link" href="{{ url('/') }}" default>
                    <span class="icon-holder"><i class="c-blue-500 ti-home"></i> </span><span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="{{ route('merchants.index') }}">
                    <span class="icon-holder"><i class="c-brown-500 ti-user"></i> </span>
                    <span class="title">Merchants</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder"><i class="c-orange-500 ti-layout-list-thumb"></i> </span>
                    <span class="title">Transactions</span>
                    <span class="arrow"><i class="ti-angle-right"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="sidebar-link" href="{{ route('transfers.index') }}">Transfers</a></li>
                    <li><a class="sidebar-link" href="{{ route('payments.index') }}">Payments</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder"><i class="c-deep-orange-500 ti-calendar"></i> </span>
                    <span class="title">History</span>
                    <span class="arrow"><i class="ti-angle-right"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="sidebar-link" href="{{ route('transfers.history') }}">Transfers</a></li>
                    <li><a class="sidebar-link" href="{{ route('payments.history') }}">Payments</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="chat.html">
                    <span class="icon-holder"><i class="c-deep-purple-500 ti-mobile"></i> </span>
                    <span class="title">Terminals</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="charts.html">
                    <span class="icon-holder"><i class="c-indigo-500 ti-bar-chart"></i> </span>
                    <span class="title">Credentials</span>
                </a>
            </li>
        </ul>
    </div>
</div>