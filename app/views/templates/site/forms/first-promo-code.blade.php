<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@if(Auth::check() && Auth::user()->group_id > 3)
{{ Form::open(array('route'=>'signup.participant', 'id'=>'promo-code-form','name'=>'promo-code', 'class'=>'promo-code', 'data-user-auth'=>'authorized')) }}
{{ Form::text('promoCode1', NULL, array('for'=>'promo-code', 'autocomplete'=>'off', 'class'=>'promoCode1')) }}
{{ Form::button('Отправить') }}
{{ Form::close() }}
@else
{{ Form::open(array('route'=>'mainpage', 'id'=>'promo-code-form','name'=>'promo-code', 'class'=>'promo-code')) }}
{{ Form::text('promoCode1', NULL, array('for'=>'promo-code', 'autocomplete'=>'off', 'class'=>'promoCode1')) }}
{{ Form::button('Отправить',array('type'=>'submit', 'data-redirect-authorization'=>pageurl('auth'))) }}
{{ Form::close() }}
@endif