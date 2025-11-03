@extends('site.layout')
@section('content')
    <style>
        /* .quiz-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f0f2 100%);
            min-height: 100vh;
            padding: 20px 0;
        } */

        .quiz-panel {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(44, 97, 81, 0.08);
            border: none;
            overflow: hidden;
        }

        .quiz-panel-heading {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            padding: 20px 24px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quiz-panel-heading h3 {
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

        .quiz-panel-body {
            padding: 32px 24px;
        }

        .quiz-title {
            text-align: center;
            margin: 0 0 40px 0;
            padding: 24px;
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            border-radius: 12px;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(44, 97, 81, 0.2);
        }

        .quiz-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
        }

        .notice-message {
            text-align: center;
            padding: 60px 20px;
            background: #f8fafb;
            border-radius: 12px;
            border-left: 4px solid #2c6151;
        }

        .notice-message h1 {
            color: #2c6151;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .question {
            background: #ffffff;
            border-radius: 12px;
            padding: 28px !important;
            margin-bottom: 24px;
            border: 2px solid #e8f0f2;
            transition: all 0.3s ease;
            position: relative;
        }

        .question::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #2c6151 0%, #3a7a66 100%);
            border-radius: 12px 0 0 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .question:hover {
            border-color: #2c6151;
            box-shadow: 0 4px 16px rgba(44, 97, 81, 0.1);
        }

        .question:hover::before {
            opacity: 1;
        }

        .question h5 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .question ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .question ul li {
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .question ul li:last-child {
            margin-bottom: 0;
        }

        .question ul li input[type="radio"] {
            display: none;
        }

        .question ul li label {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #f8fafb;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            color: #475569;
            font-weight: 500;
            position: relative;
            padding-left: 52px;
        }

        .question ul li label::before {
            content: '';
            position: absolute;
            left: 20px;
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .question ul li label::after {
            content: '';
            position: absolute;
            left: 25px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #2c6151;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .question ul li label:hover {
            background: #f1f5f9;
            border-color: #2c6151;
            color: #2c6151;
            transform: translateX(4px);
        }

        .question ul li input[type="radio"]:checked + label {
            background: linear-gradient(135deg, rgba(44, 97, 81, 0.1) 0%, rgba(58, 122, 102, 0.1) 100%);
            border-color: #2c6151;
            color: #2c6151;
            font-weight: 600;
        }

        .question ul li input[type="radio"]:checked + label::before {
            border-color: #2c6151;
            background: #ffffff;
        }

        .question ul li input[type="radio"]:checked + label::after {
            transform: scale(1);
        }

        .text-danger {
            color: #ef4444;
            font-size: 14px;
            margin-top: 8px;
            padding: 8px 12px;
            background: #fef2f2;
            border-radius: 6px;
            border-left: 3px solid #ef4444;
        }

        .submit-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            padding-top: 32px;
            border-top: 2px solid #e8f0f2;
        }

        .btn-bgr {
            background: linear-gradient(135deg, #2c6151 0%, #3a7a66 100%);
            color: #ffffff;
            border: none;
            padding: 14px 48px !important;
            font-size: 16px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(44, 97, 81, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-bgr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 97, 81, 0.4);
            background: linear-gradient(135deg, #3a7a66 0%, #2c6151 100%);
        }

        .btn-bgr:active {
            transform: translateY(0);
        }

        .empty-data {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-data h1 {
            color: #2c6151;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .empty-data::before {
            content: '⚠️';
            display: block;
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .quiz-panel-heading {
                padding: 16px 20px;
            }
            
            .quiz-panel-body {
                padding: 20px 16px;
            }

            .quiz-title h1 {
                font-size: 22px;
            }

            .question {
                padding: 20px !important;
            }

            .question h5 {
                font-size: 16px;
            }

            .question ul li label {
                padding: 12px 16px;
                padding-left: 48px;
                font-size: 14px;
            }

            .btn-bgr {
                padding: 12px 36px !important;
                font-size: 15px;
            }
        }
    </style>

    <!-- content -->
    <div class="col-xs-12 col-sm-9 quiz-container">
        <div class="panel panel-default quiz-panel">
            <div class="panel-heading quiz-panel-heading">
                <h3 class="panel-title">
                    <a href="javascript:void(0);" class="toggle-sidebar">
                        <span class="fa fa-angle-double-left" data-toggle="offcanvas" title="Maximize Panel"></span>
                    </a>
                    Menu
                </h3>
            </div>
            <!--panel-Body-->
            <div class="panel-body quiz-panel-body">
                <div class="content-row px-2 px-md-5">
                    <?php if(isset($notice)): ?>
                    <div class="notice-message">
                        <h1><b>{{ $notice }}</b></h1>
                    </div>
                    <?php else: ?>
                    <form action="{{ URL::to('nop-bai-kiem-tra') }}" method="post">
                        {{ csrf_field() }}
                        <div class="quiz-title">
                            <h1><b>{{ $tieu_de_test }}</b></h1>
                        </div>
                        <input hidden type="hidden" name="ma_bkt" value="{{ $test[0]->ma_bkt ?? '' }}">
                        <?php
					    if (isset($array_question) && !empty($array_question) && is_array($array_question)):
						foreach ($array_question as $index=>$row): ?>
                        <div class="row">
                            <div class="col-12 question">
                                <h5><b>{{ $row['label'] }}</b></h5>
                                @error('question.0')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input hidden type="hidden" name="question[{{ $index }}]">

                                <ul>
                                    <li>
                                        <input type="radio" id="q{{ $index }}-1" value="1" name="question[{{ $index }}]">
                                        <label for="q{{ $index }}-1">{{ $row['answer1'] }}</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="q{{ $index }}-2" value="2" name="question[{{ $index }}]">
                                        <label for="q{{ $index }}-2">{{ $row['answer2'] }}</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="q{{ $index }}-3" value="3" name="question[{{ $index }}]">
                                        <label for="q{{ $index }}-3">{{ $row['answer3'] }}</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="q{{ $index }}-4" value="4" name="question[{{ $index }}]">
                                        <label for="q{{ $index }}-4">{{ $row['answer4'] }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach;?>

                        <div class="submit-container">
                            <button type="submit" class="btn px-4 py-2 btn-bgr"><b>Nộp Bài</b></button>
                        </div>
                        <?php else: ?>
                        <div class="empty-data">
                            <h1>Dữ liệu không tồn tại</h1>
                        </div>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>

                </div>
            </div>


            <!-- end panel body -->
        </div>
    </div>
    <!-- end content -->
@endsection