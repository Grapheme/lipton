<?
/**
* TITLE: Обратная связь
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

                <div class="feedback-form-holder">
                    @include(Helper::layout('forms.feedback'))
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop