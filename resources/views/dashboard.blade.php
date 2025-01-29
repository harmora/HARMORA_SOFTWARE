@extends('layout')
@section('title')
<?= get_label('dashboard', 'Dashboard') ?>
@endsection
@php
$user = getAuthenticatedUser();
@endphp
@section('content')
@authBoth

<style>
    /* Unique card container */
.admin-dashboard-card {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 400px;
    margin: 20px auto;
    margin-top: 0;
    text-align: center;
}

/* Unique title styling */
.admin-dashboard-title {
    font-size: 24px;
    font-weight: bold;
    color: #333333;
    margin-bottom: 10px;
}

/* Unique description styling */
.admin-dashboard-description {
    font-size: 16px;
    color: #085cb0;
}

</style>

<style>
    .half-blue-half-white {
        background-image:   url('assets/img/logos/bg.svg');

background-position: center;
background-repeat: no-repeat;

    height: 97%; /* Adjust height as needed */
    border: none; /* Optional: remove border if you want a clean split */
}

.profile-container {
    text-align: center;
   /* Adjust padding to position profile picture */
}

.profile-pic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid white; /* Border around the profile picture */
    margin-bottom: 15px; /* Space between picture and name */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a slight shadow */
}

.profile-name {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 5px;
}

.profile-email {
    font-size: 0.8rem;
    color: #777;
    margin-bottom: 0;
}
</style>


<div class="container-fluid">
    <div class="col-lg-12 col-md-12 order-1">
        @if (!isset($general_settings['allowSignup']) || $general_settings['allowSignup'] == 1)
        @if (isAdminOrHasAllDataAccess() )
        <div class="alert alert-primary mt-4" role="alert">
            {{ get_label('primary_workspace_not_set_info', 'Signup is enabled, but primary workspace is not set, which is required for signup.') }}
            <a href="/workspaces" target="_blank">
                {{ get_label('click_to_set_now', 'Click here to set it now') }}
            </a>
            @role('admin')
            {{ get_label('or', 'or') }}
            <a href="/settings/general" target="_blank">
                {{ get_label('click_to_disable_signup', 'click here to disable signup.') }}
            </a>
            @endrole
        </div>
        @endif
        @endif


        <div class="row mt-4">
            <div class="col-lg-12 col-md-12 col-12 mb-4">
                <div class="card card half-blue-half-white text-center">
                    <div class="card-body">

                        @if(auth()->user()->role->rolename === 'user')
                        <div class="admin-dashboard-card">
                            <a href="{{ route('enterprise.profile') }}">
                            <img src="{{ auth()->user()->entreprise->photo ? asset('storage/' . auth()->user()->entreprise->photo) : asset('storage/photos/no-image.jpg') }}" alt="Profile Picture" class="profile-pic">
                        </a>
                            <h2 class="admin-dashboard-title">{{ get_label('welcome_to_your_dashboard', 'Welcome to Your Dashboard') }}</h2>
                            <p class="admin-dashboard-description">{{ get_label('manage_entreprise', 'Here you can manage your Entreprise, view statistics, and perform tasks.') }}</p>
                         </div>
                    @elseif(auth()->user()->role->rolename === 'admin')
                        {{-- <div class="profile-container">
                            <img src="https://media.licdn.com/dms/image/D4D0BAQEtnLWLnSV-Uw/company-logo_400_400/0/1711586810808?e=2147483647&v=beta&t=05K3VHWrqPONa3p9MeMj4XrLFKZPRts1wWiAiPO14aA" alt="Profile Picture" class="profile-pic">
                            <h3 class="profile-name">HARMORA</h3>
                            <p class="profile-email">admin</p>
                        </div> --}}

                        <div class="admin-dashboard-card">




                            <img src="{{asset("assets/img/logos/Logo.png")}}" alt="Profile Picture" class="profile-pic">
                            <h2 class="admin-dashboard-title"><?= get_label('Welcome to Your Admin Dashboard', 'Welcome to Your Admin Dashboard') ?></h2>
                            <p class="admin-dashboard-description"><?= get_label('Here you can manage your site, view statistics, and perform administrative tasks.', 'Here you can manage your site, view statistics, and perform administrative tasks.') ?></p>
                        </div>

                    @endif




                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            @if (auth()->user()->role->rolename === 'user')
                <div class="col-lg-3 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-pulse bx-md text-success"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1" title="Chiffre d'Affaires"><?= get_label('ca', 'CA') ?></span>
                            <h3 class="card-title mb-2">{{$ca}}</h3>
                            <a href="/{{getUserPreferences('projects', 'default_view')}}">
                                <small class="text-success fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-package bx-md text-primary"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1"><?= get_label('total_products', 'Total products') ?></span>
                            <h3 class="card-title mb-2">{{ is_countable($products) && count($products) > 0 ? count($products) : 0 }}</h3>
                            <a href="/{{getUserPreferences('tasks', 'default_view')}}">
                                <small class="text-primary fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bx-shopping-bag bx-md text-warning"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1"><?= get_label('total_orders', 'Total orders') ?></span>
                            <h3 class="card-title mb-2">{{ is_countable($commandes) && count($commandes) > 0 ? count($commandes) : 0 }}</h3>
                            <a href="/users">
                                <small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="menu-icon tf-icons bx bxs-user-detail bx-md text-info"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1"><?= get_label('total_clients', 'Total clients') ?></span>
                            <h3 class="card-title mb-2">{{ is_countable($clients) && count($clients) > 0 ? count($clients) : 0 }}</h3>
                            <a href="/clients">
                                <small class="text-info fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
                            </a>
                        </div>
                    </div>
                </div>
            @elseif (auth()->user()->role->rolename === 'admin')

