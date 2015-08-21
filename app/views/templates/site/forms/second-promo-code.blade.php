<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'promo.second.register', 'id'=>'promo-code2-form','name'=>'promo-code-2', 'class'=>'promo-code promo-code-2')) }}
<h3>Есть еще один промо-код?</h3>
<div class="fields-holder">
    {{ Form::text('promoCode2', NULL, array('for'=>'promo-code2', 'autocomplete'=>'off', 'class'=>'promoCode1')) }}
    {{ Form::button('Отправить',array('type'=>'submit')) }}
    <a href="javascript:void(0);">Отмена</a>
</div>
{{ Form::close() }}