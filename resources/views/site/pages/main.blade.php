@extends('site.layout')
@section('content')
    <style>
        /* .class-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f0f2 100%);
            min-height: 100vh;
            padding: 20px 0;
        } */

        .class-panel {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(44, 97, 81, 0.08);
            border: none;
            overflow: hidden;
        }

        .class-panel-heading {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            padding: 20px 24px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .class-panel-heading h3 {
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

        .class-panel-body {
            padding: 32px 24px;
        }

        .class-card-wrapper {
            padding: 12px;
            height: 100%;
        }

        .class-card-wrapper > a {
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(44, 97, 81, 0.08);
            background: #ffffff;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 97, 81, 0.05) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
            pointer-events: none;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 32px rgba(44, 97, 81, 0.15);
        }

        .card:hover::before {
            opacity: 1;
        }

        .bg-card {
            height: 180px;
            background-image: var(--bg-avatar);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            transition: all 0.4s ease;
        }

        .bg-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.3), transparent);
            transition: opacity 0.4s ease;
        }

        .card:hover .bg-card {
            transform: scale(1.05);
        }

        .card:hover .bg-card::after {
            opacity: 0.7;
        }

        .avatar-tutor {
            display: flex;
            justify-content: flex-end;
            padding-right: 20px;
            margin-top: -32px;
            position: relative;
            z-index: 2;
        }

        .avatar-lecture {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .card:hover .avatar-lecture {
            transform: scale(1.1);
            border-color: #2c6151;
            box-shadow: 0 6px 16px rgba(44, 97, 81, 0.3);
        }

        .card-body {
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .card-body:first-of-type {
            flex: 1;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.4;
            min-height: 50px;
        }

        .card-title a {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .card:hover .card-title a {
            color: #2c6151;
        }

        .card-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 63px;
        }

        .card-text a {
            color: #64748b;
            text-decoration: none;
        }

        .card-body.action-bar {
            padding: 16px 20px;
            background: #f8fafb;
            border-top: 1px solid #e8f0f2;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #ffffff;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #e8f0f2;
            position: relative;
            z-index: 2;
        }

        .card-link i {
            font-size: 18px;
            margin: 0;
            transition: all 0.3s ease;
        }

        .card-link:hover {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            border-color: #2c6151;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 97, 81, 0.3);
        }

        .card-link:hover i {
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #64748b;
        }

        .empty-state::before {
            content: '📚';
            display: block;
            font-size: 72px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #2c6151;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        @media (max-width: 768px) {
            .class-panel-heading {
                padding: 16px 20px;
            }
            
            .class-panel-body {
                padding: 20px 16px;
            }

            .class-card-wrapper {
                padding: 8px;
            }

            .bg-card {
                height: 150px;
            }

            .avatar-lecture {
                width: 56px;
                height: 56px;
            }

            .card-title {
                font-size: 16px;
                min-height: auto;
            }

            .card-text {
                font-size: 13px;
                min-height: auto;
            }
        }

        @media (min-width: 992px) {
            .class-card-wrapper {
                padding: 16px;
            }
        }
    </style>

    <div class="col-xs-12 col-sm-9 class-container">
        <div class="panel panel-default class-panel">
            <div class="panel-heading class-panel-heading">
                <h3 class="panel-title">
                    <a href="javascript:void(0);" class="toggle-sidebar">
                        <span class="fa fa-angle-double-left" data-toggle="offcanvas" title="Maximize Panel"></span>
                    </a>
                    Menu
                </h3>
            </div>
            <!--panel-Body-->
            <div class="panel-body class-panel-body">
                <div class="content-row px-3">
                    <!-- card-start -->
                    <div class="row">
                        <?php
                        if(isset($section_class_none) && !empty($section_class_none)):
                        foreach ($section_class_none as $row):?>
                            <div class="col-12 col-md-6 col-lg-4 class-card-wrapper">
                                <a href="{{URL::to($row->alias)}}">
                                    <div class="card">
                                        <div class="bg-card card-img-top"
                                            style="--bg-avatar: url('{{ URL::to(config('asset.images_path') . $row->hinh_anh) }}')">
                                        {{-- style="--bg-avatar: url('https://www.gstatic.com/classroom/themes/img_bookclub.jpg')"> --}}
                                        
                                        </div>

                                        <div class="d-flex justify-content-end pe-3 avatar-tutor">
                                            <img class="avatar-lecture" src="{{ URL::to(config('asset.images_path') . $row->avatar) }}"
                                                alt="{{$row->ten_lhp}}">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title hv-link"><a href="{{$row->alias}}" class="text-dark">{{$row->ten_lhp}}</a></h5>
                                            <p class="card-text hv-link"><a href="" class="text-dark">{{$row->mo_ta}}</a></p>
                                        </div>
                                        <div class="card-body action-bar d-flex justify-content-end">
                                            <a href="{{URL::to($row->alias.'/test')}}" class="card-link" title="Bài kiểm tra" onclick="event.stopPropagation();">
                                                <i class="fa fa-file"></i>
                                            </a>
                                            <a href="{{URL::to($row->alias.'/'.$row->alias_bg.'/files-bai-giang')}}" class="card-link" title="Tài liệu" onclick="event.stopPropagation();">
                                                
                                                <i class="fa fa-folder"></i>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach;
                        else: ?>
                            <div class="col-12">
                                <div class="empty-state">
                                    <h3>Chưa có lớp học nào</h3>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- card-finish -->
                </div>
            </div>
        </div>
        <!-- end panel body -->
    </div>
@endsection