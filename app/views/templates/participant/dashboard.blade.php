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
if(count($prizes) > 1):
    if(isset($prizes['LinguaLeo.LotteryTicket']) && empty($prizes['LinguaLeo.LotteryTicket']['certificateCode'])):
        Config::set('api.wonLotteryTicketId', $prizes['LinguaLeo.LotteryTicket']['customerPrize_id']);
    endif;
    foreach($prizes as $systemName => $prize):
        if($systemName !== 'LiptonLinguaLeoForTravellers'):
            $second_prize = $prize;
        endif;
    endforeach;
endif;
?>
@extends(Helper::layout())
@section('title')
    Личный кабинет
@stop
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
                            @if(empty($prizes))
                                @include(Helper::layout('forms.first-promo-code'))
                            @else
                                @include(Helper::layout('forms.second-promo-code'))
                            @endif
                        </div>
                        <div class="request">
                            <div class="note"> </div>

                            @if(isset($profile->writing->writing) && !empty($profile->writing->writing))
                                @if($profile->writing->status == 1)
                                    <a href="{{ URL::route('show.participant.writing', $profile->writing->id.'-'.BaseController::stringTranslite(Auth::user()->name.'-'.Auth::user()->surname)) }}">Конкурс рассказов</a>
                                    <div class="moderation positive">Ваш рассказ одобрен
                                        <div class="bullet"></div>
                                    </div>
                                @elseif($profile->writing->status == 2)
                                    <a href="{{ URL::route('show.participant.writing', $profile->writing->id.'-'.BaseController::stringTranslite(Auth::user()->name.'-'.Auth::user()->surname)) }}">Конкурс рассказов</a>
                                    <div class="moderation neutral">Ваш рассказ на модерации
                                        <div class="bullet"></div>
                                    </div>
                                @elseif($profile->writing->status == 3)
                                    <a href="{{ URL::route('profile.tell-story') }}">Конкурс рассказов</a>
                                    <div class="moderation negative">Ваш рассказ отклонен
                                        <div class="bullet"></div>
                                    </div>
                                @endif
                            @else
                                <a href="{{ URL::route('profile.tell-story') }}">Конкурс рассказов</a>
                            @endif

                        </div>
                    </div>
                    <div class="profile-border"></div>
                    <h3>Галерея призов</h3>

                    <div class="gained-prizes">
                        <div class="prize">
                            <div class="ico leo js-prize-leo"></div>
                            <p>Онлайн курс <nobr>«Английский для туристов»</nobr></p>
                            @if(!empty($prizes['LiptonLinguaLeoForTravellers']))
                                <p class="js-achived-leo">Доступ к онлайн курсу выслан на указанную почту.</p>
                            @endif
                        </div>
                        <div class="prize js-prize-spec">
                            <div class="ico spec"></div>
                            <p>Онлайн курс<br>на выбор</p>
                            @if(count($prizes) > 1)
                                @if(isset($prizes['LinguaLeo.LotteryTicket']) && empty($prizes['LinguaLeo.LotteryTicket']['certificateCode']))
                                <a href="javascript:void(0);" class="js-select-certificates">Получить</a>
                                @elseif(isset($second_prize))
                                <p class="js-achived-spec">Доступ к онлайн курсу выслан на указанную почту.</p>
                                @endif
                            @endif
                        </div>
                        <div class="prize">
                            <div class="ico ngt"></div>
                            <p>Путешествие с National Geographic Traveler<br>&nbsp;</p>
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