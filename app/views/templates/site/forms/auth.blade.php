<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'signin','class'=>'registration','name'=>'registration-form')) }}
{{ Form::hidden('promo-code', isset($_COOKIE['firstCodeCookie']) ? str_replace(' ', '', $_COOKIE['firstCodeCookie']) : '' ) }}
{{ Form::text('login', NULL, array('for'=>'registration-form','placeholder'=>'Электронная почта','autocomplete'=>'off')) }}
{{ Form::password('password',array('for'=>'registration-form','placeholder'=>'Пароль','autocomplete'=>'off')) }}
{{ Form::button('Войти',array('type'=>'submit')) }}
{{ Form::close() }}