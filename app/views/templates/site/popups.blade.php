<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@if(Auth::check())
<div class="block second-code">
    <div class="second-code-hack"></div>
    @include(Helper::layout('forms.second-promo-code'))
</div>
<div class="block story">
    <div class="second-code-hack"></div>
    <div class="story-popup"><a href="#" class="popup-close-cross"></a>
        <h2>Мой рассказ</h2>
        <div class="story-wrapper">
            <div class="story-text">
               СОЧИНЕНИЕ
            </div>
        </div>
    </div>
</div>
@endif