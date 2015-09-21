<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
{{ Form::model($profile,array('route'=>'profile.save','name'=>'profile-edit','class'=>'profile-edit')) }}

<div class="accept-block-holder avatar-hack">
    <div class="profile-info">
        @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
            <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
        @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
            <div style="background-image: url({{ asset($profile->ulogin->photo_big) }});" class="avatar"></div>
        @else
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
                 class="avatar"></div>
        @endif
        <div class="hidden-input">
            <span>Сменить аватар</span>
            <input accept="image/*" type="file" class="js-cropper-image" name="profile_image">
        </div>
        <input type="text" class="hidden-avatar-input" name="avatar">
    </div>
</div>
<div class="form-block-left">
    <p class="field-title required">Фамилия</p>
    {{ Form::text('surname', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

    <p class="field-title required">Имя</p>
    {{ Form::text('name', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

    <p class="field-title">Пол</p>
    {{ Form::select('sex', array('Женский', 'Мужской'), Session::get('sex'), array('id' => 'sex', 'for' => 'profile-edit')) }}
</div>
<div class="devider no-dots"></div>
<div class="form-block-right">
    <!--<p class="field-title required">Город</p>-->
    {{ Form::hidden('city', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

    <p class="field-title required">Телефон</p>
    {{ Form::text('phone', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

    <p class="field-title">Дата рождения</p>

    <div class="birthday">
        <input name="dd" value="{{ Carbon::createFromFormat('Y-m-d H:i:s', $profile->bdate)->format('d') }}" placeholder="ДД"
               for="full-registration-form" autocomplete="off" class="dd">
        <input name="mm" value="{{  Carbon::createFromFormat('Y-m-d H:i:s', $profile->bdate)->format('m') }}" placeholder="ММ"
               for="full-registration-form" autocomplete="off" class="mm">
        <input name="yyyy" value="{{  Carbon::createFromFormat('Y-m-d H:i:s', $profile->bdate)->format('Y') }}"
               placeholder="ГГГГ" for="full-registration-form" autocomplete="off" class="yyyy">
    </div>
</div>
<div style="width: 100%; height: 30px;"></div>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}