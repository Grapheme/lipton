<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'auth.participant.restore','class'=>'password-recovery-form','name'=>'password-recovery-form', 'method'=>'POST')) }}
{{ Form::email('emailRecovery', NULL, array('for'=>'password-recovery-form', 'autocomplete'=>'off')) }}
<p class="recovery-message-text"></p>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}