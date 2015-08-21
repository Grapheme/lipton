<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin', 'codes', 'prizes', 'writing')->first();
$bdate = new Carbon($profile->bdate);
$now = Carbon::now();

$post['customerId'] = Auth::user()->remote_id;
$post['sessionKey'] = Auth::user()->sessionKey;
$prizes = (new ApiController())->get_prizes($post);
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')  @stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <h2>Личный кабинет</h2>
                <a class="exit-cabinet" href="{{ URL::route('logout') }}">Выйти</a>
                <div class="profile">
                    <div class="profile-head">
                        <div class="profile-info">
                            @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
                                <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
                            @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
                                <div style="background-image: url({{ asset($profile->ulogin->photo_big) }});"
                                     class="avatar"></div>
                            @else
                                <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
                                     class="avatar"></div>
                            @endif
                            <h3>{{ $profile->name }} {{ $profile->surname }}</h3>

                            <p>{{ $bdate->diffInYears($now).' '.Lang::choice('год|года|лет', $bdate->diffInYears($now)) }}{{ !empty($profile->city) ? ', '.$profile->city : '' }}</p>
                            <a href="{{ URL::route('profile.edit') }}">Редактировать профиль</a>
                        </div>
                        <div class="profile-promo-code">
                            <h3>Введите промо код</h3>
                            @if(count($prizes) == 0)
                                @include(Helper::layout('forms.first-promo-code'))
                            @elseif(count($prizes) >= 1 && isset($prizes[0]))
                                @include(Helper::layout('forms.second-promo-code'))
                            @endif
                        </div>
                        <div class="request">
                            <div class="note"></div>
                            @if(isset($profile->writing->writing) && !empty($profile->writing->writing))
                                <a href="javascript:void(0);">Конкурс рассказов</a>
                                @if($profile->writing->status == 2)
                                    <div class="moderation neutral">Ваш рассказ на модерации
                                        <div class="bullet"></div>
                                    </div>
                                @elseif($profile->writing->status == 1)
                                    <a class="watch" target="_blank"
                                       href="{{ URL::route('show.participant.writing', $profile->writing->id.'-'.BaseController::stringTranslite(Auth::user()->name.'-'.Auth::user()->surname)) }}">Смотреть</a>
                                @endif
                            @else
                                <a class="disabled-button" href="javascript:void(0);">Конкурс рассказов</a>
                            @endif
                        </div>
                    </div>
                    <div class="profile-border"></div>
                    <h3>Полученные призы</h3>

                    <div class="gained-prizes">
                        <div class="prize">
                            <div class="ico leo"></div>
                            @if(count($prizes) > 0 && isset($prizes[0]))
                                <p>{{ @$prizes[0]['displayName'] }}</p>
                                <a class="disabled-button">Получен</a>
                            @else
                            <p>Курс английского для путешественников</p>
                            @endif
                        </div>
                        <div class="prize">
                            <div class="ico spec"></div>
                            @if(count($prizes) > 1 && isset($prizes[1]))
                                <p>{{ @$prizes[1]['displayName'] }}</p>
                                <a class="disabled-button">Получен</a>
                            @else
                                <p>Cпецкурс<br>на выбор</p>
                            @endif
                        </div>
                        <div class="prize">
                            <div class="ico ngt"></div>
                            <p>Путешествие с national geographic traveler</p>
                            @if(isset($profile->writing->status) && $profile->writing->status == 1)
                                <a class="disabled-button">Одобрен</a>
                            @elseif(isset($profile->writing->status) && $profile->writing->status == 2)
                                <a class="disabled-button">Модерация</a>
                            @elseif(isset($profile->writing->status) && $profile->writing->status == 3)
                                <a href="{{ URL::route('profile.tell-story') }}">Изменить</a>
                            @else
                                <a href="{{ URL::route('profile.tell-story') }}">Написать</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    @if($prizes === -1)
        <?php Auth::logout(); ?>
        <script type="application/javascript">
            window.location.href = '{{ pageurl('auth') }}';
        </script>
    @endif
@stop