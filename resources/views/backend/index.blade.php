@extends('backend.master')

@section('title', 'Dashboard')

@section('content')
<section class="content">
    @can('dashboard_view')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sale SubTotal</span>
                        <span class="info-box-number">
                            {{currency()->symbol??''}} {{number_format($sub_total,2,'.',',')}}
                            <small></small>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sale</span>
                        <span class="info-box-number">{{currency()->symbol??''}} {{number_format($total,2,'.',',')}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sale Due </span>
                        <span class="info-box-number">{{currency()->symbol??''}} </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $sub_total }}</h3>
                            <p>Sale SubTotal</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $total }}</h3>
                            <p>Sale</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $total_customer }}</h3>
                            <p>Customers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $total_product }}</h3>
                            <p>Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->


        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daily Total Sales <small>{{ $dateRange }}</small></h5>
                        <!-- <button id="closeDayBtn" class="btn btn-warning btn-sm float-right">Close Day</button> -->

                        <div class="input-group w-auto">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="reservation" style="width: 180px;">
                        </div>
                    </div>

                    <div class="card-body">
                        <canvas id="dailySaleLineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Monthly Total Sales <small>for {{ $currentYear }}</small></h5>
                    </div>
                    <div class="card-body">
                        <canvas id="barChartYear"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    <!-- /.container-fluid -->
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#closeDayBtn').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to close the current business day and advance to the next!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, close day!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('backend.admin.close.day') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Closed!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to close day: ' + (xhr.responseJSON.message || 'Unknown error'),
                                'error'
                            );
                        }
                    });
                }
            })
        });
    });
</script>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailySaleChart = document.getElementById('dailySaleLineChart');
    const barChartYear = document.getElementById('barChartYear');

    new Chart(dailySaleChart, {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: 'Sales',
                data: @json($totalAmounts),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(barChartYear, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Sales',
                data: @json($totalAmountMonth),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    $(function() {
        //Date range picker
        $('#reservation').daterangepicker().on('apply.daterangepicker', function(e, picker) {
            let selectedDateRange = picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD');

            // Update URL with daterange query parameter
            let url = new URL(window.location.href);
            url.searchParams.set('daterange', selectedDateRange);
            window.location.href = url.toString();
        });

    })
</script>
@endpush