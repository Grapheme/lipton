@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
    @include($module['tpl'].'/menu')
    @if(Input::get('filter_status') == 'winners')
        <?php
        $now = Carbon::now();
        $begin = $now->startOfWeek()->format('d.m.Y');
        $end = $now->endOfWeek()->format('d.m.Y')
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                {{ Form::open(array('route'=>'moderator.participants','class' => 'smart-form', 'style' => 'margin-bottom:20px;', 'method'=>'get')) }}
                {{ Form::hidden('filter_status','winners') }}
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
                        <th class="col-lg-1 text-center">ID</th>
                        <th class="col-lg-2 text-center">Фото и видео</th>
                        <th class="col-lg-4 text-center">Данные пользователя</th>
                        <th class="col-lg-5 text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        @if(Input::has('filter_status') && in_array(Input::get('filter_status'),array('codes', 'writing')))
                            <?php $user = $user->users; ?>
                        @else
                            <?php $user = $user->toArray(); ?>
                        @endif
                        <?php
                        $bdate = new Carbon($user['bdate']);
                        ?>
                        <tr class="vertical-middle">
                            <?php $sub_index = Input::has('page') ? (int)Input::get('page') - 1 : 0;?>
                            <td>{{ ($index+1)+($sub_index*20) }}</td>
                            <td style="vertical-align:top;">
                                @if(!empty($user['photo']) && File::exists(public_path($user['photo'])))
                                    <img height="100px" src="{{ asset($user['photo']) }}"
                                         alt="{{ $user['name'] }}" class="{{ $user['name'] }}">
                                @elseif(!empty($user['ulogin']) && !empty($user['ulogin']['photo_big']))
                                    <img height="100px" src="{{ $user['ulogin']['photo_big'] }}"
                                         alt="{{ $user['name'] }}"
                                         class="{{ $user['name'] }}">
                                @elseif(!empty($user['photo']))
                                    <img height="100px" src="{{ $user['photo'] }}" alt="{{ $user['name'] }}"
                                         class="{{ $user['name'] }}">
                                @else
                                    <img height="100px"
                                         src="{{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }}"
                                         alt="{{ $user['name'] }}"
                                         class="{{ $user['name'] }}">
@include($module['tpl'].'/menu')
@if(count($users))
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table table-striped table-bordered min-table white-bg">
                <thead>
                <tr>
                    <th class="col-lg-1 text-center">ID</th>
                    <th class="col-lg-2 text-center">Фото и видео</th>
                    <th class="col-lg-4 text-center">Данные пользователя</th>
                    <th class="col-lg-5 text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    @if(Input::has('filter_status'))
                        <?php $user = $user->users; ?>
                    @else
                        <?php $user = $user->toArray(); ?>
                    @endif
                    <?php
                    $bdate = new Carbon($user['bdate']);
                    ?>
                    <tr>
                        <?php $sub_index = Input::has('page') ? (int)Input::get('page') - 1 : 0;?>
                        <td>{{ ($index+1)+($sub_index*20) }}</td>
                        <td style="vertical-align:top;font-size:12px;">
                            @if(!empty($user['photo']) && File::exists(public_path($user['photo'])))
                                <img height="100px" src="{{ asset($user['photo']) }}"
                                     alt="{{ $user['name'] }}" class="{{ $user['name'] }}">
                            @elseif(!empty($user['ulogin']) && !empty($user['ulogin']['photo_big']))
                                <img height="100px" src="{{ $user['ulogin']['photo_big'] }}"
                                     alt="{{ $user['name'] }}"
                                     class="{{ $user['name'] }}">
                            @elseif(!empty($user['photo']))
                                <img height="100px" src="{{ $user['photo'] }}" alt="{{ $user['name'] }}"
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
                            </p>
                            {{ Form::model($user,array('route'=>array('moderator.participants.save',$user['id']),'method'=>'post')) }}
                            {{ Form::checkbox('winner') }} Победитель
                            {{ Form::select('number_week', array('1 неделя','2 неделя','3 неделя','4 неделя','5 неделя','6 неделя','7 неделя','8 неделя','9 неделя','10 неделя'), NULL, array('style'=>'width:130px;')) }}<br>
                            {{ Form::button('Сохранить',array('class'=>'btn btn-success btn-xs','type'=>'submit','style'=>'margin-top:-4px;')) }}
                            {{ Form::close() }}
                        </td>
                        <td style="vertical-align:top;">
                            <div style="margin-top: 50px">
                                @if(count($user['writing']) && !empty($user['writing']['writing']))
                                    <p><a target="_blank"
                                          href="{{ URL::route('show.participant.writing', $user['writing']['id'].'-'.BaseController::stringTranslite($user['name'].'-'.$user['surname'])) }}">Рассказ участника</a></p>
                                @elseif(count($user['writing']) && empty($user['writing']['writing']))
                                    <p>Рассказ пуст</p>
                                @else
                                    <p>Рассказ отсутствует</p>
                                @endif
                            </div>
                            @if(count($user['writing']) && !empty($user['writing']['writing']))
                                @if($user['writing']['status'] == 2)
                                    <span class="label label-info">Ожидает модерации</span><br><br>
                                    <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing']['id'], 1)) }}"
                                       class="btn btn-success btn-xs js-confirm">Одобрить рассказ</a>
                                    <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing']['id'], 3)) }}"
                                       class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                                @elseif($user['writing']['status'] == 1)
                                    <span class="label label-success">Рассказ одобрен</span><br><br>
                                    <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing']['id'], 3)) }}"
                                       class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                                @elseif($user['writing']['status'] == 3)
                                    <span class="label label-danger">Рассказ отклонен</span><br><br>
                                    <a href="{{ URL::route('moderator.participants.status', array($user['id'], $user['writing']['id'], 1)) }}"
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
</script>
@stop

