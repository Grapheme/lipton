<?
/**
 * TITLE: Регистрация
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <div class="participate-block">
                    <h2>Принять участие</h2>
                    @if(Auth::guest())
                    <div class="forms-holder full-registration-holder">
                        <form name="full-registration-form" class="full-registration">
                            <div class="form-block-left">
                                <p class="field-title required">Фамилия</p>
                                <input name="surname" for="full-registration-form" autocomplete="off">

                                <p class="field-title required">Имя</p>
                                <input name="name" for="full-registration-form" autocomplete="off">

                                <p class="field-title">Пол</p>
                                <select id="sex" name="sex" for="full-registration-form">
                                    <option value="0">Не указан</option>
                                    <option value="male">Мужской</option>
                                    <option value="female">Женский</option>
                                </select>
                            </div>
                            <div class="devider no-dots"></div>
                            <div class="form-block-right">
                                <p class="field-title required">Email</p>
                                <input name="email" for="full-registration-form" autocomplete="off">

                                <p class="field-title required">Телефон</p>
                                <input name="phone" for="full-registration-form" autocomplete="off">

                                <p class="field-title">Дата рождения</p>

                                <div class="birthday">
                                    <input name="dd" placeholder="ДД" for="full-registration-form" autocomplete="off"
                                           class="dd">
                                    <input name="mm" placeholder="ММ" for="full-registration-form" autocomplete="off"
                                           class="mm">
                                    <input name="yyyy" placeholder="ГГГГ" for="full-registration-form"
                                           autocomplete="off"
                                           class="yyyy">
                                </div>
                            </div>
                            <div class="accept-block-holder">
                                <label for="accept">.</label>
                                <input id="accept" name="acceptCheckbox" type="checkbox">

                                <p>Я согласен с <a href="#">правилами акции</a>, ознакомился с <a href="#">условиями
                                        использования</a> предоставленных<br>мной сведений и выражаю свое согласие на их
                                    использование</p>
                            </div>
                            <button>Отправить</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@if(Auth::check()):
<script type="application/javascript">
    $(function(){
        window.location.href = '{{ URL::route('dashboard') }}';
    })
</script>
@endif
@stop