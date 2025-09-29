<header class="w-100 d-flex justify-content-between align-items-center p-3 navbar text-white">
    <a type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="bi bi-list"></i>
    </a>
    <div class="d-flex align-items-center">
        <h1 class="h4 mb-0">Hostello</h1>
    </div>
</header>
<!-- ===================Offcanvas======================== -->

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h3 class="offcanvas-title mt-3 p-3">Admin</h3>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0 slide-left">
        <div class="list-data">
            <!-- students -->
            <div class="container">
                <div class="row mt-2 mb-2 cols-header">
                    <div class="d-flex">
                        <div class="col-8 text-left">
                            <a href="" class="nav-link disabled" tabindex="-1" aria-disabled="true">Student
                            </a>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="row p-2 cols-col">
                    <ul class="none d-flex flex-column gap-3">
                        <li><a href="admin.php" class="nav-link">All Student</a></li>
                        <li class="mt-2">
                            <button class="btn btn-primary d-flex align-items-center gap-1 w-100" id="openPopup">
                                <i class="bi bi-person-plus"></i>
                                Add Student
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="w-100 my-2 bg-success" />
            <!-- room and block  -->
            <div class="container">
                <div class="row mt-2 mb-2 cols-header">
                    <div class="d-flex">
                        <div class="col-8 text-left">
                            <a href="" class="nav-link disabled" tabindex="-1" aria-disabled="true">Rooms</a>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                            <i class="bi bi-chevron-up"></i>
                        </div>
                    </div>
                </div>
                <div class="row p-2 cols-col">
                    <ul class="none  d-flex flex-column gap-3">
                        <li class="mt-2">
                            <a href="roomsetting.php"
                                class="btn btn-primary d-flex align-items-center gap-1 w-100">
                                <i class="bi bi-house"></i>
                                Room Setting
                            </a>
                        </li>


                    </ul>
                </div>
            </div>
            <hr class="w-100 my-2 bg-success" />
            <!-- settings  -->
            <div class="container">
                <div class="row mt-2 mb-2 cols-header">
                    <div class="d-flex">
                        <div class="col-8 text-left">
                            <a href="" class="nav-link disabled" tabindex="-1" aria-disabled="true">settings
                            </a>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="row p-2 cols-col">
                    <ul class="none d-flex flex-column gap-3">
                        <li><a href="payment.php" class="nav-link">payment</a></li>
                        <hr class="w-80 my-2 bg-success" />
                        <li class="modechng">
                            <label class="switch ">
                                <input type="checkbox" id="darkModeToggle" onclick="toggleDarkMode()">
                                <span class="slider"></span>
                            </label>
                        </li>
                        <hr class="w-80 my-2 bg-success" />
                        <li><a href="#" class="nav-link">LogOut <i class="bi bi-box-arrow-right"
                                    style="color: red;"></i></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content -->
<div class="container-fluid">
    <div class="col-md-12 p-4">
        <!-- Top Filter Bar -->
        <div class="filter-bar row mb-4 p-4 shadow-sm rounded filter  ">

            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <label class="form-label mb-0 small">Block</label>
                <select id="blockFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="A">Block A</option>
                    <option value="B">Block B</option>
                    <option value="C">Block C</option>
                    <option value="D">Block D</option>
                </select>
            </div>

            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <label class="form-label mb-0 small">Payment</label>
                <select id="paymentFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="paid">Paid</option>
                    <option value="due">Due Soon</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label mb-0 small">Occupancy</label>
                <select id="occupancyFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="vacant">Vacant Beds</option>
                    <option value="occupied">Occupied Beds</option>
                </select>
            </div>

        </div>
    </div>
</div>