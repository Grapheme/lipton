@extends(Helper::acclayout())
@section('style')
    <style type="text/css">
        .popover{
            width: 1024px !important;
            max-width: 1024px !important;
        }
    </style>
@stop
@section('content')
    @include($module['tpl'].'/menu')
@if($users->count())
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table table-striped table-bordered min-table white-bg">
                <thead>
                <tr>
                    <th class="col-lg-1 text-center">ID</th>
                    <th class="col-lg-1 text-center">Фото и видео</th>
                    <th class="col-lg-10 text-center" style="white-space:nowrap;">Данные пользователя</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    <tr class="vertical-middle">
                        <?php $sub_index = Input::has('page') ? (int)Input::get('page')-1 : 0;?>
                        <td>{{ ($index+1)+($sub_index*20) }}</td>
                        <td>
                        @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                            <img src="{{ asset($user->photo) }}"
                                 alt="{{ $user->name }}" class="{{ $user->name }}">
                        @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                            <img src="{{ $user->ulogin->photo_big }}" alt="{{ $user->name }}"
                                 class="{{ $user->name }}">
                        @elseif(!empty($user->photo))
                            <img src="{{ $user->photo }}" alt="{{ $user->name }}"
                                 class="{{ $user->name }}">
                        @else
                            <img src="{{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }}" alt="{{ $user->name }}"
                                 class="{{ $user->name }}">
                        @endif
                            <div style="margin-top: 50px">
                                @if(count($user->writing) && !empty($user->writing->writing))
                                    <p><a data-content="{{{ nl2br($user->writing->writing) }}}" data-html="true" data-original-title="Рассказ участника" data-placement="right" rel="popover" class="btn btn-link" href="javascript:void(0);">Смотреть рассказ</a></p>
                                @else
                                    <p>Рассказ отсутсвует</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <p>
                                <strong>{{ $user->name }} {{ $user->surname }}</strong><br/>
                                @if($user->age > 0){{ $user->age }} {{ Lang::choice('год|года|лет', (int)$user->age ) }}. {{ $user->location }}<br/>@endif
                                {{ $user->created_at->format('d.m.Y H:i:s') }} #{{ $user->id }}<br/>
                                {{ $user->city }}<br/>
                                <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user->email, $user->email) }}<br/>
                                <i class="fa fa-fw fa-mobile-phone"></i>{{ $user->phone }}
                                @if(!empty($user->social))
                                    @foreach(json_decode($user->social) as $social)
                                        @if(!empty($social))
                                        <br/><i class="fa fa-fw fa-angle-double-right "></i>
                                <a href="{{ $social }}" target="_blank">{{ str_limit(trim($social), $limit = 25, $end = ' ...') }}</a>
                                        @endif
                                    @endforeach
                                @endif
                            </p>
                        @if(count($user->writing) && !empty($user->writing->writing))
                            <hr style="margin-bottom: 5px; margin-top: 5px;">
                            @if($user->writing->status == 2)
                            <span class="label label-primary">Ожидает модерации</span><br><br>
                            <a href="{{ URL::route('moderator.participants.status', array($user->id, $user->writing->id, 1)) }}" class="btn btn-success btn-xs js-confirm">Одобрить рассказ</a>
                            <a href="{{ URL::route('moderator.participants.status', array($user->id, $user->writing->id, 3)) }}" class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                            @elseif($user->writing->status == 1)
                            <span class="label label-success">Рассказ одобрен</span><br><br>
                            <a href="{{ URL::route('moderator.participants.status', array($user->id, $user->writing->id, 3)) }}" class="btn btn-danger btn-xs js-confirm">Отклонить рассказ</a>
                            @elseif($user->writing->status == 3)
                            <span class="label label-danger">Рассказ отклонен</span><br><br>
                            <a href="{{ URL::route('moderator.participants.status', array($user->id, $user->writing->id, 1)) }}" class="btn btn-success btn-xs js-confirm">Одобрить рассказ</a>
                            @endif
                        @endif
                            <hr style="margin-bottom: 5px; margin-top: 5px;">
                            {{ Form::model($user,array('route'=>array('moderator.participants.save',$user->id),'method'=>'post')) }}
                            {{ Form::checkbox('winner') }} Победитель <br>

                            {{ Form::button('Сохранить',array('class'=>'btn btn-success btn-sm','type'=>'submit','style'=>'margin-top:10px;')) }}
                            {{ Form::close() }}
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
        if(typeof pageSetUp === 'function'){pageSetUp();}
        if(typeof runFormValidation === 'function'){
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runFormValidation);
        }else{
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
    </script>
@stop

