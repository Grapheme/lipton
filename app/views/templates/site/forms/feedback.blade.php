<?
/**
* TEMPLATE_IS_NOT_SETTABLE
*/
?>

{{ Form::open(array('route'=>'contact_feedback','id'=>'feedback-form','class'=>'feedback')) }}
<div class="input-row">
	<div class="input-cell">
	{{ Form::text('fio', Auth::check() ? Auth::user()->name .' '. Auth::user()->surname : '', array('for'=>'feedback-form','placeholder'=>'Ваше имя и отчество','autocomplete'=>'off')) }}
	</div>
	<div class="input-cell">
	{{ Form::email('email', Auth::check() ? Auth::user()->email : '', array('for'=>'feedback-form','placeholder'=>'Адрес электронной почты','autocomplete'=>'off')) }}
	</div>
</div>
{{ Form::textarea('message', NULL, array('for'=>'feedback-form','placeholder'=>'Текст вашего сообщения','autocomplete'=>'off')) }}
{{ Form::button('Отправить', array('type'=>'submit')) }}
{{ Form::close() }}