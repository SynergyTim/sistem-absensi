@extends('layouts')

@section('title', 'Dashboard Absensi')
@section('content')
<div class="row">
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Data Siswa</div>
                        <div class="text-lg fw-bold">..</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="calendar"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#!">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Data Kelas</div>
                        <div class="text-lg fw-bold">..</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="dollar-sign"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#!">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Absensi</div>
                        <div class="text-lg fw-bold">..</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="check-square"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#!">View Tasks</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">History Absensi</div>
                        <div class="text-lg fw-bold">..</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#!">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>
<!-- Example Charts for Dashboard Demo-->
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                Earnings Breakdown
                <div class="dropdown no-caret">
                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                        <a class="dropdown-item" href="#!">Last 12 Months</a>
                        <a class="dropdown-item" href="#!">Last 30 Days</a>
                        <a class="dropdown-item" href="#!">Last 7 Days</a>
                        <a class="dropdown-item" href="#!">This Month</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#!">Custom Range</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                Monthly Revenue
                <div class="dropdown no-caret">
                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                        <a class="dropdown-item" href="#!">Last 12 Months</a>
                        <a class="dropdown-item" href="#!">Last 30 Days</a>
                        <a class="dropdown-item" href="#!">Last 7 Days</a>
                        <a class="dropdown-item" href="#!">This Month</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#!">Custom Range</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-bar"><canvas id="myBarChart" width="100%" height="30"></canvas></div>
            </div>
        </div>
    </div>
</div>
<!-- Example DataTable for Dashboard Demo-->
<div class="card mb-4">
    <div class="card-header">Semua Data Siswa</div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
</div>
    @endsection