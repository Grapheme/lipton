<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>

<header>
    @if (Request::is('/'))

    @else

    @endif
</header>

<div class="block header">
    <div class="partners-block">
        <div class="partner-traveller"></div>
        <div class="partner-lingualeo"></div>
    </div>
    <div class="content">
    @if (Request::is('/'))
        <div class="logo-main"></div>
    @else
        <a href="/" class="logo-main"></a>
    @endif
    </div>
    <div class="icons-block">
        <a href="#" class="ico-account"></a>
        <div href="#" class="ico-burger"></div>
    </div>
    <div class="right-menu-holder">
        {{ Menu::placement('main_menu') }}
    </div>
</div>
<div class="block menu-holder">
    <div class="content">
        {{ Menu::placement('center_menu') }}
    </div>
</div>