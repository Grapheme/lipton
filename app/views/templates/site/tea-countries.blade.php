<?
/**
 * TITLE: Страны
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
<?php
$countries = array();
for ($i = 1; $i <= 6; $i++):
    if (isset($page['blocks']['country_' . $i])):
        if (isset($page['blocks']['country_' . $i]['meta']['content']) && !empty($page['blocks']['country_' . $i]['meta']['content'])):
            $countries[$i] = json_decode($page['blocks']['country_' . $i]['meta']['content'], TRUE);
        endif;
    endif;
endfor;
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
                                <div class="text">{{ @$countries[1]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[1]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-cuba-big.png">
                            </div>
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
                                <div class="text">{{ @$countries[6]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[6]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-england-big.png">
                            </div>
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
                                <div class="text">{{ @$countries[5]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[5]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-china-big.png">
                            </div>
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
                                <div class="text">{{ @$countries[4]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[4]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-shri-big.png">
                            </div>
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
                                <div class="text">{{ @$countries[3]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[3]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-turkey-big.png">
                            </div>
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
                                <div class="text">{{ @$countries[2]['desc'] }}</div>
                                <div class="text translate">{{ @$countries[2]['translate'] }}</div>
                            </div>
                            <div class="column-right"><img
                                        src="{{ asset(Config::get('site.theme_path')) }}/images/bg-tea-marokko-big.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="learn-more-popup-holder">
                    <div class="learn-more-popup-hack"></div>
                    <div class="learn-more-popup"><a href="javascript:void(0);" class="popup-close-cross"></a></div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop