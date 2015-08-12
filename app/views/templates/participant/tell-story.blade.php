<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')  @stop
@section('content')
<div class="main-content sub-page">
    <div class="block">
        <div class="content">
            <h2>Расскажите нам о себе</h2>

            <h3 class="tell-story-header">Возможно именно вы станете победителем незабываемого путешествия от&nbsp;Lipton
                и National Geographic Traveler</h3>

            <div class="feedback-form-holder">
                @include(Helper::acclayout('forms.story'))
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')

@stop