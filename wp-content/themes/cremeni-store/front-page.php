<?php
/**
 * Página inicial da loja Cremeni.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

$categories = cremeni_store_product_categories();
$sports = cremeni_store_sports();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/loja/');
$guides_url = post_type_exists('cremeni_guide') ? get_post_type_archive_link('cremeni_guide') : home_url('/guias/');

get_header();
?>
<main id="conteudo">
    <section class="hero">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('CREMENI • VIDA ATIVA, BEM-ESTAR E CONEXÃO', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Movimento para você. Cuidado para quem faz parte da sua vida.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Produtos esportivos selecionados, mimos para pets e Guias Cremeni que conectam corpo, mente, rotina e companhia.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <a class="button button--primary" href="<?php echo esc_url($shop_url); ?>">
                        <?php esc_html_e('Explorar produtos', 'cremeni-store'); ?>
                    </a>
                    <a class="button button--secondary" href="#universos-cremeni">
                        <?php esc_html_e('Conhecer a Cremeni', 'cremeni-store'); ?>
                    </a>
                </div>
                <ul class="hero__proof" aria-label="<?php esc_attr_e('Diferenciais da loja', 'cremeni-store'); ?>">
                    <li><?php esc_html_e('Seleção criteriosa', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Dropshipping nacional', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Conteúdo próprio Cremeni', 'cremeni-store'); ?></li>
                </ul>
            </div>
            <div class="hero__visual" aria-hidden="true">
                <div class="hero__halo"></div>
                <img
                    class="hero__watermark"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cremeni-store-logo.svg'); ?>"
                    alt=""
                    width="1090"
                    height="246"
                    loading="eager"
                    decoding="async"
                >
                <div class="hero__product-card">
                    <span><?php esc_html_e('CORPO • MENTE • PET', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Uma relação que continua depois da compra.', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <div class="cremeni-container trust-strip__grid">
            <div><strong><?php esc_html_e('Esporte selecionado', 'cremeni-store'); ?></strong><span><?php esc_html_e('Produtos com função, mercado e condição comercial validados antes da publicação.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Pet Mimos', 'cremeni-store'); ?></strong><span><?php esc_html_e('Itens leves e afetivos pensados para complementar a compra sem pesar no carrinho.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Guias Cremeni', 'cremeni-store'); ?></strong><span><?php esc_html_e('Conteúdo curto e útil para apoiar hábitos, bem-estar, rotina e convivência.', 'cremeni-store'); ?></span></div>
        </div>
    </section>

    <section id="universos-cremeni" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading section-heading--split">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Universos Cremeni', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Três frentes com funções diferentes dentro da mesma jornada.', 'cremeni-store'); ?></h2>
                </div>
                <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ver catálogo', 'cremeni-store'); ?></a>
            </div>

            <div class="store-categories__grid">
                <?php $index = 1; ?>
                <?php foreach ($categories as $slug => $category) : ?>
                    <?php
                    if ($slug === 'guias-cremeni' && is_string($guides_url) && $guides_url !== '') {
                        $category_url = $guides_url;
                    } else {
                        $category_url = function_exists('get_term_link') ? get_term_link($slug, 'product_cat') : $shop_url;
                        if (is_wp_error($category_url)) {
                            $category_url = $shop_url;
                        }
                    }
                    ?>
                    <a class="category-card" href="<?php echo esc_url($category_url); ?>">
                        <span class="category-card__index"><?php echo esc_html(str_pad((string) $index, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3><?php echo esc_html($category['label']); ?></h3>
                        <p><?php echo esc_html($category['description']); ?></p>
                        <span class="category-card__action"><?php esc_html_e('Conhecer', 'cremeni-store'); ?></span>
                    </a>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="esportes" class="sports-section">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('CREMENI ESPORTE', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Escolha pela modalidade que faz parte da sua rotina.', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('O catálogo esportivo será enxuto e evolutivo: produtos entram somente quando houver condição de dropshipping, competitividade e função comercial comprovadas.', 'cremeni-store'); ?></p>
            </div>
            <div class="sports-grid">
                <?php foreach ($sports as $slug => $sport) : ?>
                    <a class="sport-card" href="<?php echo esc_url(add_query_arg('esporte', $slug, $shop_url)); ?>">
                        <span class="sport-card__mark"><?php echo esc_html(mb_strtoupper(mb_substr($sport, 0, 1))); ?></span>
                        <strong><?php echo esc_html($sport); ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (class_exists('WooCommerce')) : ?>
        <section class="featured-products">
            <div class="cremeni-container">
                <div class="section-heading section-heading--split">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Seleção Cremeni', 'cremeni-store'); ?></p>
                        <h2><?php esc_html_e('Produtos em destaque', 'cremeni-store'); ?></h2>
                    </div>
                    <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ir para a loja', 'cremeni-store'); ?></a>
                </div>
                <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="brand-story">
        <div class="cremeni-container brand-story__grid">
            <div>
                <p class="eyebrow"><?php esc_html_e('GUIAS CREMENI', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('A compra pode terminar. A relação não precisa terminar.', 'cremeni-store'); ?></h2>
            </div>
            <div>
                <p><?php esc_html_e('Os Guias Cremeni são conteúdos proprietários, curtos e práticos sobre corpo, mente, rotina, vida ativa e convivência com pets. Eles podem acompanhar produtos, apoiar recompra e criar novos motivos para o cliente retornar à Cremeni.', 'cremeni-store'); ?></p>
                <?php if (is_string($guides_url) && $guides_url !== '') : ?><p><a class="text-link" href="<?php echo esc_url($guides_url); ?>"><?php esc_html_e('Explorar Guias Cremeni', 'cremeni-store'); ?></a></p><?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
