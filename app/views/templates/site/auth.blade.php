<?
/**
* TITLE: Авторизация
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
                    <h2>{{ $page->seo->h1 }}</h2>
                    <div class="forms-holder">
                        @include(Helper::layout('forms.auth'))
                        <div class="devider"></div>
                        <div class="login-block">
                            <h3>Нет учетной записи?</h3>
                            <a href="{{ pageurl('registration') }}" class="login">Зарегистрироваться</a>
                            <h3>Войти через соцсеть</h3>
                            <div class="soc-holder">
                                <a class="soc fb"></a>
                                <a class="soc vk"></a>
                                <a class="soc ok"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop