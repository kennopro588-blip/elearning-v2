@extends(config('asset.view_admin')('admin_layout'))
@section('content')
<section class="content">
    <div class="row">
        <div id="notify" class="col-lg-12">
            <!-- Thông báo -->
        </div>
    </div>

    <h4 class="text-capitalize">Thông tin hệ thống</h4>
    
    {{-- Dashboard cho Admin --}}
    @if(session('admin_role') == 1)
        <div class="row">
            <!-- Tài khoản -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tài khoản</span>
                        <span class="info-box-number">{{ $data['count_account'] ?? 0 }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <i class="fa fa-plus-circle"></i> {{ $data['new_accounts_last_month'] ?? 0 }} mới trong 30 ngày
                        </span>
                    </div>
                </div>
            </div>

            <!-- Giảng viên -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Giảng viên</span>
                        <span class="info-box-number">{{ $data['count_teacher'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Sinh viên -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sinh viên</span>
                        <span class="info-box-number">{{ $data['count_student'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Hoạt động -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-sign-in"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Hoạt động 7 ngày</span>
                        <span class="info-box-number">{{ $data['active_last_week'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Bộ môn -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bộ môn</span>
                        <span class="info-box-number">{{ $data['count_subject'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Học phần -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fa fa-folder"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Học phần</span>
                        <span class="info-box-number">{{ $data['count_course'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Lớp học phần -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange">
                    <span class="info-box-icon"><i class="fa fa-university"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lớp học phần</span>
                        <span class="info-box-number">{{ $data['count_class'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Bài kiểm tra -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-maroon">
                    <span class="info-box-icon"><i class="fa fa-file-text"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bài kiểm tra</span>
                        <span class="info-box-number">{{ $data['count_test'] ?? 0 }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $data['test_completion_rate'] ?? 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $data['test_completion_rate'] ?? 0 }}% tỷ lệ hoàn thành
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ và thống kê chi tiết -->
        <div class="row">
            <!-- Top 5 học phần -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-trophy"></i> Top 5 Học phần có nhiều lớp nhất</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['top_courses']) && count($data['top_courses']) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên học phần</th>
                                        <th class="text-center">Số lớp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['top_courses'] as $index => $course)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $course->ten_hp }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-blue">{{ $course->total }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top 5 giảng viên -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-star"></i> Top 5 Giảng viên có nhiều bài giảng</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['top_teachers']) && count($data['top_teachers']) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Giảng viên</th>
                                        <th class="text-center">Số bài giảng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['top_teachers'] as $index => $teacher)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $teacher->ho_ten }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-green">{{ $teacher->total }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Hàng 2: Top lớp và đánh giá -->
        <div class="row">
            <!-- Top 5 lớp có nhiều sinh viên -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Top 5 Lớp có nhiều sinh viên</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['top_classes_by_students']) && count($data['top_classes_by_students']) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên lớp</th>
                                        <th class="text-center">Số SV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['top_classes_by_students'] as $index => $class)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $class->ten_lhp }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-yellow">{{ $class->total }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top 5 lớp được đánh giá cao -->
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-heart"></i> Top 5 Lớp được đánh giá cao nhất</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['top_rated_classes']) && count($data['top_rated_classes']) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên lớp</th>
                                        <th class="text-center">Đánh giá</th>
                                        <th class="text-center">Lượt đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['top_rated_classes'] as $index => $class)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $class->ten_lhp }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-red">
                                                <i class="fa fa-star"></i> {{ number_format($class->avg_rating, 1) }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $class->total_reviews }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê sinh viên theo bộ môn -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Thống kê sinh viên theo bộ môn</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['students_by_department']) && count($data['students_by_department']) > 0)
                            <div class="chart-responsive">
                                <canvas id="studentsByDepartmentChart" height="80"></canvas>
                            </div>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($data['students_by_department']) && count($data['students_by_department']) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chuẩn bị dữ liệu
        var departments = @json($data['students_by_department']->pluck('ten_bm'));
        var totals = @json($data['students_by_department']->pluck('total'));

        // Tạo biểu đồ
        var ctx = document.getElementById('studentsByDepartmentChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // Có thể đổi thành 'pie', 'line', 'doughnut'...
            data: {
                labels: departments,
                datasets: [{
                    label: 'Số lượng sinh viên',
                    data: totals,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Phân bố sinh viên theo bộ môn'
                    }
                }
            }
        });
    });
</script>
@endif

        <!-- Thống kê bài kiểm tra theo tháng -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-line-chart"></i> Bài kiểm tra 6 tháng gần nhất</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['tests_by_month']) && count($data['tests_by_month']) > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-light-blue">
                                        <th>Tháng</th>
                                        <th class="text-center">Số bài kiểm tra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['tests_by_month'] as $test)
                                    <tr>
                                        <td>{{ $test->month }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-blue">{{ $test->total }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có dữ liệu</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tương tác -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-comments"></i> Hoạt động tương tác</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $data['interactions_last_month'] ?? 0 }}</h5>
                                    <span class="description-text">Bình luận/Tương tác trong 30 ngày</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $data['test_completion_rate'] ?? 0 }}%</h5>
                                    <span class="description-text">Tỷ lệ hoàn thành bài kiểm tra</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Dashboard cho Giảng viên --}}
    @if(session('admin_role') == 2)
        <div class="row">
            <!-- Lớp học phần -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-university"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lớp học phần</span>
                        <span class="info-box-number">{{ $data['count_class'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Bài giảng -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bài giảng</span>
                        <span class="info-box-number">{{ $data['count_lesson'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Bài -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bài</span>
                        <span class="info-box-number">{{ $data['count_content'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Bài kiểm tra -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-pencil"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bài kiểm tra</span>
                        <span class="info-box-number">{{ $data['count_test'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Sinh viên -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng sinh viên</span>
                        <span class="info-box-number">{{ $data['total_students'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Đánh giá TB -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange">
                    <span class="info-box-icon"><i class="fa fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Đánh giá TB</span>
                        <span class="info-box-number">{{ $data['average_rating'] ?? 0 }}/5</span>
                    </div>
                </div>
            </div>

            <!-- Bài nộp 7 ngày -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bài nộp (7 ngày)</span>
                        <span class="info-box-number">{{ $data['submissions_last_week'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách lớp của giảng viên -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Lớp học phần của tôi</h3>
                    </div>
                    <div class="box-body">
                        @if(!empty($data['my_classes']) && count($data['my_classes']) > 0)
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã lớp</th>
                                        <th>Tên lớp</th>
                                        <th>Học phần</th>
                                        <th class="text-center">Số SV</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['my_classes'] as $class)
                                    <tr>
                                        <td>{{ $class->ma_lhp }}</td>
                                        <td><strong>{{ $class->ten_lhp }}</strong></td>
                                        <td>{{ $class->ten_hp }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-blue">{{ $class->student_count }}</span>
                                        </td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($class->ngay_tao)) }}</td>
                                        <td class="text-center">
                                            @if($class->trang_thai == 1)
                                                <span class="label label-success">Hoạt động</span>
                                            @else
                                                <span class="label label-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">Chưa có lớp nào</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bài kiểm tra sắp hết hạn -->
        @if(!empty($data['upcoming_tests']) && count($data['upcoming_tests']) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-clock-o"></i> Bài kiểm tra sắp hết hạn (7 ngày tới)</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Lớp</th>
                                    <th class="text-center">Bắt đầu</th>
                                    <th class="text-center">Hạn nộp</th>
                                    <th class="text-center">Còn lại</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['upcoming_tests'] as $test)
                                <tr>
                                    <td><strong>{{ $test->tieu_de }}</strong></td>
                                    <td>{{ $test->ten_lhp }}</td>
                                    <td class="text-center">{{ date('d/m/Y H:i', strtotime($test->bat_dau)) }}</td>
                                    <td class="text-center">
                                        <span class="text-danger">
                                            <strong>{{ date('d/m/Y H:i', strtotime($test->han_nop)) }}</strong>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $now = now();
                                            $deadline = \Carbon\Carbon::parse($test->han_nop);
                                            $diff = $now->diffInDays($deadline);
                                        @endphp
                                        <span class="label {{ $diff <= 2 ? 'label-danger' : 'label-warning' }}">
                                            {{ $diff }} ngày
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tương tác gần đây -->
        @if(!empty($data['recent_interactions']) && count($data['recent_interactions']) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-comments"></i> Tương tác gần đây (30 ngày)</h3>
                    </div>
                    <div class="box-body">
                        <ul class="timeline">
                            @foreach($data['recent_interactions'] as $interaction)
                            <li>
                                <i class="fa fa-comment bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($interaction->ngay_tao)->diffForHumans() }}
                                    </span>
                                    <h3 class="timeline-header">
                                        <strong>{{ $interaction->ho_ten }}</strong> đã bình luận trong 
                                        <a href="#">{{ $interaction->ten_lhp }}</a>
                                    </h3>
                                    <div class="timeline-body">
                                        {{ Str::limit($interaction->noi_dung, 150) }}
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    @if(session('admin_role') == 1 && !empty($data['students_by_department']))
    // Biểu đồ sinh viên theo bộ môn
    var ctx = document.getElementById('studentsByDepartmentChart').getContext('2d');
    var chartData = {
        labels: [
            @foreach($data['students_by_department'] as $dept)
                '{{ $dept->ten_bm }}',
            @endforeach
        ],
        datasets: [{
            label: 'Số sinh viên',
            data: [
                @foreach($data['students_by_department'] as $dept)
                    {{ $dept->total }},
                @endforeach
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
                'rgba(199, 199, 199, 0.6)',
                'rgba(83, 102, 255, 0.6)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Phân bố sinh viên theo bộ môn'
                }
            }
        }
    });
    @endif
</script>
@endpush
@endsection