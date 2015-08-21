<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@if(Auth::check())
    <div class="block second-code">
        <div class="second-code-hack"></div>
        @include(Helper::layout('forms.second-promo-code'))
    </div>
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
    <div class="profile-error-wrapper">
        <div class="second-code-hack"></div>
        <div class="error-block">
            <h3>Внимание</h3>
            <p>@if(Session::has('message')){{ Session::get('message') }}@endif</p>
            <a href="#">Закрыть</a>
        </div>
    </div>
    @if(isset($profile->writing->writing) && !empty($profile->writing->writing))
        <div class="block story">
            <div class="second-code-hack"></div>
            <div class="story-popup"><a href="#" class="popup-close-cross"></a>

                <h2>Мой рассказ</h2>

                <div class="story-wrapper">
                    <div class="story-text">
                        {{ nl2br($profile->writing->writing) }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
<div class="password-recovery-holder">
    <div class="second-code-hack"></div>
    <div class="error-block">
        <a class="popup-close-cross" href=""></a>
        <h3>Введите E-mail указанный<br>при регистрации</h3>
        @include(Helper::layout('forms.restore-password'))
    </div>
</div>