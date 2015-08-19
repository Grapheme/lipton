<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<script src="//ulogin.ru/js/ulogin.js"></script>
{{ HTML::script(Config::get('site.theme_path').'/scripts/main.concat.js') }}
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-61873602-2', 'auto');
    ga('send', 'pageview');

    var serviceDomain = 'tracker.directcrm.ru';
    (function (window, document, script, url, objectName) {
        window[objectName] = window[objectName] || function () {
                    (window[objectName].Queue = window[objectName].Queue || []).push(arguments);
                },
                a = document.createElement(script),
                m = document.getElementsByTagName(script)[0];
        a.async = 1;
        a.src = url + '?v=' + Math.random();
        m.parentNode.insertBefore(a, m);
    })(window, document, 'script', '//' + serviceDomain + '/scripts/v1/tracker.js', 'directCrm');
    directCrm('create', {
        projectSystemName: 'Unilever',
        brandSystemName: 'Lipton',
        pointOfContactSystemName: 'LiptonDiscovery2015',
        projectDomain: 'unilever-services.directcrm.ru',
        serviceDomain: serviceDomain
    });
</script>

