<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@if(Auth::check())
{{ Form::open(array('route'=>'', 'id'=>'promo-code-form','name'=>'promo-code', 'class'=>'promo-code', 'data-user-auth'=>'authorized')) }}
{{ Form::text('promoCode1', NULL, array('for'=>'promo-code', 'autocomplete'=>'off', 'class'=>"promoCode1')) }}
{{ Form::button('Отправить') }}
{{ Form::close() }}
@else:
{{ Form::open(array('action'=>'', 'id'=>'promo-code-form','name'=>'promo-code', 'class'=>'promo-code')) }}
{{ Form::text('promoCode1', NULL, array('for'=>'promo-code', 'autocomplete'=>'off', 'class'=>"promoCode1')) }}
{{ Form::button('Отправить',array('data-redirect-authorization'=>pageurl('registering'))) }}
{{ Form::close() }}
@endif