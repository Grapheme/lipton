<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<div class="block header">
    <div class="partners-block">
        <div class="partner-lingualeo"></div>
        <div class="partner-traveller"></div>
    </div>
    <div class="content">
    @if (Request::is('/'))
        <div class="logo-main"></div>
    @else
        <a href="/" class="logo-main"></a>
    @endif
    </div>
    <div class="icons-block">
        @if(Auth::check())
        <a href="{{ URL::route('dashboard') }}" class="ico-account"></a>
        @endif
        <div href="#" class="ico-burger"></div>
    </div>
    <div class="right-menu-holder">
        {{ Menu::placement('main_menu') }}
    </div>
</div>
<div class="block menu-holder">
    <div class="content">
        <ul class="top-menu">
            <li><a href="http://discovery.liptontea.ru">Чайная коллекция</a></li>
            <li><a href="http://www.liptontea.ru">Мир чая</a></li>
            <li><a href="http://goodstarter.liptontea.ru">Goodstarter</a></li>
        </ul>
    </div>
</div>