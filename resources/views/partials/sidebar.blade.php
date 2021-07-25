<nav class="pcoded-navbar">
    <div class="nav-list">
        <div class="pcoded-inner-navbar main-menu">
            <ul class="pcoded-item pcoded-left-item">

                <div class="pcoded-navigation-label">Dashboard</div>

                <li class="">
                    <a
                    href="{{ url('/home') }}"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="feather icon-home"></i
                    ></span>
                    <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>

                <div class="pcoded-navigation-label">Users</div>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-users"></i></span>
                    <span class="pcoded-mtext">Users</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('users/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('users') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Manage Users</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('deleted-users') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Deleted Users</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('terminate-user-form') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Terminate User</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('reset-pin') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Reset Pin</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="fa fa-users"></i></span>
                        <span class="pcoded-mtext">Beneficiaries</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="">
                            <a href="{{ url('beneficiaries/create') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Add New</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ url('beneficiaries') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Manage Beneficiaries</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-th-large"></i
                    ></span>
                    <span class="pcoded-mtext">Departments</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('departments/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('assign-manager') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Assign Manager</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('departments') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Manage Departments</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-user"></i
                    ></span>
                    <span class="pcoded-mtext">Employee Types</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('usertypes/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('usertypes') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Manage Employees</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <div class="pcoded-navigation-label">Allocations</div>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-calendar"></i></span>
                    <span class="pcoded-mtext">Allocations</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('allocations/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('allocations') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Allocations</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('import-allocation') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Import Allocation</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('deleted-allocations') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Deleted Allocations</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a
                        href="javascript:void(0)"
                        class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                    ><i class="fa fa-tasks"></i></span>
                        <span class="pcoded-mtext">Jobcards</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="">
                            <a
                                href="{{ url('jobcards/create') }}"
                                class="waves-effect waves-dark"
                            >
                                <span class="pcoded-mtext">Add New</span>
                            </a>
                        </li>
                        <li class="">
                            <a
                                href="{{ url('jobcards') }}"
                                class="waves-effect waves-dark"
                            >
                                <span class="pcoded-mtext">Manage Jobcards</span>
                            </a>
                        </li>
                        <li class="">
                            <a
                                href="{{ url('get-jobcard-import') }}"
                                class="waves-effect waves-dark"
                            >
                                <span class="pcoded-mtext">Import Jobcards</span>
                            </a>
                        </li>
                        <li class="">
                            <a
                                href="{{ url('deleted-jobcards') }}"
                                class="waves-effect waves-dark"
                            >
                                <span class="pcoded-mtext">Deleted</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <div class="pcoded-navigation-label">Food Requests</div>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-file-excel-o"></i></span>
                    <span class="pcoded-mtext">Requests</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('frequests/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Create New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('frequests') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">All Requests</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('/get-daily-approval') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Daily Schedule</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('approved-requests') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Approved Requests</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('collected-requests') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Collected Requests</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('pending-requests') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Pending Requests</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <div class="pcoded-navigation-label">Collection</div>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-book"></i></span>
                    <span class="pcoded-mtext">Food Collection</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('fcollections/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('fcollections') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Collections</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href=""
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Extras / Previous</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-book"></i></span>
                    <span class="pcoded-mtext">Meat Collection</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('mcollections/create') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Add New</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('mcollections') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Collections</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href=""
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Extras / Previous</span>
                        </a>
                    </li>
                    </ul>
                </li>

                <li class="pcoded-hasmenu">
                    <a
                    href="javascript:void(0)"
                    class="waves-effect waves-dark"
                    >
                    <span class="pcoded-micon"
                        ><i class="fa fa-book"></i></span>
                    <span class="pcoded-mtext">Reports</span>
                    </a>
                    <ul class="pcoded-submenu">
                    <li class="">
                        <a
                        href="{{ url('get-month-report') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">Monthly</span>
                        </a>
                    </li>
                    <li class="">
                        <a
                        href="{{ url('user-collection-report') }}"
                        class="waves-effect waves-dark"
                        >
                        <span class="pcoded-mtext">User Collection</span>
                        </a>
                    </li>

                    </ul>
                </li>

                <div class="pcoded-navigation-label">System Settings</div>

                <li class="">
                    <a href="{{ url('hsettings-get') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                          <i class="feather icon-aperture"></i>
                        </span>
                        <span class="pcoded-mtext">Humber Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
  </nav>
