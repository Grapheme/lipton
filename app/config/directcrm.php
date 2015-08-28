<?php
return array(

    'headers' => array(
        'x_customer_agent' => @$_SERVER ['HTTP_USER_AGENT'],
        'x_chanel' => 'LiptonDiscovery2015',
        'x_brand' => 'lipton',
        'x_customer_url' => Request::root(),
        'authorization' => array(
            'key' => 'D4LrRgsXOEDwWSqlWwej',
            'staffLogin' => 'GrapeLipton2015',
            'staffPassword' => 'ZR2ekMJt',
            'customerId' => '',
            'sessionKey' => ''
        )
    ),
    #'server_url' => 'https://unilever-services.directcrm.ru',
    #'host' => 'unilever-services.directcrm.ru',

    'server_url' => 'https://unilever-services.staging.directcrm.ru',
    'host' => 'unilever-services.staging.directcrm.ru',

    'send_local_messages' => FALSE,

    'certificates' => array(
        'LiptonLinguaLeoForTravellers' => 'Онлайн курс «Английский для туристов»',
        'LiptonLinguaLeoGrammarBeginners' => 'Курс грамматики для начинающих',
        'LiptonLinguaEGE' => 'Курс ЕГЭ',
        'LiptonLinguaLeoEnglishBeginners' => 'Курс английского для начинающих',
        'LiptonLinguaLeoBusinessEnglish' => 'Курс бизнес английского',
        'LiptonLinguaLeoMarketing' => 'Курс маркетинг',
        'LiptonLinguaLeoStartup' => 'Курс стартап',
        'LiptonLinguaIELTSGeneral' => 'Курс IELTS General',
        'LiptonLinguaIELTSAcademic' => 'Курс IELTS Academic',
        'LiptonLinguaGIA' => 'Курс ГИА',
    )
);