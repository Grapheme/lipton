<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::open(array('route'=>'signup.participant','name'=>'full-registration-form','class'=>'full-registration')) }}
{{ Form::hidden('token', Session::get('token')) }}
{{ Form::hidden('identity', Session::get('identity')) }}
{{ Form::hidden('profile', Session::get('profile')) }}
{{ Form::hidden('uid', Session::get('uid')) }}
{{ Form::hidden('photo_big', Session::get('photo_big')) }}
{{ Form::hidden('photo', Session::get('photo')) }}
{{ Form::hidden('network', Session::get('network')) }}
{{ Form::hidden('verified_email', Session::has('verified_email') ? Session::get('verified_email') : 0) }}
{{ Form::hidden('promo-code', isset($_COOKIE['firstCodeCookie']) ? str_replace(' ', '', $_COOKIE['firstCodeCookie']) : '' ) }}
<div class="form-block-left">
    <p class="field-title required">Фамилия</p>
    {{ Form::text('surname', trim(Session::get('last_name')), array('for'=>'full-registration-form','autocomplete'=>'off')) }}
    <p class="field-title required">Имя</p>
    {{ Form::text('name', trim(Session::get('first_name')), array('for'=>'full-registration-form','autocomplete'=>'off')) }}
    <p class="field-title">Пол</p>
    {{ Form::select('sex', array('Женский', 'Мужской'), Session::get('sex'), array('id' => 'sex', 'for' => 'full-registration-form')) }}
</div>
<div class="devider no-dots"></div>
<div class="form-block-right">
    <p class="field-title required">Email</p>
    {{ Form::email('email', Session::get('email'), array('for'=>'full-registration-form','autocomplete'=>'off')) }}
    <p class="field-title required">Телефон</p>
    {{ Form::text('phone', NULL, array('for'=>'full-registration-form','autocomplete'=>'off')) }}
    <p class="field-title">Дата рождения</p>
    <?php
        $bdate = array('d'=>'', 'm'=>'', 'y'=>'');
        if(Session::has('bdate')):
            $bdate['d'] = (new myDateTime())->setDateString(Session::get('bdate'))->format('d');
            $bdate['m'] = (new myDateTime())->setDateString(Session::get('bdate'))->format('m');
            $bdate['y'] = (new myDateTime())->setDateString(Session::get('bdate'))->format('Y');
        endif;
    ?>
    <div class="birthday">
        <input name="dd" value="{{ @$bdate['d'] }}" placeholder="ДД" for="full-registration-form" autocomplete="off" class="dd">
        <input name="mm" value="{{ @$bdate['m'] }}" placeholder="ММ" for="full-registration-form" autocomplete="off" class="mm">
        <input name="yyyy" value="{{ @$bdate['y'] }}" placeholder="ГГГГ" for="full-registration-form" autocomplete="off" class="yyyy">
    </div>
</div>
<div class="accept-block-holder">
    <label for="accept">.</label>
    <input id="accept" name="acceptCheckbox" type="checkbox">
    <p>Я изнакомился и согласен с <a href="/uploads/files/1440405199_1756731.pdf">правилами акции и условиями
            использования</a> предоставленных мной сведений и выражаю свое согласие на их
        использование</p>
    <p class="acceptionError">Необходимо согласиться с правилами акции и условиями использования.</p>
</div>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}