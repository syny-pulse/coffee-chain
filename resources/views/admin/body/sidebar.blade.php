
<style>
    /* Vertical Menu Custom Branding */




.vertical-menu .metismenu a:hover
 {
    background-color: #635bff; /* Teal background on hover or when active */
    color: #ffffff !important;
}
.vertical-menu .metismenu a.active{
    color: #635bff !important; /* Teal background on hover or when active */

}
.vertical-menu .metismenu a:hover i {
    color: #ffffff !important;
}
.vertical-menu .metismenu a.active:hover {
    color: #ffffff !important; /* White color on hover of active link */
    background-color: #635bff !important; /* Optionally, keep the background color */
}

</style>

 <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->


                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ url('/dashboard') }}" class="waves-effect">
                                    <i class="ri-home-fill" style="color: #635bff"></i>

                                    <span>Dashboard</span>
                                </a>
                            </li>



                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-hotel-fill"  style="color: #635bff"></i>
                                    <span>Manage Retailers</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('supplier.all') }}">All retailers</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-shield-user-fill"  style="color: #635bff"></i>
                                    <span>Manage Customers</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('customer.all') }}">All Customers</a></li>
                                    <li><a href="{{ route('credit.customer') }}">Credit Customers</a></li>
                                    <li><a href="{{ route('paid.customer') }}">Paid Customers</a></li>
                                    <li><a href="{{ route('unpaid.customer') }}">Outstanding balances</a></li>
                                    <li><a href="{{ route('customer.wise.report') }}">Customer Report</a></li>


                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-delete-back-fill"  style="color: #635bff"></i>
                                    <span>Manage Units</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('unit.all') }}">All Unit</a></li>

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-apps-2-fill"  style="color: #635bff"></i>
                                    <span>Manage Ingredients</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('category.all') }}">All Ingredients</a></li>

                                </ul>
                            </li>


                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-reddit-fill"  style="color: #635bff"></i>
                                    <span>Manage Products</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('product.all') }}">All Product</a></li>
                                    <li><a href="{{ route('stock.supplier.wise') }}">Retailer / Product Wise </a></li>

                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-oil-fill"  style="color: #635bff"></i>
                                    <span>Manage Stock</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('purchase.all') }}">All Stocks</a></li>
                                    <li><a href="{{ route('purchase.pending') }}">Pending stocks</a></li>
                                    <li><a href="{{ route('daily.purchase.report') }}">Daily purchase Report</a></li>
                                    <li><a href="{{ route('stock.report') }}">Track Stock</a></li>


                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-compass-2-fill"  style="color: #635bff"></i>
                                    <span>Manage Invoice</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('invoice.all') }}">All Invoice</a></li>
                                    <li><a href="{{ route('invoice.pending.list') }}">pending Invoices</a></li>
                                    {{-- <li><a href="{{ route('print.invoice.list') }}">Print Invoice List</a></li> --}}
                                    <li><a href="{{ route('daily.invoice.report') }}">Daily Invoice Report</a></li>



                                </ul>
                            </li>

                            {{-- <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-layout-3-line"></i>
                                    <span>Layouts</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true">
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow">Vertical</a>
                                        <ul class="sub-menu" aria-expanded="true">
                                            <li><a href="layouts-dark-sidebar.html">Dark Sidebar</a></li>
                                            <li><a href="layouts-compact-sidebar.html">Compact Sidebar</a></li>
                                            <li><a href="layouts-icon-sidebar.html">Icon Sidebar</a></li>
                                            <li><a href="layouts-boxed.html">Boxed Layout</a></li>
                                            <li><a href="layouts-preloader.html">Preloader</a></li>
                                            <li><a href="layouts-colored-sidebar.html">Colored Sidebar</a></li>
                                        </ul>
                                    </li>

                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow">Horizontal</a>
                                        <ul class="sub-menu" aria-expanded="true">
                                            <li><a href="layouts-horizontal.html">Horizontal</a></li>
                                            <li><a href="layouts-hori-topbar-light.html">Topbar light</a></li>
                                            <li><a href="layouts-hori-boxed-width.html">Boxed width</a></li>
                                            <li><a href="layouts-hori-preloader.html">Preloader</a></li>
                                            <li><a href="layouts-hori-colored-header.html">Colored Header</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li> --}}

                            {{--  <li class="menu-title">Stock</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-gift-fill"  style="color: #635bff"></i>
                                    <span>Manage Stock</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    
                                   

                                </ul>
                            </li>  --}}

                            <li class="menu-title">Forecast</li>

<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="ri-line-chart-fill" style="color: #635bff"></i>
        <span>Sales Forecast</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">
        <!-- High-level forecast insights -->
        <li><a href="{{ route('forecast.overview') }}">Forecast Overview</a></li>
        <li><a href="{{ route('forecast.trend') }}">Sales Trends</a></li>
        <li><a href="{{ route('forecast.demand') }}">Demand Prediction</a></li>

        <!-- Detailed forecast periods -->
         <li><a href="{{ route('forecast.daily') }}">Daily Forecast</a></li>

        <li><a href="{{ route('forecast.weekly') }}">Weekly Forecast</a></li>
        <li><a href="{{ route('forecast.monthly') }}">Monthly Forecast</a></li>
        {{--<li><a href="{{ route('forecast.custom') }}">Custom Forecast</a></li> --}}
    </ul>
</li>

                            {{-- <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-profile-line"></i>
                                    <span>Support</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="pages-starter.html">Starter Page</a></li>
                                    <li><a href="pages-timeline.html">Timeline</a></li>
                                    <li><a href="pages-directory.html">Directory</a></li>
                                    <li><a href="pages-invoice.html">Invoice</a></li>
                                    <li><a href="pages-404.html">Error 404</a></li>
                                    <li><a href="pages-500.html">Error 500</a></li>
                                </ul>
                            </li> --}}






                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
