<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'promo.second.register.certificates', 'name'=>'select-gain', 'id'=>'select-certificates-form', 'class'=>'promo-code select-gain')) }}
{{ Form::hidden('ticket_id', Config::get('api.wonLotteryTicketId')) }}
<h3>Выберите желаемый курс</h3>
<div class="fields-holder">
    {{ Form::select('certificate', Config::get('directcrm.certificates'), FALSE, array('id'=>'gain-list1')) }}
    {{ Form::button('Отправить',array('type'=>'submit')) }}
</div>
{{ Form::close() }}