<?
/**
 * TITLE: Галерея призов
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <h2>{{ $page->seo->h1 }}</h2>

                <div class="prize-gallery">
                    <div class="prize-block third-place">
                        <div class="big-ico"></div>
                        {{ $page->block('first_prize') }}
                    </div>
                    <div class="prize-block second-place">
                        <div class="big-ico"></div>
                        {{ $page->block('secon_prize') }}
                    </div>
                    <div class="prize-block first-place">
                        <div class="big-ico"></div>
                        {{ $page->block('third_prize') }}
                    </div>
                    <a href="{{ pageurl('countries') }}" class="prize-link">Lipton Discovery Collection</a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop