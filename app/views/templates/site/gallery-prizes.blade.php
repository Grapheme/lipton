<?
/**
 * TITLE: Галерея призов
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

                <div class="prize-gallery">
                    <div class="prize-block third-place">
                        <div class="big-ico"></div>
                        <h3>Спецкурс: английский для туристов</h3>

                        <p>Введите один промо-код с упаковки чая Lipton Discovery и получите курс английского языка для
                            туристов от Lingualeo</p>
                    </div>
                    <div class="prize-block second-place">
                        <div class="big-ico"></div>
                        <h3>Спецкурс<br>на выбор</h3>

                        <p>Введите сразу два промо-кода с упаковки чая Lipton Discovery и получите один из спецкурсов
                            Lingualeo на выбор.</p>
                    </div>
                    <div class="prize-block first-place">
                        <div class="big-ico"></div>
                        <h3>Путешествие с National Geographic Traveller</h3>

                        <p>Главный приз — увлекательное путешествия в одну из шести стран, представленных вкусами Lipton
                            Discovery с National Geographic Traveller</p>
                    </div>
                    <a href="{{ pageurl('countries') }}" class="prize-link">Lipton Discovery Collection</a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop