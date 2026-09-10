<?php
/** Rodapé global do tema. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
?>
<footer class="site-footer">
    <div class="cremeni-container site-footer__grid">
        <section class="site-footer__brand">
            <?php require get_template_directory() . '/assets/cremeni-wordmark.php'; ?>
            <p class="site-footer__tagline"><?php esc_html_e('bom a qualquer hora', 'cremeni-store'); ?></p>
            <p><?php esc_html_e('Esporte, Pet Mimos e Guias CREMENI reunidos em uma experiência de compra, cuidado e conteúdo para uma vida mais ativa.', 'cremeni-store'); ?></p>
        </section>

        <section>
            <h2 class="site-footer__title"><?php esc_html_e('Atendimento', 'cremeni-store'); ?></h2>
            <p><?php esc_html_e('Atendimento digital para todo o Brasil.', 'cremeni-store'); ?></p>
            <p><?php esc_html_e('São Caetano do Sul — SP', 'cremeni-store'); ?></p>
            <a href="<?php echo esc_url(cremeni_store_page_url('atendimento')); ?>"><?php esc_html_e('Falar com a CREMENI →', 'cremeni-store'); ?></a>
        </section>

        <section>
            <h2 class="site-footer__title"><?php esc_html_e('Navegue', 'cremeni-store'); ?></h2>
            <?php if (has_nav_menu('footer')) { wp_nav_menu(['theme_location'=>'footer','container'=>false,'menu_class'=>'site-footer__menu','fallback_cb'=>false]); } else { cremeni_store_render_fallback_menu('site-footer__menu'); } ?>
        </section>
    </div>

    <div class="cremeni-container site-footer__bottom">
        <p>&copy; <?php echo esc_html((string) gmdate('Y')); ?> CREMENI. <?php esc_html_e('Todos os direitos reservados.', 'cremeni-store'); ?></p>
        <span><?php esc_html_e('Curadoria antes de volume.', 'cremeni-store'); ?></span>
    </div>
</footer>
<?php
try { wp_footer(); } catch (Throwable $error) {
    try { update_option('cremeni_runtime_last_fatal',['time'=>gmdate('c'),'message'=>sanitize_text_field($error->getMessage()),'file'=>basename($error->getFile()),'line'=>(int)$error->getLine()],false); }
    catch (Throwable $ignored) { error_log('CREMENI wp_footer failure: '.$error->getMessage()); }
}
?>
</body>
</html>
