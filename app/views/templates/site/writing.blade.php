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

function curPageURL() {
   $pageURL = 'http';
   if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
   $pageURL .= "://";
   if (@$_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= @$_SERVER["SERVER_NAME"].":".@$_SERVER["SERVER_PORT"].@$_SERVER["REQUEST_URI"];
  } else {
      $pageURL .= @$_SERVER["SERVER_NAME"].@$_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}
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
                <div class="sharing-script clearfix">
                    <div class="widget">
                        <div id="vk_like"></div>
                    </div>
                    <div class="widget">
                        <div id="fb-root"></div>
                        <!-- Your like button code -->
                        <div class="fb-like" 
                            data-href="<?= curPageURL(); ?>" 
                            data-layout="button_count" 
                            data-action="like" 
                            data-show-faces="false" 
                            data-share="false">
                        </div>
                    </div>
                    <div class="widget">
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
                            }(document,"ok_shareWidget","http://www.promo-discovery.liptontea.ru","{width:170,height:30,st:'straight',sz:20,ck:3}");
                        </script>
                    </div>
                    
                    <!-- <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
                    <script type="text/javascript">
                      VK.init({apiId: 5042647, onlyWidgets: true});
                    </script>
                    <div id="vk_like"></div>
                    <script type="text/javascript">
                    VK.Widgets.Like("vk_like", {type: "button"});
                    </script> -->

                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
                    <script type="text/javascript">
                      VK.init({apiId: 5044004, onlyWidgets: true});
                    </script>
                    <script type="text/javascript">
                        VK.Widgets.Like("vk_like", {type: "button"});
                    </script>

                    <!-- Load Facebook SDK for JavaScript -->
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=608272645916709";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>

                </div>
                @endif
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop