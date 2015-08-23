<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'promo.second.register.certificates', 'name'=>'select-gain', 'class'=>'promo-code select-gain')) }}
<h3>Выберите желаемый курс</h3>
<div class="fields-holder">
    {{ Form::select('certificate', Config::get('directcrm.certificates'), FALSE, array('id'=>'gain-list')) }}
    {{ Form::button('Отправить',array('type'=>'submit')) }}
</div>
{{ Form::close() }}