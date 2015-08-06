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
                @include(Helper::layout('forms.first-promo-code'))
            </div>
        </div>
        <div class="block footer">
            <div class="content">
                <div class="plane-block"></div>
                <div class="block-left">
                    {{ $page->block('block-left') }}
                    <a href="{{ pageurl('registering') }}" class="involvement">Принять участие</a>
                </div>
                <div class="block-right">
                    {{ $page->block('block-right') }}
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop