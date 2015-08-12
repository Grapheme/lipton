<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin')->first();
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')  @stop
@section('content')
    <div class="main-content sub-page">
        <div class="block">
            <div class="content">
                <div class="participate-block">
                    <h2>Редактирование информации</h2>

                    <div class="forms-holder full-registration-holder">
                        <form name="profile-edit" class="profile-edit">
                            <div class="form-block-left">
                                <p class="field-title required">Фамилия</p>
                                <input name="surname" for="profile-edit" autocomplete="off">

                                <p class="field-title required">Имя</p>
                                <input name="name" for="profile-edit" autocomplete="off">

                                <p class="field-title">Пол</p>
                                <select id="sex" name="sex" for="profile-edit">
                                    <option value="0">Не указан</option>
                                    <option value="male">Мужской</option>
                                    <option value="female">Женский</option>
                                </select>
                            </div>
                            <div class="devider no-dots"></div>
                            <div class="form-block-right">
                                <p class="field-title required">Email</p>
                                <input name="email" for="profile-edit" autocomplete="off">

                                <p class="field-title required">Телефон</p>
                                <input name="phone" for="profile-edit" autocomplete="off">

                                <p class="field-title">Дата рождения</p>

                                <div class="birthday">
                                    <input name="dd" placeholder="ДД" for="profile-edit" autocomplete="off" class="dd">
                                    <input name="mm" placeholder="ММ" for="profile-edit" autocomplete="off" class="mm">
                                    <input name="yyyy" placeholder="ГГГГ" for="profile-edit" autocomplete="off"
                                           class="yyyy">
                                </div>
                            </div>
                            <button type="submit">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')

@stop