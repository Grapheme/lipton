<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$user = $writing->user;
$text = $writing->writing
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <h2>Конкурс рассказов</h2>
                <div class="share-story-block">
                    {{ nl2br($text) }}
                </div>
                 <div class="share-story-author">
                    <div class="profile-info">
                      <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
                      <h3>{{ $user->name }} {{ $user->surname }}</h3>
                      <p>{{ $user->name }} {{ $user->surname }}br/>
                        @if($user->age > 0){{ $user->age }} {{ Lang::choice('год|года|лет', (int)$user->age ) }}. {{ $user->location }} <br/>@endif
                        {{ $user->city }}<br/>
                        <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user->email, $user->email) }}<br/>
                        <i class="fa fa-fw fa-mobile-phone"></i>{{ $user->phone }}</p>
                    </div>
                </div>
        <!--             @if(!empty($user->photo) && File::exists(public_path($user->photo)))
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
                    @endif -->
                  <!--   <p>
                        <strong>{{ $user->name }} {{ $user->surname }}</strong><br/>
                        @if($user->age > 0){{ $user->age }} {{ Lang::choice('год|года|лет', (int)$user->age ) }}. {{ $user->location }}<br/>@endif
                        {{ $user->created_at->format('d.m.Y H:i:s') }}<br/>
                        {{ $user->city }}<br/>
                        <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user->email, $user->email) }}<br/>
                        <i class="fa fa-fw fa-mobile-phone"></i>{{ $user->phone }}
                    </p> -->
                <div class="sharing-script"><script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,odnoklassniki" data-yashareTheme="counter"></div></div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop