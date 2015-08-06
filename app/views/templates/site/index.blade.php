<?
/**
 * TITLE: Главная страница
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
<?php
$steps = array();
if (isset($page['blocks']['steps_1'])):
    if (isset($page['blocks']['steps_1']['meta']['content']) && !empty($page['blocks']['steps_1']['meta']['content'])):
        $steps[] = json_decode($page['blocks']['steps_1']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['steps_2'])):
    if (isset($page['blocks']['steps_2']['meta']['content']) && !empty($page['blocks']['steps_2']['meta']['content'])):
        $steps[] = json_decode($page['blocks']['steps_2']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['steps_3'])):
    if (isset($page['blocks']['steps_3']['meta']['content']) && !empty($page['blocks']['steps_3']['meta']['content'])):
        $steps[] = json_decode($page['blocks']['steps_3']['meta']['content'], TRUE);
    endif;
endif;
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content">
        <div class="block promo-code">
            <div class="content">
                <div class="promo-steps">
                @foreach($steps as $index => $step)
                    <div class="promo-step step-{{ $index + 1 }}">
                        <div class="step-number">{{ $index + 1 }}</div>
                        <h2>{{ $step['title'] }}</h2>
                        <p>{{ $step['desc'] }}</p>
                    </div>
                @endforeach
                </div>
                <form action="http://lipton.dev/build/ajax/promo.json" id="promo-code-form" name="promo-code"
                      data-user-auth="authorized" class="promo-code">
                    <input type="text" name="promoCode1" for="promo-code" autocomplete="off" class="promoCode1">
                    <button type="submit" data-redirect-authorization="/participate.html">Отправить</button>
                </form>
            </div>
        </div>
        <div class="block footer">
            <div class="content">
                <div class="plane-block"></div>
                <div class="block-left">
                    <h3>У меня нет промо-кода</h3>

                    <p>Ничего страшного. Расскажите нам о том, почему именно вы достойны отправиться навстречу
                        приключениям
                        с National Geographic Traveller, и одним 10 из победителей можете стать вы!</p><a
                            href="/participate.html" class="involvement">Принять участие</a>
                </div>
                <div class="block-right"><a href="#" target="_blank" class="traveller-card">
                        <div class="traveller-logo"></div>
                        <h3 class="new-issue">Читайте новый выпуск</h3></a></div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop