<?php
$now = Carbon::now();
$begin = $now->startOfWeek()->format('d.m.Y');
$end = $now->endOfWeek()->format('d.m.Y')
?>
@extends(Helper::acclayout())
@section('style')
    <style type="text/css">
        #question-likes-modal {
            width: 365px;
            height: 250px; /* Рaзмеры дoлжны быть фиксирoвaны */
            border-radius: 5px;
            border: 3px #000 solid;
            background: #fff;
            position: fixed; /* чтoбы oкнo былo в видимoй зoне в любoм месте */
            top: 45%; /* oтступaем сверху 45%, oстaльные 5% пoдвинет скрипт */
            left: 50%; /* пoлoвинa экрaнa слевa */
            margin-top: -150px;
            margin-left: -150px; /* тут вся мaгия центрoвки css, oтступaем влевo и вверх минус пoлoвину ширины и высoты сooтветственнo =) */
            display: none; /* в oбычнoм сoстoянии oкнa не дoлжнo быть */
            opacity: 0; /* пoлнoстью прoзрaчнo для aнимирoвaния */
            z-index: 5; /* oкнo дoлжнo быть нaибoлее бoльшем слoе */
            padding: 20px 10px;
        }

        /* Кнoпкa зaкрыть для тех ктo в тaнке) */
        #question-likes-modal #modal_close {
            width: 21px;
            height: 21px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            display: block;
        }

        /* Пoдлoжкa */
        #overlay {
            z-index: 3; /* пoдлoжкa дoлжнa быть выше слoев элементoв сaйтa, нo ниже слoя мoдaльнoгo oкнa */
            position: fixed; /* всегдa перекрывaет весь сaйт */
            background-color: #000; /* чернaя */
            opacity: 0.8; /* нo немнoгo прoзрaчнa */
            width: 100%;
            height: 100%; /* рaзмерoм вo весь экрaн */
            top: 0;
            left: 0; /* сверху и слевa 0, oбязaтельные свoйствa! */
            cursor: pointer;
            display: none; /* в oбычнoм сoстoянии её нет) */
        }
    </style>
