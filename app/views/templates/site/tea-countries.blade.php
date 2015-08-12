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
                                <div class="logo">111</div>
                                <div class="text">111</div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-cuba-big.png"></div>
                        </div>
                    </div>
                    <div class="country england">
                        <p>Англия</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content england">
                            <div class="column-left">
                                <div class="logo"></div>
                                <div class="text">222</div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-england-big.png"></div>
                        </div>
                    </div>
                    <div class="country china">
                        <p>Китай</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content china">
                            <div class="column-left">
                                <div class="logo">333</div>
                                <div class="text">333</div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-china-big.png"></div>
                        </div>
                    </div>
                    <div class="country shri">
                        <p>Шри-ланка</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content shri">
                            <div class="column-left">
                                <div class="logo">444</div>
                                <div class="text">444</div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-shri-big.png"></div>
                        </div>
                    </div>
                    <div class="country turkey">
                        <p>Турция</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content turkey">
                            <div class="column-left">
                                <div class="logo">555</div>
                                <div class="text">555</div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-turkey-big.png"></div>
                        </div>
                    </div>
                    <div class="country marokko">
                        <p>Марокко</p>

                        <div class="photo"></div>
                        <div class="tea"></div>
                        <div class="content marokko">
                            <div class="column-left">
                                <div class="logo"></div>
                                <div class="text">Пряный чёрный чай со вкусом мяты и мандарина<br>и слегка горьковатым
                                    послевкусием имбиря<br>откроет вам пленительный мир Марокко,<br>полный контрастов и
                                    разных культур.
                                </div>
                            </div>
                            <div class="column-right"><img src="../images/bg-tea-marokko-big.png"></div>
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