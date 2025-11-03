<?php

namespace App\Http\Controllers;

use App\Models\Bai;
use App\Models\BaiGiang;
use App\Models\BaiKiemTra;
use App\Models\BoMon;
use App\Models\HocPhan;
use App\Models\LopHocPhan;
use Hash;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Models\Taikhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends LayoutController
{
    function login()
    {
        // Session::flush();

        return view(config('asset.view_admin_page')('form-login'));
    }
    function admin_index(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $args = array();
        $args['is_admin'] = true;
        $result = (new Taikhoan)->check_login($args, $email);
        if ($result && Hash::check($password, $result->password)) {
            Session::put('admin_name', $result->ho_ten);
            Session::put('admin_id', $result->ma_tk);
            Session::put('admin_role', $result->vai_tro);
            Session::put('admin_joined', $result->ngay_tao);

            Session::put('count_account', (Taikhoan::count()));
            Session::put('count_subject', (BoMon::count()));
            Session::put('count_course', (HocPhan::count()));
            Session::put('count_class', (LopHocPhan::count()));

            if ($result->vai_tro == 2) {
                $args = array();
                $args['ma_tk'] = $result->ma_tk;
                Session::put('count_class', count((new LopHocPhan())->gets($args)));
                Session::put('count_lesson', count((new BaiGiang())->gets($args)));
                $args['ma_gv'] = $result->ma_tk;
                Session::put('count_content', count((new Bai())->gets($args)));
                Session::put('count_test', count((new BaiKiemTra())->gets($args)));
            }

            // print_r(session()->all());
            Session::forget('message');
            return Redirect::to('/dashboard');
        }
        Session::put('message', 'Tài khoản hoặc mật khẩu không đúng!');
        return Redirect::to('/admin');
    }
    // function show_dashboard()
    // {
    //     return $this->_auth_login() ?? view(config('asset.view_admin_page')('main'));
    // }


    

    public function show_dashboard()
    {
        // Kiểm tra đăng nhập
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $role = Session::get('admin_role');
        $adminId = Session::get('admin_id');

        $data = [];

        // Thống kê theo vai trò
        if ($role == 1) { // Admin
            $data = $this->getAdminStatistics();
        } elseif ($role == 2) { // Giảng viên
            $data = $this->getTeacherStatistics($adminId);
        }

        return view(config('asset.view_admin_page')('main'), compact('data'));
    }

    /**
     * Thống kê cho Admin
     */
    private function getAdminStatistics()
    {
        $data = [];

        // 1. Tổng số tài khoản
        $data['count_account'] = DB::table('taikhoan')
            ->where('trang_thai', 1)
            ->count();

        // 2. Tổng số giảng viên (vai_tro = 2)
        $data['count_teacher'] = DB::table('taikhoan')
            ->where('vai_tro', 2)
            ->where('trang_thai', 1)
            ->count();

        // 3. Tổng số sinh viên (vai_tro = 3)
        $data['count_student'] = DB::table('taikhoan')
            ->where('vai_tro', 3)
            ->where('trang_thai', 1)
            ->count();

        // 4. Số người hoạt động trong 7 ngày qua
        $data['active_last_week'] = DB::table('taikhoan')
            ->where('trang_thai_dang_nhap', 1)
            ->where('ngay_tao', '>=', now()->subDays(7))
            ->count();

        // 5. Tổng bộ môn
        $data['count_subject'] = DB::table('bo_mon')
            ->where('trang_thai', 1)
            ->count();

        // 6. Tổng học phần
        $data['count_course'] = DB::table('hoc_phan')
            ->where('trang_thai', 1)
            ->count();

        // 7. Tổng lớp học phần
        $data['count_class'] = DB::table('lop_hoc_phan')
            ->where('trang_thai', 1)
            ->count();

        // 8. Tổng bài kiểm tra
        $data['count_test'] = DB::table('bai_kiem_tra')
            ->where('trang_thai', 1)
            ->count();

        // 9. Top 5 học phần có nhiều lớp nhất
        $data['top_courses'] = DB::table('lop_hoc_phan as lhp')
            ->join('hoc_phan as hp', 'lhp.ma_hp', '=', 'hp.ma_hp')
            ->select('hp.ma_hp', 'hp.ten_hp', DB::raw('COUNT(lhp.ma_lhp) as total'))
            ->where('lhp.trang_thai', 1)
            ->groupBy('hp.ma_hp', 'hp.ten_hp')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 10. Top 5 giảng viên có nhiều bài giảng
        $data['top_teachers'] = DB::table('bai_giang as bg')
            ->join('taikhoan as tk', 'bg.ma_tk', '=', 'tk.ma_tk')
            ->select('tk.ma_tk', 'tk.ho_ten', DB::raw('COUNT(bg.ma_bg) as total'))
            ->where('bg.trang_thai', 1)
            ->groupBy('tk.ma_tk', 'tk.ho_ten')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 11. Top 5 lớp có nhiều sinh viên nhất
        $data['top_classes_by_students'] = DB::table('sinh_vien as sv')
            ->join('lop_hoc_phan as lhp', 'sv.ma_lhp', '=', 'lhp.ma_lhp')
            ->select('lhp.ma_lhp', 'lhp.ten_lhp', DB::raw('COUNT(sv.id) as total'))
            ->where('sv.trang_thai', 1)
            ->groupBy('lhp.ma_lhp', 'lhp.ten_lhp')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 12. Thống kê sinh viên theo bộ môn
        $data['students_by_department'] = DB::table('taikhoan as tk')
            ->join('bo_mon as bm', 'tk.ma_bm', '=', 'bm.ma_bm')
            ->select('bm.ten_bm', DB::raw('COUNT(tk.ma_tk) as total'))
            ->where('tk.vai_tro', 3)
            ->where('tk.trang_thai', 1)
            ->groupBy('bm.ten_bm')
            ->get();

        // 13. Thống kê bài kiểm tra theo tháng (6 tháng gần nhất)
        $data['tests_by_month'] = DB::table('bai_kiem_tra')
            ->select(
                DB::raw('DATE_FORMAT(ngay_tao, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('ngay_tao', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // 14. Thống kê đánh giá trung bình theo lớp (top 5)
        $data['top_rated_classes'] = DB::table('danh_gia as dg')
            ->join('lop_hoc_phan as lhp', 'dg.ma_lhp', '=', 'lhp.ma_lhp')
            ->select('lhp.ten_lhp', DB::raw('AVG(dg.so_sao) as avg_rating'), DB::raw('COUNT(dg.id) as total_reviews'))
            ->where('dg.trang_thai', 1)
            ->groupBy('lhp.ma_lhp', 'lhp.ten_lhp')
            ->orderByDesc('avg_rating')
            ->limit(5)
            ->get();

        // 15. Thống kê tương tác (comments) trong 30 ngày
        $data['interactions_last_month'] = DB::table('tuong_tac')
            ->where('ngay_tao', '>=', now()->subDays(30))
            ->where('trang_thai', 1)
            ->count();

        // 16. Thống kê tỷ lệ hoàn thành bài kiểm tra
        $totalTests = DB::table('bai_kiem_tra')->where('trang_thai', 1)->count();
        $submittedTests = DB::table('nop_bai_kiem_tra')->where('trang_thai', 1)->count();
        $data['test_completion_rate'] = $totalTests > 0 ? round(($submittedTests / $totalTests) * 100, 2) : 0;

        // 17. Thống kê tài khoản mới trong 30 ngày
        $data['new_accounts_last_month'] = DB::table('taikhoan')
            ->where('ngay_tao', '>=', now()->subDays(30))
            ->where('trang_thai', 1)
            ->count();

        return $data;
    }

    /**
     * Thống kê cho Giảng viên
     */
    private function getTeacherStatistics($teacherId)
    {
        $data = [];

        // 1. Tổng lớp học phần của giảng viên
        $data['count_class'] = DB::table('lop_hoc_phan')
            ->where('ma_tk', $teacherId)
            ->where('trang_thai', 1)
            ->count();

        // 2. Tổng bài giảng
        $data['count_lesson'] = DB::table('bai_giang')
            ->where('ma_tk', $teacherId)
            ->where('trang_thai', 1)
            ->count();

        // 3. Tổng số bài (nội dung)
        $data['count_content'] = DB::table('bai as b')
            ->join('chuong as c', 'b.ma_chuong', '=', 'c.ma_chuong')
            ->join('bai_giang as bg', 'c.ma_bg', '=', 'bg.ma_bg')
            ->where('bg.ma_tk', $teacherId)
            ->where('b.trang_thai', 1)
            ->count();

        // 4. Tổng bài kiểm tra
        $data['count_test'] = DB::table('bai_kiem_tra as bkt')
            ->join('lop_hoc_phan as lhp', 'bkt.ma_lhp', '=', 'lhp.ma_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('bkt.trang_thai', 1)
            ->count();

        // 5. Tổng sinh viên trong các lớp
        $data['total_students'] = DB::table('sinh_vien as sv')
            ->join('lop_hoc_phan as lhp', 'sv.ma_lhp', '=', 'lhp.ma_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('sv.trang_thai', 1)
            ->distinct('sv.ma_tk')
            ->count('sv.ma_tk');

       // 6. Danh sách lớp học phần của giảng viên
        $data['my_classes'] = DB::table('lop_hoc_phan as lhp')
            ->join('hoc_phan as hp', 'lhp.ma_hp', '=', 'hp.ma_hp')
            ->leftJoin('sinh_vien as sv', 'lhp.ma_lhp', '=', 'sv.ma_lhp')
            ->select(
                DB::raw('MIN(lhp.ma_lhp) as ma_lhp'),
                DB::raw('MIN(lhp.ten_lhp) as ten_lhp'),
                DB::raw('MIN(lhp.trang_thai) as trang_thai'),
                DB::raw('MIN(lhp.ngay_tao) as ngay_tao'),
                DB::raw('MIN(hp.ten_hp) as ten_hp'),
                DB::raw('COUNT(sv.id) as student_count')
            )
            ->where('lhp.ma_tk', $teacherId)
            ->where('lhp.trang_thai', 1)
            ->groupBy('lhp.ma_lhp')
            ->get();
            
        // 7. Bài kiểm tra sắp hết hạn (trong 7 ngày tới)
        $data['upcoming_tests'] = DB::table('bai_kiem_tra as bkt')
            ->join('lop_hoc_phan as lhp', 'bkt.ma_lhp', '=', 'lhp.ma_lhp')
            ->select('bkt.*', 'lhp.ten_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('bkt.han_nop', '>=', now())
            ->where('bkt.han_nop', '<=', now()->addDays(7))
            ->where('bkt.trang_thai', 1)
            ->orderBy('bkt.han_nop', 'asc')
            ->get();

        // 8. Thống kê bài nộp trong 7 ngày qua
        $data['submissions_last_week'] = DB::table('nop_bai_kiem_tra as nbkt')
            ->join('bai_kiem_tra as bkt', 'nbkt.ma_bkt', '=', 'bkt.ma_bkt')
            ->join('lop_hoc_phan as lhp', 'bkt.ma_lhp', '=', 'lhp.ma_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('nbkt.ngay_nop', '>=', now()->subDays(7))
            ->count();

        // 9. Đánh giá trung bình của các lớp
        $data['average_rating'] = DB::table('danh_gia as dg')
            ->join('lop_hoc_phan as lhp', 'dg.ma_lhp', '=', 'lhp.ma_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('dg.trang_thai', 1)
            ->avg('dg.so_sao');

        $data['average_rating'] = round($data['average_rating'] ?? 0, 2);

        // 10. Tương tác gần đây (30 ngày)
        $data['recent_interactions'] = DB::table('tuong_tac as tt')
            ->join('lop_hoc_phan as lhp', 'tt.ma_lhp', '=', 'lhp.ma_lhp')
            ->join('taikhoan as tk', 'tt.ma_tk', '=', 'tk.ma_tk')
            ->select('tt.*', 'tk.ho_ten', 'lhp.ten_lhp')
            ->where('lhp.ma_tk', $teacherId)
            ->where('tt.ngay_tao', '>=', now()->subDays(30))
            ->where('tt.trang_thai', 1)
            ->orderBy('tt.ngay_tao', 'desc')
            ->limit(10)
            ->get();

        return $data;
    }

    /**
     * API endpoint để lấy dữ liệu biểu đồ
     */
    public function getChartData($type)
    {
        switch ($type) {
            case 'student_growth':
                return $this->getStudentGrowthData();
            
            case 'class_distribution':
                return $this->getClassDistributionData();
            
            case 'test_completion':
                return $this->getTestCompletionData();
            
            case 'department_stats':
                return $this->getDepartmentStatsData();
            
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    /**
     * Dữ liệu tăng trưởng sinh viên theo tháng
     */
    private function getStudentGrowthData()
    {
        $data = DB::table('taikhoan')
            ->select(
                DB::raw('DATE_FORMAT(ngay_tao, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('vai_tro', 3)
            ->where('ngay_tao', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($data);
    }

    /**
     * Phân bố lớp học theo học phần
     */
    private function getClassDistributionData()
    {
        $data = DB::table('lop_hoc_phan as lhp')
            ->join('hoc_phan as hp', 'lhp.ma_hp', '=', 'hp.ma_hp')
            ->select('hp.ten_hp', DB::raw('COUNT(lhp.ma_lhp) as total'))
            ->where('lhp.trang_thai', 1)
            ->groupBy('hp.ma_hp', 'hp.ten_hp')
            ->get();

        return response()->json($data);
    }

    /**
     * Tỷ lệ hoàn thành bài kiểm tra
     */
    private function getTestCompletionData()
    {
        $data = DB::table('bai_kiem_tra as bkt')
            ->leftJoin('nop_bai_kiem_tra as nbkt', 'bkt.ma_bkt', '=', 'nbkt.ma_bkt')
            ->select(
                DB::raw('DATE_FORMAT(bkt.ngay_tao, "%Y-%m") as month'),
                DB::raw('COUNT(DISTINCT bkt.ma_bkt) as total_tests'),
                DB::raw('COUNT(DISTINCT nbkt.id) as submitted_tests')
            )
            ->where('bkt.ngay_tao', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json($data);
    }

    /**
     * Thống kê theo bộ môn
     */
    private function getDepartmentStatsData()
    {
        $data = DB::table('bo_mon as bm')
            ->leftJoin('hoc_phan as hp', 'bm.ma_bm', '=', 'hp.ma_bm')
            ->leftJoin('taikhoan as tk', 'bm.ma_bm', '=', 'tk.ma_bm')
            ->select(
                'bm.ten_bm',
                DB::raw('COUNT(DISTINCT hp.ma_hp) as total_courses'),
                DB::raw('COUNT(DISTINCT CASE WHEN tk.vai_tro = 2 THEN tk.ma_tk END) as total_teachers'),
                DB::raw('COUNT(DISTINCT CASE WHEN tk.vai_tro = 3 THEN tk.ma_tk END) as total_students')
            )
            ->where('bm.trang_thai', 1)
            ->groupBy('bm.ma_bm', 'bm.ten_bm')
            ->get();

        return response()->json($data);
    }



    



    function logout()
    {
        $this->_auth_login();
        Session::flush();
        return Redirect::to('/admin');
    }
}