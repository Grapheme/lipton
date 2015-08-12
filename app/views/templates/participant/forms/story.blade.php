<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::model($story,array('route'=>'promo.third.register','id'=>'story-form','class'=>'story')) }}
{{ Form::textarea('message',$story->writing, array('for'=>'story-form', 'placeholder'=>'Ваш рассказ о себе', 'autocomplete'=>'off')) }}
{{ Form::button('Принять участие',array('type'=>'submit')) }}
{{ Form::close() }}