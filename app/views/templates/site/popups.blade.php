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
@endif