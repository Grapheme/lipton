<?php

return array(

    'functions' => array(

        'mainpage' => function() {
            return array(
                'url' => URL::route('mainpage'),
                'text' => 'ГЛАВНАЯ СТРАНИЦА',
                'title' => 'Перейти на главную страницу',
            );
        }

    ),

	'default' => function($params = array()) {

        return array(

            'active_class' => 'active',

            'tpl' => array(
                'container' => '<ul>%elements%</ul>',
                'element_container' => '<li%attr%>%element%</li>',
                'element' => '<a href="%url%"%attr%>%text%</a>',
            ),

            'elements' => array()
        );
    }

);
