<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$user = $writing->user;
$text = $writing->writing;

$bdate = new Carbon($user->bdate);
$now = Carbon::now();
?>
@extends(Helper::layout())
@section('meta_og')
    <meta property="og:title" content="Поставь лайк — поддержи меня в конкурсе рассказов от Lipton Arkenstone" />
    <meta property="og:description" content="{{{ nl2br($text) }}}" />
    <meta property="og:url" content="{{ URL::to(Request::path()) }}" />
    <meta property="og:image" content="{{ asset(Config::get('site.theme_path').'/images/og-images.jpg') }}" />
    <link rel="image_src" href="{{ asset(Config::get('site.theme_path').'/images/og-images.jpg') }}" />
@stop
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
                    @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                        <div style="background-image: url({{ asset($user->photo) }});"
                             class="avatar"></div>
                    @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                        <div style="background-image: url({{ $user->ulogin->photo_big }});"
                             class="avatar"></div>
                    @elseif(!empty($user->photo))
                        <div style="background-image: url({{ $user->photo }});"
                             class="avatar"></div>
                    @else
                        <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
                             class="avatar"></div>
                    @endif
                        <h3>{{ $user->name }} {{ $user->surname }}</h3>
                        <p>
                        @if($bdate->diffInYears($now) > 0)
                            {{ $bdate->diffInYears($now).' '.Lang::choice('год|года|лет', $bdate->diffInYears($now)) }}
                        @endif
                            {{ $user->city }}
                        </p>
                    </div>
                </div>
                <div class="sharing-script">
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
                    <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small"
                         data-yashareQuickServices="vkontakte,facebook,odnoklassniki" data-yashareTheme="counter"></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop