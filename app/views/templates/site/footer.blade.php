<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<div class="sub-footer">
    <div class="copyright">
        <p>ООО «ЮНИЛЕВЕР РУСЬ». ЮРИДИЧЕСКИЙ И ПОЧТОВЫЙ АДРЕС: 123022, Г. МОСКВА, УЛ. СЕРГЕЯ МАКЕЕВА, Д. 13. </p>

        <p>ЭЛЕКТРОННАЯ ПОЧТА: <a href="mailto:COMMUNICATIONS.RUSSIA@UNILEVER.COM">COMMUNICATIONS.RUSSIA@UNILEVER.COM</a>
        </p>
    </div>
    <div class="sharing-links">
        <span> Рассказать друзьям
            <a href="https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl={{ URL::to(Request::path()) }}" class="soc ok"></a>
            <a href="https://vk.com/share.php?url={{ URL::to(Request::path()) }}" class="soc vk"></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ URL::to(Request::path()) }}" class="soc fb"></a>
        </span>
    </div>
    {{ Menu::placement('footer_menu') }}
</div>
<div class="footer-decor-left"></div>
<div class="footer-decor-right"></div>
