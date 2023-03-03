<div id="scrollbar">
    <div class="container-fluid">

        <div id="two-column-menu">
        </div>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title"><span data-key="t-menu">Main</span></li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="/">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                </a>
            <li>

            <li class="nav-item">
                <a href="#adminMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false"
                   aria-controls="sidebarnft" data-key="t-nft-marketplace">
                    <i class="ri-admin-line"></i>   <span data-key="t-file-manager"> Admin</span>
                </a>
                <div class="collapse menu-dropdown" id="adminMenu">
                    <ul class="nav nav-sm flex-column">

                        <li class="nav-item"><a class="nav-link" href="/admin/affiliates">Affiliates</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/coupons">Coupons</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/discovery">Discovery Builder</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/email_templates">Email Templates</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/file_categories">File Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/integrations">Integrations</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/users">My Team</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/lead_types">Lead Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/package_builds">Package Builder</a></li>
                        @if(setting('brand.license'))
                            <li class="nav-item"><a class="nav-link" href="/admin/partners">Partners</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="/admin/bill_categories/products">Products</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/admin/bill_categories/services">Services</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/admin/settings">Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/tax_locations">Tax Locations</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/terms">TOS Manager</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/vendors">Vendors</a></li>


                    </ul>
                </div>
            </li> <!-- end Dashboard Menu -->

            {!! moduleHook('layouts.admin') !!}
            <li class="menu-title"><span data-key="t-menu">Operations</span></li>


            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/accounts">
                    <i class="ri-customer-service-line"></i> <span data-key="t-dashboards">Accounts</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/orders">
                    <i class="ri-calendar-check-line"></i> <span data-key="t-Orders">Orders</span>
                    @if(\App\Models\Order::where('active', true)->count())
                        <span
                            class="badge bg-info ms-auto ">{{\App\Models\Order::where('active', true)->count()}}</span>
                    @endif
                </a>
            <li>


            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/shipments">
                    <i class="ri-mail-settings-line"></i> <span data-key="t-shipments">Shipments</span>
                    @if(\App\Models\Shipment::where('active', true)->count())
                        <span
                            class="badge bg-info ms-auto ">{{\App\Models\Shipment::where('active', true)->count()}}</span>
                    @endif
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/meetings">
                    <i class="ri-map-pin-time-line"></i> <span data-key="t-shipments">Meetings</span>
                </a>
            <li>


            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/projects">
                    <i class="ri-stack-line"></i> <span data-key="t-projects">Projects</span>
                </a>
            <li>


            <li class="menu-title"><span data-key="t-menu">Sales</span></li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/leads">
                    <i class="ri-file-user-line"></i> <span data-key="t-leads">Leads</span>
                </a>
            <li>


            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/quotes">
                    <i class="ri-money-dollar-circle-line"></i> <span data-key="t-quotes">Quotes</span>
                </a>
            <li>


            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/sales/funnel">
                    <i class="ri-coins-line"></i> <span data-key="t-funnel">Sales Funnel</span>
                </a>
            <li>
            {!! moduleHook('layouts.sales') !!}


            <li class="menu-title"><span data-key="t-menu">Finance</span></li>

            {!! moduleHook('layouts.accounting') !!}

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/finance/flow">
                    <i class="ri-funds-box-fill"></i> <span data-key="t-flow">Cash Flow</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/finance/invoices">
                    <i class="ri-money-dollar-box-line"></i> <span data-key="t-invoices">Invoices</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/finance/transactions">
                    <i class="ri-bar-chart-2-line"></i> <span data-key="t-transactions">Transactions</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="/admin/finance/commissions">
                    <i class="ri-line-chart-line"></i> <span data-key="t-commissions">Commissions</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="{{currentVersion()->changelog}}">
                    <i class="ri-file-list-2-line"></i> <span data-key="t-changelog">Changelog</span>
                    <span class="badge bg-primary ms-auto ">v{{currentVersion()->version}}</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" target="_blank" href="https://logic.readme.io/discuss">
                    <i class="ri-wechat-line"></i> <span data-key="t-discuss">Discuss Logic</span>
                </a>
            <li>

            <li class="nav-item">
                <a class="nav-link menu-link" target="_blank" href="https://logic.readme.io/docs">
                    <i class="ri-book-3-line"></i> <span data-key="t-docs">Documentation</span>
                </a>
            </li>

        </ul>

    </div>
</div>
