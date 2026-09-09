<?php
/**
 * Página inicial CREMENI.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

$categories = cremeni_store_product_categories();
$sports = cremeni_store_sports();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/loja/');
$cremeni_official_logo = require get_template_directory() . '/assets/cremeni-official-logo-data.php';

get_header();
?>
<main id="conteudo">
    <section class="hero hero--brand-first">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('CREMENI • VIDA ATIVA • BEM-ESTAR • CONEXÃO', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Movimento para você. Cuidado para quem faz parte da sua vida.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Produtos esportivos selecionados, mimos para pets e conteúdo próprio para transformar compra em relacionamento.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <a class="button button--primary" href="#universos-cremeni"><?php esc_html_e('Conhecer a CREMENI', 'cremeni-store'); ?></a>
                    <a class="button button--secondary" href="#esportes"><?php esc_html_e('Explorar esportes', 'cremeni-store'); ?></a>
                </div>
                <ul class="hero__proof" aria-label="<?php esc_attr_e('Diferenciais CREMENI', 'cremeni-store'); ?>">
                    <li><?php esc_html_e('Seleção criteriosa', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Dropshipping nacional', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Conteúdo próprio CREMENI', 'cremeni-store'); ?></li>
                </ul>
            </div>
            <div class="hero__visual" aria-hidden="true">
                <div class="hero__halo"></div>
                <img class="hero__official-wordmark" src="<?php echo esc_attr($cremeni_official_logo); ?>" alt="" width="1200" height="335" loading="eager" decoding="async">
                <div class="hero__product-card">
                    <span><?php esc_html_e('CORPO • MENTE • PET', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Uma relação que continua depois da compra.', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <div class="cremeni-container trust-strip__grid">
            <div><strong><?php esc_html_e('CREMENI Esporte', 'cremeni-store'); ?></strong><span><?php esc_html_e('Produtos com função, mercado e condição comercial validados antes da publicação.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('PET Mimos', 'cremeni-store'); ?></strong><span><?php esc_html_e('Itens leves e afetivos para complementar a jornada sem transformar a CREMENI em pet shop.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Guias CREMENI', 'cremeni-store'); ?></strong><span><?php esc_html_e('Conteúdo prático sobre corpo, mente, rotina, convivência e vida ativa.', 'cremeni-store'); ?></span></div>
        </div>
    </section>

    <section id="universos-cremeni" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading section-heading--split">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Universos CREMENI', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Três frentes. Uma única experiência de marca.', 'cremeni-store'); ?></h2>
                </div>
                <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ver catálogo', 'cremeni-store'); ?></a>
            </div>
            <div class="store-categories__grid">
                <?php $index = 1; ?>
                <?php foreach ($categories as $slug => $category) : ?>
                    <?php
                    if ('guias-cremeni' === $slug) {
                        $category_url = home_url('/guias-cremeni/');
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
                <p><?php esc_html_e('O catálogo será enxuto e evolutivo: produtos entram somente quando houver dropshipping confirmado, competitividade e função comercial comprovadas.', 'cremeni-store'); ?></p>
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
                        <p class="eyebrow"><?php esc_html_e('Seleção CREMENI', 'cremeni-store'); ?></p>
                        <h2><?php esc_html_e('Produtos aprovados para entrar na sua jornada.', 'cremeni-store'); ?></h2>
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
                <p><?php esc_html_e('Os Guias CREMENI conectam produtos, hábitos, bem-estar e convivência. São conteúdos próprios, curtos e úteis para criar novos motivos para o cliente voltar à marca mesmo quando não estiver comprando.', 'cremeni-store'); ?></p>
                <a class="text-link" href="<?php echo esc_url(home_url('/guias-cremeni/')); ?>"><?php esc_html_e('Conhecer os Guias CREMENI', 'cremeni-store'); ?></a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