<div class="row justify-content-center">
  <!-- Total Enterprises Counter -->
  <div class="col-lg-3 col-md-12 col-6 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                    <i class="menu-icon tf-icons bx bx-buildings bx-md text-success"></i>
                </div>
            </div>
            <span class="fw-semibold d-block mb-1"><?= get_label('total_enterprises', 'Total Enterprises') ?></span>
            <h3 class="card-title mb-2">{{ $entrepriseforadmin ?? 0 }}</h3>
            <a href="/enterprises">
                <small class="text-success fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
            </a>
        </div>
    </div>
</div>

<!-- Total Users Counter -->
<div class="col-lg-3 col-md-12 col-6 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                    <i class="menu-icon tf-icons bx bx-user bx-md text-primary"></i>
                </div>
            </div>
            <span class="fw-semibold d-block mb-1"><?= get_label('total_users', 'Total Users') ?></span>
            <h3 class="card-title mb-2">{{ $usersforadmin ?? 0 }}</h3>
            <a href="/users">
                <small class="text-primary fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
            </a>
        </div>
    </div>
</div>

<!-- Total Admins Counter -->
<div class="col-lg-3 col-md-12 col-6 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                    <i class="menu-icon tf-icons bx bxs-shield bx-md text-warning"></i>
                </div>
            </div>
            <span class="fw-semibold d-block mb-1"><?= get_label('total_admins', 'Total Admins') ?></span>
            <h3 class="card-title mb-2">{{ $adminsforadmin ?? 0 }}</h3>
            <a href="/admins">
                <small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small>
            </a>
        </div>
    </div>
</div>
</div>
            @endif
        </div>



       @if (auth()->user()->role->rolename === 'admin')

        <!-- Row with Two Large Empty Cards -->
<div class="row">
    <!-- First Large Empty Card -->
    <div class="col-md-6 col-lg-6 col-xl-6 mb-4">
        <div class="card overflow-hidden mb-4 statisticsDivSmall2">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <i class="fas fa-tools fa-3x mb-3 text-warning"></i>
                <h5 class="card-title">Still in Development</h5>
                <p class="card-text text-muted">We're working on this feature. Stay tuned!</p>
            </div>
        </div>
    </div>

    <!-- Second Large Empty Card -->
    <div class="col-md-6 col-lg-6 col-xl-6 mb-4">
        <div class="card overflow-hidden mb-4 statisticsDivSmall2">
            <div class="card-header pt-3 pb-1">
                <div class="card-title d-flex justify-content-between mb-2">
                    <h5 class="m-0">Pack Statistics</h5>
                    <div>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="View more details">
                            <i class='bx bx-file'></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="statisticsadmin" style="padding: 20px;"></div>
            </div>
        </div>
    </div>

</div>



