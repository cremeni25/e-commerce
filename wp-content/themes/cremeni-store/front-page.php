<?php
/**
 * Página inicial da loja Cremeni.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo">
    <section class="hero">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('Nutrição esportiva prática', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Performance começa com escolhas inteligentes.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Produtos desenvolvidos para acompanhar treinos, competições e uma rotina ativa.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <?php if (function_exists('wc_get_page_permalink')) : ?>
                        <a class="button button--primary" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                            <?php esc_html_e('Conhecer produtos', 'cremeni-store'); ?>
                        </a>
                    <?php endif; ?>
                    <a class="button button--secondary" href="#diferenciais">
                        <?php esc_html_e('Por que Cremeni', 'cremeni-store'); ?>
                    </a>
                </div>
            </div>

            <div class="hero__visual" aria-hidden="true">
                <div class="hero__product-card">
                    <span><?php esc_html_e('Açaí Premium', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Whey Protein + Creatina', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section id="diferenciais" class="benefits">
        <div class="cremeni-container benefits__grid">
            <article class="benefit-card">
                <h2><?php esc_html_e('Praticidade', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Nutrição pronta para acompanhar sua rotina.', 'cremeni-store'); ?></p>
            </article>
            <article class="benefit-card">
                <h2><?php esc_html_e('Performance', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Ingredientes selecionados para quem busca evolução.', 'cremeni-store'); ?></p>
            </article>
            <article class="benefit-card">
                <h2><?php esc_html_e('Qualidade', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Produtos desenvolvidos com foco em segurança e confiança.', 'cremeni-store'); ?></p>
            </article>
        </div>
    </section>

    <?php if (class_exists('WooCommerce')) : ?>
        <section class="featured-products">
            <div class="cremeni-container">
                <div class="section-heading">
                    <p class="eyebrow"><?php esc_html_e('Loja Cremeni', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Produtos em destaque', 'cremeni-store'); ?></h2>
                </div>
                <?php echo do_shortcode('[products limit="4" columns="4" visibility="featured"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="brand-story">
        <div class="cremeni-container brand-story__content">
            <p class="eyebrow"><?php esc_html_e('CREMENI', 'cremeni-store'); ?></p>
            <h2><?php esc_html_e('Suplementação conectada ao esporte e à vida real.', 'cremeni-store'); ?></h2>
            <p><?php esc_html_e('Criamos soluções para atletas e pessoas ativas que valorizam sabor, conveniência e desempenho.', 'cremeni-store'); ?></p>
        </div>
    </section>
</main>
<?php
get_footer();
