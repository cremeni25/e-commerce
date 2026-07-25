<?php
/**
 * Rodapé global do tema.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<footer class="site-footer">
    <div class="cremeni-container site-footer__grid">
        <section>
            <h2 class="site-footer__title">CREMENI STORE</h2>
            <p><?php esc_html_e('E-commerce multimarcas para suplementos, produtos fitness, roupas, acessórios e equipamentos. Operação com estoques e entregas realizadas por parceiros selecionados.', 'cremeni-store'); ?></p>
        </section>

        <section>
            <h2 class="site-footer__title"><?php esc_html_e('Atendimento', 'cremeni-store'); ?></h2>
            <p><?php esc_html_e('Atendimento digital para todo o Brasil.', 'cremeni-store'); ?></p>
            <p><?php esc_html_e('São Caetano do Sul — SP', 'cremeni-store'); ?></p>
        </section>

        <section>
            <h2 class="site-footer__title"><?php esc_html_e('Navegação', 'cremeni-store'); ?></h2>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'site-footer__menu',
                'fallback_cb'    => false,
            ]);
            ?>
        </section>
    </div>

    <div class="cremeni-container site-footer__bottom">
        <p>&copy; <?php echo esc_html((string) gmdate('Y')); ?> CREMENI STORE. <?php esc_html_e('Todos os direitos reservados.', 'cremeni-store'); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
