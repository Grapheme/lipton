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
    <div class="main-content">
        <h2> Мой рассказ. {{  $user->name .' '. $user->surname }}</h2>
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
        <p>
            <strong>{{ $user->name }} {{ $user->surname }}</strong><br/>
            @if($user->age > 0){{ $user->age }} {{ Lang::choice('год|года|лет', (int)$user->age ) }}. {{ $user->location }}<br/>@endif
            {{ $user->created_at->format('d.m.Y H:i:s') }}<br/>
            {{ $user->city }}<br/>
            <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user->email, $user->email) }}<br/>
            <i class="fa fa-fw fa-mobile-phone"></i>{{ $user->phone }}
        </p>
        <div>
            {{ nl2br($text) }}
        </div>
    </div>
    1
@stop
@section('scripts')
@stop