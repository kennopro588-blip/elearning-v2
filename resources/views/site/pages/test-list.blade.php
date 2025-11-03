@extends('site.layout')
@section('content')
    @include(config('asset.view_admin_partial')('notify_message'))

    <style>
        /* .test-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f0f2 100%);
            min-height: 100vh;
            padding: 20px 0;
        } */

        .test-panel {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(44, 97, 81, 0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .test-panel:hover {
            box-shadow: 0 12px 32px rgba(44, 97, 81, 0.12);
        }

        .test-panel-heading {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            padding: 20px 24px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .test-panel-heading h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            flex-grow: 1;
        }

        .toggle-sidebar {
            color: #ffffff;
            font-size: 20px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
        }

        .toggle-sidebar:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-3px);
        }

        .test-panel-body {
            padding: 32px 24px;
        }

        .content-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            padding: 0 !important;
        }

        .ppcolumn {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px !important;
            border: 2px solid #e8f0f2;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            max-height: 300px;
        }

        .ppcolumn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2c6151 0%, #3a7a66 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .ppcolumn:hover {
            transform: translateY(-4px);
            border-color: #2c6151;
            box-shadow: 0 8px 24px rgba(44, 97, 81, 0.15);
        }

        .ppcolumn:hover::before {
            transform: scaleX(1);
        }

        .appt {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appt:not(.text-success):not(.text-danger):not(.text-warning) {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(44, 97, 81, 0.3);
        }

        .appt:not(.text-success):not(.text-danger):not(.text-warning):hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(44, 97, 81, 0.4);
        }

        .appt.text-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
        }

        .appt.text-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
        }

        .appt.text-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
        }

        .ppcolumn img {
            width: 56px !important;
            height: 56px !important;
            object-fit: contain;
            margin-bottom: 16px;
            padding: 0 !important;
            opacity: 0.9;
        }

        .ppcolumn h5 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 700;
            margin: 12px 0 16px 0;
            line-height: 1.4;
            min-height: 50px;
        }

        .ppcolumn .test-info {
            background: #f8fafb;
            padding: 16px;
            border-radius: 8px;
            border-left: 3px solid #2c6151;
        }

        .ppcolumn .test-info span {
            display: block;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .ppcolumn .test-info span:last-child {
            margin-bottom: 0;
        }

        .ppcolumn .test-info b {
            color: #2c6151;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state h3 {
            color: #2c6151;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .empty-state::before {
            content: '📝';
            display: block;
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .content-row {
                grid-template-columns: 1fr;
            }
            
            .test-panel-heading {
                padding: 16px 20px;
            }
            
            .test-panel-body {
                padding: 20px 16px;
            }
        }
    </style>

    <div class="col-xs-12 col-sm-9 test-container">
        <div class="panel panel-default test-panel">
            <div class="panel-heading test-panel-heading">
                <h3 class="panel-title">
                    <a href="javascript:void(0);" class="toggle-sidebar">
                        <span class="fa fa-angle-double-left" data-toggle="offcanvas" title="Maximize Panel"></span>
                    </a>
                    Menu
                </h3>
            </div>
            <!--panel-Body-->
            <div class="panel-body test-panel-body">
                <div class="content-row">
                    <?php                    
                    if(isset($tests) && !empty($tests)):
                    foreach ($tests as $row): $check_ex = 0;?>
                    <div class="ppcolumn">
                        <?php if(isset($check_submited) && !empty($check_submited)):
							foreach($check_submited as $check): 
							if($row->ma_bkt == $check->ma_bkt): ?>
                        <a class="appt">Đã thực hiện</a>
                        <?php $check_ex = 1; endif; endforeach; endif; ?>
                        <?php if($check_ex == 0)
                        if($row->han_nop < now()):
                        ?>
                        <a style="color: white" class="appt">Hết hạn</a>
                        <?php elseif($row->bat_dau > now()): ?>
                        <a class="appt text-warning">Sắp diễn ra</a>
                        <?php else: ?>
                        <a href="<?php echo URL::to($row->alias_lhp . '/' . $row->ma_bkt . '/test'); ?>" class="appt">Xem chi tiết</a>
                        <?php endif; ?>
                        <img src="{{ URL::to(config('asset.images_path') . 'd-img.png') }}" alt="Test icon" />
                        <h5>{{ $row->tieu_de }}</h5>
                        <div class="test-info">
                            <span>Bắt đầu: <b>{{ $row->bat_dau }}</b></span>
                            <span>Kết thúc: <b>{{ $row->han_nop }}</b></span>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                    <div class="empty-state">
                        <h3>Chưa có bài kiểm tra nào</h3>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
@endsection