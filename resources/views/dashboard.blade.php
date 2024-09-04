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
                            <img src="{{ auth()->user()->entreprise->photo ? asset('storage/' . auth()->user()->entreprise->photo) : asset('storage/photos/no-image.jpg') }}" alt="Profile Picture" class="profile-pic">
                            <h2 class="admin-dashboard-title">Welcome to Your Dashboard</h2>
                            <p class="admin-dashboard-description">Here you can manage your Entreprise, view statistics, and perform tasks.</p>
                         </div>
                    @elseif(auth()->user()->role->rolename === 'admin')
                        {{-- <div class="profile-container">
                            <img src="https://media.licdn.com/dms/image/D4D0BAQEtnLWLnSV-Uw/company-logo_400_400/0/1711586810808?e=2147483647&v=beta&t=05K3VHWrqPONa3p9MeMj4XrLFKZPRts1wWiAiPO14aA" alt="Profile Picture" class="profile-pic">
                            <h3 class="profile-name">HARMORA</h3>
                            <p class="profile-email">admin</p>
                        </div> --}}

                        <div class="admin-dashboard-card">




                            <img src="{{asset("assets/img/logos/Logo.png")}}" alt="Profile Picture" class="profile-pic">
                            <h2 class="admin-dashboard-title">Welcome to Your Admin Dashboard</h2>
                            <p class="admin-dashboard-description">Here you can manage your site, view statistics, and perform administrative tasks.</p>
                        </div>

                    @endif




                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-success"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('ca', 'CA') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>

                        <a href="/{{getUserPreferences('projects', 'default_view')}}"><small class="text-success fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>

                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-task bx-md text-primary"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_tasks', 'Total tasks') ?></span>
                        <h3 class="card-title mb-2">{{$tasks}}</h3>

                        <a href="/{{getUserPreferences('tasks', 'default_view')}}"><small class="text-primary fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>

                    </div>
                </div>
            </div>
            @if (!isClient())
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bxs-user-detail bx-md text-warning"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_users', 'Total users') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($users) && count($users) > 0?count($users):0}}</h3>

                        <a href="/users"><small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>

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
                        <h3 class="card-title mb-2"> {{is_countable($clients) && count($clients) > 0?count($clients):0}}</h3>

                        <a href="/clients"><small class="text-info fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>

                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-shape-polygon text-success bx-md text-warning"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_meetings', 'Total meetings') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($meetings) && count($meetings) > 0?count($meetings):0}}</h3>

                        <a href="/meetings"><small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>

                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-list-check bx-md text-info"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_todos', 'Total todos') ?></span>
                        <h3 class="card-title mb-2"> {{is_countable($total_todos) && count($total_todos) > 0?count($total_todos):0}}</h3>
                        <a href="/todos"><small class="text-info fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row">

            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card overflow-hidden mb-4 statisticsDiv">
                    <div class="card-header pt-3 pb-1">
                        <div class="card-title d-flex justify-content-between mb-0">
                            <h5 class="m-0 me-2"><?= get_label('status des commandes', 'Status des commandes') ?></h5>
                          <div>
                                <span data-bs-toggle="modal" data-bs-target="#create_todo_modal"><a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view commandes', 'View COmmandes') ?>"><i class='bx bx-box'></i></a></span>

                            </div>
                        </div>
                        <div class="my-3 mt-5">
                            <div id="caparcategorie"></div>
                        </div>
                        <div class="card-title d-flex justify-content-between mb-0">


                        </div>
                    </div>
                </div>
            </div>




            <div class="col-md-6 col-lg-4 col-xl-8 order-0 mb-4">
                <div class="card overflow-hidden mb-4 statisticsDiv">
                    <div class="card-header pt-3 pb-1">
                        <div class="card-title d-flex justify-content-between mb-0">
                            <h5 class="m-0 me-2"><?= get_label('sales revenue', 'Sales revenue') ?></h5>
                          <div>
                                <span data-bs-toggle="modal" data-bs-target="#create_todo_modal"><a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('view_factures', 'View factures') ?>"><i class='bx bx-file'></i></a></span>
                                <a href="/todos"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_clients', 'View Clients') ?>"><i class="bx bx-user"></i></button></a>
                            </div>
                        </div>
                        <div class="my-3">
                            <div class="form-group">
                                <label for="grouping">Group by:</label>
                                <select id="grouping" class="form-control">
                                    <option value="day">Day</option>
                                    <option value="month" selected>Month</option>
                                    <option value="year">Total</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="yearSelect">Year:</label>
                                <input type="number" id="yearSelect" class="form-control" value="{{ now()->year }}" min="2000" max="{{ now()->year }}">
                            </div>

                            <div id="chart"></div>
                        </div>

                    </div>
                    <div class="card-body" id="todos-statistics">
                        <ul class="p-0 m-0">
                            @if (is_countable($todos) && count($todos) > 0)

                            @else
                            <div class=" h-100 d-flex justify-content-center align-items-center">
                                <div>
                                    <?= get_label('factures not found', 'Factures not found!') ?>
                                </div>
                            </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/get-chiffre-affaire',
                method: 'GET',
                success: function(response) {
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
                        chart: {
                            type: 'donut',
                        },
                        labels: categories,
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    // Render the chart
                    var chart = new ApexCharts(document.querySelector("#caparcategorie"), options);
                    chart.render();
                },
                error: function(error) {
                    console.log("Error fetching data:", error);
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






    <!-- ------------------------------------------- -->
    <?php
    $titles = [];
    $project_counts = [];
    $task_counts = [];
    $bg_colors = [];
    $total_projects = 0;
    $total_tasks = 0;
    $total_todos = count($todos);
    $done_todos = 0;
    $pending_todos = 0;
    $todo_counts = [];
    $ran = array(
        '#63ed7a', '#ffa426', '#fc544b', '#6777ef', '#FF00FF', '#53ff1a', '#ff3300', '#0000ff', '#00ffff', '#99ff33', '#003366',
        '#cc3300', '#ffcc00', '#ff9900', '#3333cc', '#ffff00', '#FF5733', '#33FF57', '#5733FF', '#FFFF33', '#A6A6A6', '#FF99FF',
        '#6699FF', '#666666', '#FF6600', '#9900CC', '#FF99CC', '#FFCC99', '#99CCFF', '#33CCCC', '#CCFFCC', '#99CC99', '#669999',
        '#CCCCFF', '#6666FF', '#FF6666', '#99CCCC', '#993366', '#339966', '#99CC00', '#CC6666', '#660033', '#CC99CC', '#CC3300',
        '#FFCCCC', '#6600CC', '#FFCC33', '#9933FF', '#33FF33', '#FFFF66', '#9933CC', '#3300FF', '#9999CC', '#0066FF', '#339900',
        '#666633', '#330033', '#FF9999', '#66FF33', '#6600FF', '#FF0033', '#009999', '#CC0000', '#999999', '#CC0000', '#CCCC00',
        '#00FF33', '#0066CC', '#66FF66', '#FF33FF', '#CC33CC', '#660099', '#663366', '#996666', '#6699CC', '#663399', '#9966CC',
        '#66CC66', '#0099CC', '#339999', '#00CCCC', '#CCCC99', '#FF9966', '#99FF00', '#66FF99', '#336666', '#00FF66', '#3366CC',
        '#CC00CC', '#00FF99', '#FF0000', '#00CCFF', '#000000', '#FFFFFF'
    );

    $titles = implode(",", $titles);
    $bg_colors = implode(",", $bg_colors);

    foreach ($todos as $todo) {
        $todo->is_completed ? $done_todos += 1 : $pending_todos += 1;
    }
    array_push($todo_counts, $done_todos);
    array_push($todo_counts, $pending_todos);
    $todo_counts = implode(",", $todo_counts);
    ?>
</div>
<script>
    var labels = [<?= $titles ?>];
    var bg_colors = [<?= $bg_colors ?>];
    var total_todos = [<?= $total_todos ?>];
    var todo_data = [<?= $todo_counts ?>];
    //labels
    var done = '<?= get_label('done', 'Done') ?>';
    var pending = '<?= get_label('pending', 'Pending') ?>';
    var total = '<?= get_label('total', 'Total') ?>';
</script>
<script src="{{asset('assets/js/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@else
<div class="w-100 h-100 d-flex align-items-center justify-content-center"><span>You must <a href="/login">Log in</a> or <a href="/register">Register</a> to access {{$general_settings['company_title']}}!</span></div>
@endauth
@endsection
