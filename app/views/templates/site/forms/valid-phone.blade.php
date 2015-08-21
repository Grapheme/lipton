<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<div class="sms-wrapper">
    <div class="second-code-hack"></div>
    
    <div class="cropper-holder">
		<h3>Введите код из sms</h3>
		<p>На указанный при регистрации номер мобильного телефона<br>был отправлен код для подтверждения регистрации</p>
		{{ Form::open(array('route'=>'signup.valid-phone','name'=>'sms-check','class'=>'sms-chesk')) }}
		{{ Form::text('code', NULL, array('for'=>'full-registration-form','autocomplete'=>'off')) }}
		<p id="msg-sms-response"></p>
		<a id="js-sms-again" href="{{ URL::route('signup.resend-mobile-phone-confirmation') }}">Выслать код повторно</a>
		{{ Form::button('Отправить',array('type'=>'submit')) }}
		{{ Form::close() }}
    </div>
</div>