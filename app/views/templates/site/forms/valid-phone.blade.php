<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'signup.valid-phone','name'=>'full-registration-form1','class'=>'full-registration1')) }}
	<a href="#" class="popup-close-cross"></a>
	<h3>Введите код из sms</h3>
	<p>На указанный при регистрации номер мобильного телефона<br>был отправлен код для подтверждения регистрации</p>
	<form name="sms-check" class="sms-chesk">
	<!-- <input for="sms-chesk" name="sms-chesk-input" class="sms-chesk-input"> -->
		{{ Form::text('code', NULL, array('for'=>'full-registration-form','autocomplete'=>'off')) }}
		<a id="js-sms-again" href="javascript:void(0);">Выслать код повторно</a>
	<!-- <button>Отправить</button> -->
		{{ Form::button('Отправить',array('type'=>'submit')) }}
	</form>
{{ Form::close() }}


<!--     {{ Form::text('code', NULL, array('for'=>'full-registration-form','autocomplete'=>'off')) }}

<div class="devider no-dots"></div>
<div class="accept-block-holder">
    <p></p>
</div>
{{ Form::button('Отправить',array('type'=>'submit')) }} -->
{{ Form::close() }}