@endif


       @if (auth()->user()->role->rolename === 'user')
       <div class="row">
        <!-- Left Column: Two Cards Stack -->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="row">
                <!-- First Card -->
                <div class="col-12 col-yl-6 mb-4">
                    <div class="card overflow-hidden mb-2 statisticsDivSmall">
                        <div class="card-header pt-3 pb-1">
                            <!-- First Chart Section -->
                            <div class="card-title d-flex justify-content-between mb-0">
                                <h5 class="m-0 me-2"><?= get_label('ca / products categories', 'CA / Products categories') ?></h5>
                                <div>
                                    <span data-bs-toggle="modal" data-bs-target="#create_todo_modal">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view commandes', 'View Commandes') ?>">
                                            <i class='bx bx-box'></i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div id="caparcategorieproduit"></div>
                                <div id="no-data-produits" class="text-center badge bg-primary d-none"><?= get_label('There are no commandes yet', 'There are no commandes yet') ?></div>

                            </div>



                        </div>

                    </div>
                </div>

                <!-- Second Card -->
                <div class="col-12 col-yl-6 mb-4">
                    <div class="card overflow-hidden mb-2 statisticsDivSmall">
                        <div class="card-header pt-3 pb-1">
                            <!-- Second Chart Section -->
                            <div class="card-title d-flex justify-content-between mb-0">
                                <h5 class="m-0 me-2"><?= get_label('commandes status', 'Commandes status') ?></h5>
                                <div>
                                    <span data-bs-toggle="modal" data-bs-target="#create_todo_modal">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view commandes', 'View Commandes') ?>">
                                            <i class='bx bx-box'></i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex  justify-content-center">
                                <div id="caparcategorie"></div>
                                <div id="no-data-categories" class="text-center badge bg-primary d-none"><?= get_label('There are no commandes yet', 'There are no commandes yet') ?></div>
                            </div>


                            </div>


                    </div>
                </div>


            </div>
        </div>

        <!-- Right Column: Large Card -->
        <div class="col-md-6 col-lg-8 col-xl-8 mb-4">
            <div class="card overflow-hidden mb-4 statisticsDiv">
                <div class="card-header pt-3 pb-1">


                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">

                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-ca" aria-controls="navs-top-ca" aria-selected="true">
                                <i class="menu-icon tf-icons  bx bx-pulse text-info"></i>  <?= get_label('general', 'General') ?>
                            </button>
                        </li>
                        <li class="nav-item">

                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-client" aria-controls="navs-top-client" aria-selected="false">
                                <i class="menu-icon tf-icons bx bx-user text-warning"></i>  <?= get_label('client', 'Client') ?>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-top-ca" role="tabpanel">
                            <div class="card-title d-flex justify-content-between mb-4">
                                <h5 class="m-0 me-2"><?= get_label('sale revenue', 'Sale revenue') ?></h5>
                                <div>
                                    <span data-bs-toggle="modal" data-bs-target="#create_todo_modal">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view_factures', 'View factures') ?>">
                                            <i class='bx bx-file'></i>
                                        </a>
                                    </span>
                                    <a href="/todos">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_clients', 'View Clients') ?>">
                                            <i class="bx bx-user"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="my-3">
                                <div class="form-group">
                                    <label for="grouping"><?= get_label('Group by:', 'Group by:') ?></label>
                                    <select id="grouping" class="form-control">
                                        <option value="day"><?= get_label('Day', 'Day') ?></option>
                                        <option value="month" selected><?= get_label('Month', 'Month') ?></option>
                                        <option value="year"><?= get_label('Total', 'Total') ?></option>
                                    </select>
                                </div>

                                <div class="form-group" >
                                    <label for="yearSelect"><?= get_label('Year:', 'Year:') ?></label>
                                    <input type="number" id="yearSelect" class="form-control" value="{{ now()->year }}" min="2000" max="{{ now()->year }}" style="margin-bottom: 15px">
                                </div>

                                <div id="chart"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-client" role="tabpanel">


                            <div class="card-title d-flex justify-content-between mb-4">
                                <h5 class="m-0 me-2"><?= get_label('Clients sale revenue', 'Clients Sale revenue') ?></h5>
                                <div>
                                    <span data-bs-toggle="modal" data-bs-target="#create_todo_modal">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view_factures', 'View factures') ?>">
                                            <i class='bx bx-file'></i>
                                        </a>
                                    </span>
                                    <a href="/todos">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_clients', 'View Clients') ?>">
                                            <i class="bx bx-user"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>


                            <div class="my-3">
                                <div class="form-group">
                                    <label for="clientSelect"><?= get_label('Select Client:', 'Select Client:') ?></label>
                                    <select id="clientSelect" class="form-control">
                                        <!-- Options dynamically generated with Laravel -->
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">
                                            @if ($client->denomenation)
                                                {{ $client->denomenation . " - " }}
                                            @endif
                                            @if ($client->first_name)
                                                {{ $client->first_name }} {{ $client->last_name }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="clientGrouping"><?= get_label('Group by:', 'Group by:') ?></label>
                                    <select id="clientGrouping" class="form-control">
                                        <option value="day"><?= get_label('Day', 'Day') ?></option>
                                        <option value="month" selected><?= get_label('Month', 'Month') ?></option>
                                        <option value="year"><?= get_label('Total', 'Total') ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="yearSelect"><?= get_label('Year:', 'Year:') ?></label>
                                    <input type="number" id="clientYearSelect" class="form-control" value="{{ date('Y') }}" min="2000" max="{{ date('Y') }}" style="margin-bottom: 15px">
                                </div>


                                <div id="clientChart"></div>
                            </div>


                        </div>
                    </div>






                </div>

            </div>
        </div>
    </div>

       @endif

    </div>

</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let clientChart; // Declare chart variable for client-specific data

            function fetchClientDataAndRenderChart(groupBy, year, clientId) {
                fetch(`/client-chiffre-affaires?group_by=${groupBy}&year=${year}&client_id=${clientId}`)
                    .then(response => response.json())
                    .then(data => {
                        const periods = data.map(item => item.period);
                        const totals = data.map(item => parseFloat(item.total));

                        const options = {
                            series: [{
                                name: 'Chiffre d\'Affaires',
                                type: 'area',
                                data: totals
                            }],
                            chart: {
                                height: 350,
                                type: 'area',
                            },
                            stroke: {
                                curve: 'smooth'
                            },
                            fill: {
                                type: 'solid',
                                opacity: 0.35
                            },
                            labels: periods,
                            markers: {
                                size: 0
                            },
                            yaxis: {
                                title: {
                                    text: 'Chiffre d\'Affaires',
                                },
                            },
                            colors: ['#FFA500'],
                            tooltip: {
                                shared: true,
                                intersect: false,
                                y: {
                                    formatter: function (y) {
                                        if (typeof y !== "undefined") {
                                            return y.toFixed(2) + " DH";
                                        }
                                        return y;
                                    }
                                }
                            }
                        };

                        // Check if chart already exists, if so, destroy it
                        if (clientChart) {
                            clientChart.destroy();
                        }

                        // Create a new chart
                        clientChart = new ApexCharts(document.querySelector("#clientChart"), options);
                        clientChart.render();
                    });
            }

            // Get current year
            const currentYear = new Date().getFullYear();

            // Initial load with default grouping by month, current year, and selected client
            const initialClientId = document.getElementById('clientSelect').value;
            fetchClientDataAndRenderChart('month', currentYear, initialClientId);

            // Update chart when grouping, year, or client selection changes
            document.getElementById('clientGrouping').addEventListener('change', function () {
                const groupBy = this.value;
                const year = document.getElementById('clientYearSelect').value;
                const clientId = document.getElementById('clientSelect').value;
                fetchClientDataAndRenderChart(groupBy, year, clientId);
            });

            document.getElementById('clientYearSelect').addEventListener('change', function () {
                const year = this.value;
                const groupBy = document.getElementById('clientGrouping').value;
                const clientId = document.getElementById('clientSelect').value;
                fetchClientDataAndRenderChart(groupBy, year, clientId);
            });

            document.getElementById('clientSelect').addEventListener('change', function () {
                const clientId = this.value;
                const groupBy = document.getElementById('clientGrouping').value;
                const year = document.getElementById('clientYearSelect').value;
                fetchClientDataAndRenderChart(groupBy, year, clientId);
            });
        });
        </script>





    <script>
        $(document).ready(function() {
            // First Chart for #caparcategorie
            $.ajax({
                url: '/get-chiffre-affaire',
                method: 'GET',
                success: function(response) {
                    if (response.length === 0) {
                        // Show 'no data' badge for categories
                        $('#no-data-categories').removeClass('d-none');
                    } else {
                        // Extract categories and percentages
                        var categories = response.map(function(item) {
                            return item.categorie;
                        });

                        var percentages = response.map(function(item) {
                            return item.percentage;
                        });

                        // Define the chart options
                        var options = {
                            series: percentages,
                            labels: categories,
                            chart: {
                                type: 'donut',
                                height: 270,
                                width: 270,
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 200
                                    },
                                }
                            }],
                            legend: {
                                position: 'bottom'
                            }
                        };

                        // Render the chart in #caparcategorie
                        var chart = new ApexCharts(document.querySelector("#caparcategorie"), options);
                        chart.render();
                    }
                },
                error: function(error) {
                    console.log("Error fetching data for #caparcategorie:", error);
                }
            });

            // Second Chart for #caparcategorieproduit
            $.ajax({
                url: '/get-chiffre-affaire-produits', // Adjust the URL for the second data source
                method: 'GET',
                success: function(response) {
                    if (response.length === 0) {
                        // Show 'no data' badge for products
                        $('#no-data-produits').removeClass('d-none');
                    } else {
                        // Extract categories and percentages for products
                        var productCategories = response.map(function(item) {
                            return item.productCategorie;
                        });

                        var productPercentages = response.map(function(item) {
                            return item.productPercentage;
                        });

                        // Define the chart options for #caparcategorieproduit
                        var productOptions = {
                            series: productPercentages,
                            labels: productCategories,
                            chart: {
                                type: 'donut',
                                height: 270,
                                width: 270,
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 200
                                    },
                                }
                            }],
                            legend: {
                                position: 'bottom'
                            }
                        };

                        // Render the chart in #caparcategorieproduit
                        var productChart = new ApexCharts(document.querySelector("#caparcategorieproduit"), productOptions);
                        productChart.render();
                    }
                },
                error: function(error) {
                    console.log("Error fetching data for #caparcategorieproduit:", error);
                }
            });
        });
    </script>


    <script>
  document.addEventListener('DOMContentLoaded', function () {
    let chart; // Declare chart variable outside the function to reuse it

    function fetchDataAndRenderChart(groupBy, year) {
        fetch(`/chiffre-affaires?group_by=${groupBy}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                const periods = data.map(item => item.period);
                const totals = data.map(item => parseFloat(item.total));

                const options = {
                    series: [{
                        name: 'Chiffre d\'Affaires',
                        type: 'area',
                        data: totals
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    fill: {
                        type: 'solid',
                        opacity: 0.35
                    },
                    labels: periods,
                    markers: {
                        size: 0
                    },
                    yaxis: {
                        title: {
                            text: 'Chiffre d\'Affaires',
                        },
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(2) + " DH";
                                }
                                return y;
                            }
                        }
                    }
                };

                // Check if chart already exists, if so, destroy it
                if (chart) {
                    chart.destroy();
                }

                // Create a new chart
                chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            });
    }

    // Get current year
    const currentYear = new Date().getFullYear();

    // Initial load with default grouping by month and current year
    fetchDataAndRenderChart('month', currentYear);

    // Update chart when grouping or year selection changes
    document.getElementById('grouping').addEventListener('change', function () {
        const groupBy = this.value;
        const year = document.getElementById('yearSelect').value;
        fetchDataAndRenderChart(groupBy, year);
    });

    document.getElementById('yearSelect').addEventListener('change', function () {
        const year = this.value;
        const groupBy = document.getElementById('grouping').value;
        fetchDataAndRenderChart(groupBy, year);
    });
});

    </script>


<script>
    $(document).ready(function() {
        // AJAX call to get pack statistics data
        $.ajax({
            url: '/pack-statistics',
            method: 'GET',
            success: function(response) {
                // Prepare the data for the chart
                var seriesData = response.map(pack => pack.entreprises_count);
                var categoriesData = response.map(pack => pack.name);

                // Chart options
                var options = {
                    series: [{
                        data: seriesData
                    }],
                    chart: {
                        height: 330,
                        type: 'bar',
                        events: {
                            click: function(chart, w, e) {
                                // Handle click event
                            }
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26A69A', '#D10CE8'], // Example colors
                    plotOptions: {
                        bar: {
                            columnWidth: '28%',
                            distributed: true,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: categoriesData,
                        labels: {
                            style: {
                                colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26A69A', '#D10CE8'], // Example colors
                                fontSize: '12px'
                            }
                        }
                    },

                    yaxis: {
                        title: {
                            text: 'Entreprises', // Y-axis label
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        }
                    }
                };

                // Render the chart
                var chart = new ApexCharts(document.querySelector("#statisticsadmin"), options);
                chart.render();
            },
            error: function(error) {
                console.error('Error fetching pack statistics:', error);
            }
        });
    });
</script>




<script src="{{asset('assets/js/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@else
<div class="w-100 h-100 d-flex align-items-center justify-content-center"><span><?= get_label('You must', 'You must') ?> <a href="/login"><?= get_label('Log in', 'Log in') ?></a> <?= get_label('or', 'or') ?> <a href="/register"><?= get_label('Register', 'Register') ?></a> <?= get_label('to access', 'to access') ?> HARMORA !</span></div>
@endauth
@endsection
