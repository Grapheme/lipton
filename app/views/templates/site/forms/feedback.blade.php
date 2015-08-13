<?
/**
* TEMPLATE_IS_NOT_SETTABLE
*/
?>

{{ Form::open(array('route'=>'contact_feedback','id'=>'feedback-form','class'=>'feedback')) }}
<div class="input-row">
{{ Form::text('fio', NULL, array('for'=>'feedback-form','placeholder'=>'Ваше имя и отчество','autocomplete'=>'off')) }}
{{ Form::email('email', NULL, array('for'=>'feedback-form','placeholder'=>'Адрес электронной почты','autocomplete'=>'off')) }}
</div>
{{ Form::textarea('message', NULL, array('for'=>'feedback-form','placeholder'=>'Текст вашего сообщения','autocomplete'=>'off')) }}
{{ Form::button('Отправить', array('type'=>'submit')) }}
{{ Form::close() }}