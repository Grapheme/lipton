





                 class="avatar"></div>
               for="full-registration-form" autocomplete="off" class="dd">
               for="full-registration-form" autocomplete="off" class="mm">
               placeholder="ГГГГ" for="full-registration-form" autocomplete="off" class="yyyy">
            <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
            <div style="background-image: url({{ asset($profile->ulogin->photo_big) }});" class="avatar"></div>
            <div style="background-image: url({{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }});"
            <input accept="image/*" type="file" class="js-cropper-image" name="profile_image">
            <span>Сменить аватар</span>
        </div>
        <div class="hidden-input">
        <input name="dd" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('d') }}" placeholder="ДД"
        <input name="mm" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('m') }}" placeholder="ММ"
        <input name="yyyy" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('Y') }}"
        <input type="text" class="hidden-avatar-input" name="avatar">
        @else
        @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
        @endif
        @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
    </div>
    </div>
    <div class="birthday">
    <div class="profile-info">
    <p class="field-title required">Город</p>
    <p class="field-title required">Имя</p>
    <p class="field-title required">Телефон</p>
    <p class="field-title required">Фамилия</p>
    <p class="field-title">Дата рождения</p>
    <p class="field-title">Пол</p>
    {{ Form::select('sex', array('Женский', 'Мужской'), Session::get('sex'), array('id' => 'sex', 'for' => 'profile-edit')) }}
    {{ Form::text('city', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}
    {{ Form::text('name', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}
    {{ Form::text('phone', NULL, array('for'=>'profile-edit','autocomplete'=>'off','class'=>'users-phone')) }}
    {{ Form::text('surname', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}
 * TEMPLATE_IS_NOT_SETTABLE
 */
/**
</div>
</div>
</div>
<?
<div class="accept-block-holder avatar-hack">
<div class="devider no-dots"></div>
<div class="form-block-left">
<div class="form-block-right">
<div style="width: 100%; height: 30px;"></div>
?>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}
{{ Form::model($profile,array('route'=>'profile.save','name'=>'profile-edit','class'=>'profile-edit')) }}