<?
/**
 * TITLE: Правила акции
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

                <div class="rules-row">
                    <div class="rule-item rule-1">
                        <div class="rule-img"></div>
                        <h3>{{ $page->block('stage_1') }}</h3>
                    </div>
                    <div class="rule-item rule-2">
                        <div class="rule-img"></div>
                        <h3>{{ $page->block('stage_2') }}</h3>
                    </div>
                    <div class="rule-item rule-3">
                        <div class="rule-img"></div>
                        <h3>{{ $page->block('stage_3') }}</h3>
                    </div>
                    <div class="rule-item rule-4">
                        <div class="rule-img"></div>
                        <h3>{{ $page->block('stage_4') }}</h3>
                    </div>
                </div>
                <div class="rules-text-block">
                    {{ $page->block('content') }}
                    <div class="footnote">
                        {{ $page->block('footnote') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop