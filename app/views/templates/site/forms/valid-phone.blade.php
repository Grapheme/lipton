<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'signup.valid-phone','name'=>'full-registration-form1','class'=>'full-registration1')) }}
<div class="form-block-left">
    <p class="field-title required">Код</p>
    {{ Form::text('code', NULL, array('for'=>'full-registration-form','autocomplete'=>'off')) }}
</div>
<div class="devider no-dots"></div>
<div class="accept-block-holder">
    <p><a id="js-sms-again" href="javascript:void(0);">Выслать код повторно</a></p>
</div>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}