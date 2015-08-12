<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin', 'codes', 'prizes', 'writing')->first();
$bdate = new Carbon($profile->bdate);
$now = Carbon::now();
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
                <a href="{{ URL::route('logout') }}">Выйти</a>
                @if(Session::has('message'))
                    <div>{{ Session::get('message') }}</div>
                @endif
                @if(Session::has('promo'))
                    <div>{{ Session::get('promo') }}</div>
                @endif
                <div class="profile">
                    <div class="profile-head">
                        <div class="profile-info">
                        @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
                            <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
                        @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
                            <div style="background-image: url({{ asset($profile->ulogin->photo_big) }});" class="avatar"></div>
                        @endif
                            <h3>{{ $profile->name }} {{ $profile->surname }}</h3>
                            <p>{{ $bdate->diffInYears($now).' '.Lang::choice('год|года|лет', $bdate->diffInYears($now)) }} {{ !empty($profile->city) ? ', '.$profile->city : '' }}</p>
                            <a href="{{ URL::route('profile.edit') }}">редактировать профиль</a>
                        </div>
                        <div class="profile-promo-code">
                            <h3>Введите промо код</h3>
                            @include(Helper::layout('forms.first-promo-code'))
                        </div>
                        <div class="request">
                            <div class="note"></div>
                            <a {{ count($profile->codes) == 2 && count($profile->writing) ? '' : 'class="disabled-button"' }} href="javascript:void(0);">Конкурс рассказов</a>
                        </div>
                    </div>
                    <div class="profile-border"></div>
                    <h3>Полученные призы</h3>

                    <div class="gained-prizes">
                        <div class="prize">
                            <div class="ico leo"></div>
                            <p>Курс английского для путешественников</p>
                            @if(count($profile->codes) >= 1)
                            <a class="disabled-button">Получен</a>
                            @endif
                        </div>
                        <div class="prize">
                            <div class="ico spec"></div>
                            <p>Cпецкурс<br>на выбор</p>
                            @if(count($profile->codes) == 2)
                            <a class="disabled-button">Получен</a>
                            @endif
                        </div>
                        <div class="prize">
                            <div class="ico ngt"></div>
                            <p>Путешествие с national geographic traveler</p>
                        @if(count($profile->codes) == 2)
                            @if(count($profile->writing) == 0)
                            <a href="{{ URL::route('profile.tell-story') }}">Написать</a>
                            @elseif(isset($profile->writing->status) && $profile->writing->status == 2)
                            <a class="disabled-button">На модерации</a>
                            @endif
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop