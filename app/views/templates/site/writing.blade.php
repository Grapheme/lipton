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
                @if(Auth::check() && Auth::user()->id == $user->id) 
                <div class="sharing-script">
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
                    <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small"
                         data-yashareQuickServices="vkontakte,facebook,odnoklassniki" data-yashareTheme="counter"></div>
                </div>
                @else
                    <!-- Put this script tag to the <head> of your page -->
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

                    <script type="text/javascript">
                      VK.init({apiId: 5042647, onlyWidgets: true});
                    </script>

                    <!-- Put this div tag to the place, where the Like block will be -->
                    <div id="vk_like"></div>
                    <script type="text/javascript">
                    VK.Widgets.Like("vk_like", {type: "button"});
                    </script>


                    <!-- 1. Include the JavaScript SDK on your page once, ideally right after the opening <body> tag. -->
                    <!-- <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=608272645916709";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script> -->
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=608272645916709";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>

                    <!-- 2. Place the code for your plugin wherever you want the plugin to appear on your page. -->
                    <!-- <div class="fb-like" data-href="http://www.promo-discovery.liptontea.ru" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div> -->
                    <div class="fb-like" data-href="http://lipton.dev.grapheme.ru" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>


                    <!-- Одноклассники -->
                    <div id="ok_shareWidget"></div>
                    <script>
                    !function (d, id, did, st) {
                      var js = d.createElement("script");
                      js.src = "https://connect.ok.ru/connect.js";
                      js.onload = js.onreadystatechange = function () {
                      if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                        if (!this.executed) {
                          this.executed = true;
                          setTimeout(function () {
                            OK.CONNECT.insertShareWidget(id,did,st);
                          }, 0);
                        }
                      }};
                      d.documentElement.appendChild(js);
                    }(document,"ok_shareWidget","http://www.promo-discovery.liptontea.ru","{width:145,height:25,st:'straight',sz:12,ck:3}");
                    </script>

                
                @endif
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop