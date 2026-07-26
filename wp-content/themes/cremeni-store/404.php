<?php
/**
 * Página não encontrada.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo" class="not-found-page">
    <section class="page-hero">
        <div class="cremeni-container not-found-page__content">
            <p class="eyebrow">404</p>
            <h1><?php esc_html_e('Esta página saiu da rota.', 'cremeni-store'); ?></h1>
            <p><?php esc_html_e('Use a busca para encontrar produtos ou retorne à loja.', 'cremeni-store'); ?></p>
            <?php get_search_form(); ?>
            <?php if (function_exists('wc_get_page_permalink')) : ?>
                <a class="button button--primary" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                    <?php esc_html_e('Ir para a loja', 'cremeni-store'); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