@stop
@section('content')
    @include($module['tpl'].'/menu')
    @if(Input::has('search'))
        <p>Результат поиска</p>
    @elseif(Input::get('filter_status') != 'winners')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                {{ Form::open(array('route'=>'moderator.participants','class' => 'smart-form', 'style' => 'margin-bottom:20px;', 'method'=>'get')) }}
                {{ Form::hidden('filter_status', Input::get('filter_status')) }}
                <header>Фильтр по дате регистрации</header>
                <fieldset>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Начало периода</label>
                            <label class="input">
                                {{ Form::text('begin', Input::has('begin') ? Input::get('begin') : $begin,array('class'=>'input-xs datepicker')) }}
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Конец периода</label>
                            <label class="input">
                                {{ Form::text('end', Input::has('end') ? Input::get('end') : $end,array('class'=>'input-xs datepicker')) }}
                            </label>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <a href="{{ URL::route('moderator.participants') }}?filter_status=winners" class="btn btn-default">
                        Сбросить фильтр
                    </a>
                    <button type="submit" class="btn btn-primary" style="float: left">Применить фильтр</button>
                </footer>
                {{ Form::close() }}
            </div>
        </div>
    @endif
    @if(count($users))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-striped table-bordered min-table white-bg">
                    <thead>
                    <tr>
                        <th class="col-lg-1 text-center">№ п.п</th>
                        <th class="col-lg-2 text-center">Фото и видео</th>
                        <th class="col-lg-4 text-center">Данные пользователя</th>
                        <th class="col-lg-5 text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <?php $user = (array)$user; ?>
                        <tr>
                            <?php $sub_index = Input::has('page') ? (int)Input::get('page') - 1 : 0;?>
                            <td>{{ ($index+1)+($sub_index*20) }}</td>
                            <td style="vertical-align:top;font-size:12px;">
                                @if(!empty($user['photo']) && File::exists(public_path($user['photo'])))
                                    <img height="100px" src="{{ asset($user['photo']) }}"
                                         alt="{{ $user['name'] }}" class="{{ $user['name'] }}">
                                @elseif(!empty($user['photo_big']))
                                    <img height="100px" src="{{ $user['photo_big'] }}"
                                         alt="{{ $user['name'] }}"
                                         class="{{ $user['name'] }}">
                                @else
                                    <img height="100px"
                                         src="{{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }}"
                                         alt="{{ $user['name'] }}"
                                         class="{{ $user['name'] }}">
                                @endif
                            </td>
                            <td style="vertical-align:top;font-size:12px;">
                                <p>
                                    <strong>{{ $user['name'] }} {{ $user['surname'] }}</strong><br/>
                                    @if(!empty($user['city']))
                                        {{ $user['city'] }}<br/>
                                    @endif
                                    {{ (new myDateTime())->setDateString($user['created_at'])->format('d.m.Y H:i:s') }}
                                    #{{ $user['id'] }}<br/>
                                    <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user['email'], $user['email']) }}
                                    <br/>
                                    <i class="fa fa-fw fa-mobile-phone"></i>{{ $user['phone'] }}
                                    <br>{{ $user['total_extend'] }}
                                    @if($user['codes'] > 0)
                                        <br>
                                        {{ $user['codes'] }} {{ Lang::choice('промо-код|промо-кода|промо-кодов', $user['codes']) }}
                                    @endif
                                </p>
                                {{ Form::model($user,array('route'=>array('moderator.participants.save',$user['id']),'method'=>'post')) }}
                                {{ Form::checkbox('winner') }} Победитель
                                {{ Form::select('number_week', array('Выбор недели','1 неделя','2 неделя','3 неделя','4 неделя','5 неделя','6 неделя','7 неделя','8 неделя','9 неделя','10 неделя'), NULL, array('style'=>'width:125px;')) }}
                                <br>
                                {{ Form::button('Сохранить',array('class'=>'btn btn-success btn-xs','type'=>'submit','style'=>'margin-top: 4px;')) }}
                                {{ Form::close() }}
                            </td>
                            <td style="vertical-align:top;font-size:12px;">
                                <div>
                                    @if(count($user['writing']) && !empty($user['writing']['writing']))
                                        <p><a target="_blank"
                                              href="{{ URL::route('show.participant.writing', $user['writing']['id'].'-'.BaseController::stringTranslite($user['name'].'-'.$user['surname'])) }}">Посмотреть
                                                рассказ </a></p>
                                    @elseif(count($user['writing']) && empty($user['writing']['writing']))
                                        <p>Участник не завершил свой рассказ</p>
                                    @else
                                        <p>Рассказ отсутствует</p>
                                    @endif
                                </div>
                                @if(!empty($user['writing']))
                                    @if($user['writing_status'] == 2)
                                        <span class="label label-info">Рассказ ожидает модерации</span><br><br>
                                        <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing_id'], 1)) }}"
                                           class="btn btn-success btn-xs js-confirm">Одобрить рассказ</a>
                                        <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing_id'], 3)) }}"
                                           class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                                    @elseif($user['writing_status'] == 1)
                                        <span class="label label-success">Рассказ одобрен</span><br><br>
                                        <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing_id'], 3)) }}"
                                           class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                                    @elseif($user['writing_status'] == 3)
                                        <span class="label label-danger">Рассказ отклонен</span><br><br>
                                        <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing_id'], 1)) }}"
                                           class="btn btn-success btn-xs js-confirm">Одобрить рассказ</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="ajax-notifications custom">
                    <div class="alert alert-transparent">
                        <h4>Список пуст</h4>
                        В данном разделе находятся пользователи сайта
                        <p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="question-likes-modal">
        <span id="modal_close">X</span>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {{ Form::open(array('route'=>'moderator.participants.likes','class' => 'smart-form', 'style' => 'margin-bottom:20px;')) }}
                {{ Form::hidden('filter_status', Input::get('filter_status')) }}
                <header>Процес может занять несколько минут</header>
                <fieldset>
                    <div class="row">
                        <section class="col col-5">
                            <label class="label">Начало периода</label>
                            <label class="input">
                                {{ Form::text('begin', Input::has('begin') ? Input::get('begin') : $begin, array('class'=>'input-xs datepicker')) }}
                            </label>
                        </section>
                        <section class="col col-5">
                            <label class="label">Конец периода</label>
                            <label class="input">
                                {{ Form::text('end', Input::has('end') ? Input::get('end') : $end, array('class'=>'input-xs datepicker')) }}
                            </label>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <button type="submit" id="js-btn-likes" class="btn btn-primary" style="float: left">Выполнить</button>
                </footer>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div id="overlay"></div>
@stop
@section('scripts')
    <script type="text/javascript">
        if (typeof pageSetUp === 'function') {
            pageSetUp();
        }
        if (typeof runFormValidation === 'function') {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runFormValidation);
        } else {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
        $('#js-question-likes').click(function (event) {
            event.preventDefault();
            $('#overlay').fadeIn(400,
                    function () {
                        $('#question-likes-modal')
                                .css('display', 'block')
                                .animate({opacity: 1, top: '50%'}, 200);
                    });
        });
        $('#modal_close, #overlay').click(function () {
            $('#modal_form')
                    .animate({opacity: 0, top: '45%'}, 200,
                    function () {
                        $(this).css('display', 'none');
                        $('#overlay').fadeOut(400);
                    }
            );
        });
        $("#js-btn-likes").click(function(){
            $(this).addClass('disabled').html('Ожидайте ...');
        });
    </script>
@stop

