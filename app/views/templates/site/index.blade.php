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
@section('meta_og')
    <meta property="og:title" content="Конкурс рассказов от Lipton Arkenstone" />
    <meta property="og:description" content="Регистрируйте промо-коды в пачках Lipton Arkenstone, напишите рассказ о себе и получите курс английского языка для туристов от Lingualeo или выиграйте увлекательное путешествие в одну из шести стран вместе с National Geographic Traveler" />
    <meta property="og:url" content="{{ URL::to(Request::path()) }}" />
    <meta property="og:image" content="{{ asset(Config::get('site.theme_path').'/images/og-images.jpg') }}" />
    <link rel="image_src" href="{{ asset(Config::get('site.theme_path').'/images/og-images.jpg') }}" />
@stop
@section('style')
@stop
@section('content')
<img class="first-preloader-image preloaded__img" style="display: none;" src="{{ asset(Config::get('site.theme_path').'/images/bg-plane-block.png') }}">
    <div class="preloader">
        <div class="preloader-hack"></div>
        <div class="preloader-plain"></div>
    </div>
    <div class="main-content">
        <div class="slider" data-autoplay="true">
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/1.jpg') }})" class="slide">&nbsp</div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/2.jpg') }})" class="slide">&nbsp</div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/3.jpg') }})" class="slide">&nbsp</div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/3.jpg') }})" class="slide">&nbsp</div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/5.jpg') }})" class="slide">&nbsp</div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/main-slider/6.jpg') }})" class="slide">&nbsp</div>
        </div>
        <div class="block promo-code">
            <div class="content">
                <div class="promo-steps">
                @foreach($steps as $index => $step)
                    <div class="promo-step step-{{ $index + 1 }}">
                        <div class="step-number">{{ $index + 1 }}</div>
                        <h2>{{ $step['title'] }}</h2>
                        <p>{{ $step['desc'] }}</p>
                        @if ($index == 1)
                            @include(Helper::layout('forms.first-promo-code'))
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        <div class="block footer">
            <div class="content">
                <div class="plane-block"></div>
                <div class="block-left">
                    {{ $page->block('block-left') }}
                    <a href="{{ pageurl('auth') }}" class="involvement">Принять участие</a>
                    <div class="footnote">
                        <p>
                            <small><sup>*</sup> Дискавери Колекшн</small>
                        </p>
                    </div>
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