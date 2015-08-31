<?
/**
 * MENU_PLACEMENTS: main_menu=Основное меню|footer_menu=Нижнее меню
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
    	@include(Helper::layout('head'))
	    @yield('style')
    </head>
    <body>
        <!-- Google Tag Manager -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MQXWTC"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MQXWTC');</script>
        <!-- End Google Tag Manager -->

        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        @include(Helper::layout('popups'))
        @include(Helper::layout('header'))

        @section('content')
            {{ @$content }}
        @show

        @section('footer')
            @include(Helper::layout('footer'))
        @show

        @include(Helper::layout('scripts'))

        @section('overlays')
        @show

        @section('scripts')
        @show

        {{ Config::get('app.settings.main.tpl_footer_counters') }}

    </body>
</html>