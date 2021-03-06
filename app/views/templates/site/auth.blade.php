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
                    @if(Auth::guest())
                    <div class="forms-holder">
                        @include(Helper::layout('forms.auth'))
                        <div class="devider"></div>
                        <div class="login-block">
                            <h3>Нет учетной записи?</h3>
                            <a href="{{ pageurl('registering') }}" class="login">Зарегистрироваться</a>

                            <h3>Войти через соцсеть</h3>

                            <div class="soc-holder" id="uLogin_490f3e3f" data-uloginid="490f3e3f"
                                 data-ulogin="mobilebuttons=0;display=buttons;optional=first_name,last_name,email,sex,bdate,photo,photo_big;redirect_uri={{ URL::route('signin.ulogin') }}">
                                <a data-uloginbutton="facebook" class="soc fb"></a>
                                <a data-uloginbutton="vkontakte" class="soc vk"></a>
                                <a data-uloginbutton="odnoklassniki" class="soc ok"></a>
                            </div>
                        </div>
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