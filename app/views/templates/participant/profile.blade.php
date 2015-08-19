<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin')->first();
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')  @stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <div class="participate-block">
                    <h2>Редактирование профиля</h2>
                    <div class="forms-holder full-registration-holder">
                        @include(Helper::acclayout('forms.profile'))
                    </div>
                    <div class="sms-wrapper">
                        <div class="second-code-hack"></div>
                        <div class="cropper-holder">
                            @include(Helper::layout('forms.valid-phone'))
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')

@stop