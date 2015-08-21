<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'auth.participant','class'=>'registration','name'=>'registration-form')) }}
{{ Form::hidden('promo-code', isset($_COOKIE['firstCodeCookie']) ? str_replace(' ', '', $_COOKIE['firstCodeCookie']) : '' ) }}
{{ Form::text('login', NULL, array('for'=>'registration-form','placeholder'=>'Электронная почта','autocomplete'=>'off')) }}
{{ Form::password('password',array('for'=>'registration-form','placeholder'=>'Пароль','autocomplete'=>'off')) }}
<a class="password-recovery" href="#">Забыли пароль?</a>
{{ Form::button('Войти',array('type'=>'submit')) }}
{{ Form::close() }}