<?
/**
 * TITLE: Страны
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
                <h2>{{ $page->seo->h1 }}</h2>

                <div class="collection">
                    <div class="country cuba">
                        <p>Куба</p>
                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content cuba">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/resolt.png') }}">
                                </div>
                                <div class="text">Черный чай с бодрящим ароматом ананаса, <br>
                                    грейпфрута и нотками рома откроет Вам <br>
                                    яркие и зажигательные ритмы острова Кубы.
                                </div>
                                <div class="text translate">*Cuba Resort — Куба Резорт</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-cuba-big.png"></div>
                        </div>
                    </div>
                    <div class="country england">
                        <p>Англия</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content england">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/earl-grey.png') }}">
                                </div>
                                <div class="text">Классический черный чай с ароматом<br>
                                    бергамота познакомит Вас с истинными традициями<br>
                                    британского чаепития Викторианской эпохи. </div>
                                <div class="text translate">*Victorian Earl Grey — Викториэн Эрл Грэй</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-england-big.png"></div>
                        </div>
                    </div>
                    <div class="country china">
                        <p>Китай</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content china">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/temple.png') }}">
                                </div>
                                <div class="text">
                                    Классический зеленый чай с легким <br>
                                    цветочным ароматом откроет загадочный <br>
                                    мир Азии и подарит мгновенье <br>
                                    безмятежности и спокойствия!
                                </div>
                                <div class="text translate">*Oriental Temple — Ориентл Тэмпл</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-china-big.png"></div>
                        </div>
                    </div>
                    <div class="country shri">
                        <p>Шри-ланка</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content shri">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/ceylon.png') }}">
                                </div>
                                <div class="text">
                                    Классический черный чай с острова Цейлон<br>
                                    с нотками сухофруктов и красно-янтарным <br>
                                    оттенком настоя откроет великолепие <br>
                                    жемчужины Индийского океана.
                                </div>
                                <div class="text translate">*Heart of Ceylon — Хат оф Сейлон</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-shri-big.png"></div>
                        </div>
                    </div>
                    <div class="country turkey">
                        <p>Турция</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content turkey">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/delight.png') }}">
                                </div>
                                <div class="text">Слегка терпкий зеленый чай с медовым <br>
                                    послевкусием и легким ароматом зеленого <br>
                                    яблока и инжира откроет Вам утонченный <br>
                                    и загадочный мир Востока.
                                </div>
                                <div class="text translate">*Sultan Delight — Салтн Дилайт</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-turkey-big.png"></div>
                        </div>
                    </div>
                    <div class="country marokko">
                        <p>Марокко</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content marokko">
                            <div class="column-left">
                                <div class="logo">
                                    <img src="{{ asset(Config::get('site.theme_path').'/images/countries/marrakesh.png') }}">
                                </div>
                                <div class="text">Пряный чёрный чай со вкусом мяты и мандарина<br>и слегка горьковатым
                                    послевкусием имбиря<br>откроет вам пленительный мир Марокко,<br>полный контрастов и
                                    разных культур.
                                </div>
                                <div class="text translate">*Spicy Marrakesh — Спайси Марракеш</div>
                            </div>
                            <div class="column-right"><img src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-marokko-big.png"></div>
                        </div>
                    </div>
                </div>
                <div class="learn-more-popup-holder">
                    <div class="learn-more-popup-hack"></div>
                    <div class="learn-more-popup"><a href="#" class="popup-close-cross"></a></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop