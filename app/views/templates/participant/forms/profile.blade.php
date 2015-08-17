<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>

    <div class="cropper-wrapper">
        <div class="second-code-hack"></div>
        <div class="block cropper-holder">
            <a href="#" class="popup-close-cross"></a>
            <div class="cropper"></div>
            <div class="cropper-tools">
                <a class="close" href="#">Отмена</a>
                <a class="save" href="#">Сохранить</a>
            </div>
        </div>
    </div>

{{ Form::model($profile,array('route'=>'profile.save','name'=>'profile-edit','class'=>'profile-edit')) }}

    <div class="accept-block-holder avatar-hack">
        <div class="profile-info">
        @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
            <div style="background-image: url({{ asset($profile->photo) }});" class="avatar"></div>
        @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
            <div style="background-image: url({{ asset($profile->ulogin->photo_big) }});" class="avatar"></div>
        @else
            <img src="{{ asset(Config::get('site.theme_path').'/images/avatar_default.png') }}" alt="{{ $user->name }}"
                 class="{{ $user->name }}">
        @endif
            <div class="hidden-input">
                <span>Сменить аватар</span>
                <input accept="image/*" type="file" class="js-cropper-image" name="profile_image">
            </div>
            <input class="hidden-avatar-input" name="avatar">
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
        <p class="field-title required">Город</p>
        {{ Form::text('city', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

        <p class="field-title required">Телефон</p>
        {{ Form::text('phone', NULL, array('for'=>'profile-edit','autocomplete'=>'off')) }}

        <p class="field-title">Дата рождения</p>
        <div class="birthday">
            <input name="dd" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('d') }}" placeholder="ДД" for="full-registration-form" autocomplete="off" class="dd">
            <input name="mm" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('m') }}" placeholder="ММ" for="full-registration-form" autocomplete="off" class="mm">
            <input name="yyyy" value="{{ (new myDateTime())->setDateString($profile->bdate)->format('Y') }}" placeholder="ГГГГ" for="full-registration-form" autocomplete="off" class="yyyy">
        </div>
    </div>
{{ Form::button('Отправить',array('type'=>'submit')) }}
{{ Form::close() }}