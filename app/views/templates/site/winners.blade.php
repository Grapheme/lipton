<?
/**
* TITLE: Победители
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <div class="main-content">
        <div class="block winners">
            <div class="mosaic-holder data-autoplay="true"">
                <div style="background-image: url('../theme/build/images/winners-slider/1.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/2.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/3.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/4.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/5.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/6.jpg')" class="slide">&nbsp</div>
                <div style="background-image: url('../theme/build/images/winners-slider/7.jpg')" class="slide">&nbsp</div>
                <!-- <div class="mosaic-fuckup"></div> -->
            </div>
            <div class="content">
                <h2>{{ $page->seo->h1 }}</h2>

                <div class="block-plain">
                    <div class="illuminators">
                    @for($i = 1; $i <= 10; $i++)
                        <div class="illuminator">
                            <div class="number">{{ str_pad($i, 2, 0, STR_PAD_LEFT); }}</div>
                            <div style="background-image: url(http://placehold.it/100x100);" class="avatar"></div>
                            <div class="magnifier">
                                <div class="number">{{ str_pad($i, 2, 0, STR_PAD_LEFT); }}</div>
                                <div style="background-image: url(http://placehold.it/100x100);" class="avatar"></div>
                                <div class="name">Margaret Yashina</div>
                                <div class="info">28 лет, г. Москва</div>
                            </div>
                        </div>
                    @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop