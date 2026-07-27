<?php
/**
 * Página inicial da loja Cremeni Store.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

$categories = cremeni_store_product_categories();
$sports = cremeni_store_sports();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/loja/');

get_header();
?>
<main id="conteudo">
    <section class="hero">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('CREMENI STORE • PERFORMANCE E MOVIMENTO', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Tudo para a sua melhor versão.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Um e-commerce multimarcas com suplementos, produtos fitness, roupas, acessórios e equipamentos selecionados para diferentes esportes e objetivos.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <a class="button button--primary" href="<?php echo esc_url($shop_url); ?>">
                        <?php esc_html_e('Ver produtos', 'cremeni-store'); ?>
                    </a>
                    <a class="button button--secondary" href="#esportes">
                        <?php esc_html_e('Escolher por esporte', 'cremeni-store'); ?>
                    </a>
                </div>
                <ul class="hero__proof" aria-label="<?php esc_attr_e('Diferenciais da loja', 'cremeni-store'); ?>">
                    <li><?php esc_html_e('Produtos originais', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Estoque de parceiros', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Entrega nacional', 'cremeni-store'); ?></li>
                </ul>
            </div>
            <div class="hero__visual" aria-hidden="true">
                <div class="hero__halo"></div>
                <img
                    class="hero__watermark"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cremeni-store-mark.svg'); ?>"
                    alt=""
                    width="520"
                    height="455"
                    loading="eager"
                    decoding="async"
                >
                <div class="hero__product-card">
                    <span><?php esc_html_e('SUPERE • EVOLUA • CONQUISTE', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Performance para todos os níveis.', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <div class="cremeni-container trust-strip__grid">
            <div><strong><?php esc_html_e('Parceiros confiáveis', 'cremeni-store'); ?></strong><span><?php esc_html_e('Operação com estoques de terceiros selecionados.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Variedade em um só lugar', 'cremeni-store'); ?></strong><span><?php esc_html_e('Mix de produtos para diferentes objetivos e modalidades.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Pagamento seguro', 'cremeni-store'); ?></strong><span><?php esc_html_e('Estrutura preparada para checkout e meios de pagamento.', 'cremeni-store'); ?></span></div>
        </div>
    </section>

    <section id="categorias" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading section-heading--split">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Compre por categoria', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Encontre o que precisa para treinar e evoluir.', 'cremeni-store'); ?></h2>
                </div>
                <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ver catálogo completo', 'cremeni-store'); ?></a>
            </div>

            <div class="store-categories__grid">
                <?php $index = 1; ?>
                <?php foreach ($categories as $slug => $category) : ?>
                    <?php
                    $category_url = function_exists('get_term_link') ? get_term_link($slug, 'product_cat') : $shop_url;
                    if (is_wp_error($category_url)) {
                        $category_url = $shop_url;
                    }
                    ?>
                    <a class="category-card <?php echo 'infoprodutos' === $slug ? 'category-card--future' : ''; ?>" href="<?php echo esc_url($category_url); ?>">
                        <span class="category-card__index"><?php echo esc_html(str_pad((string) $index, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3><?php echo esc_html($category['label']); ?></h3>
                        <p><?php echo esc_html($category['description']); ?></p>
                        <span class="category-card__action"><?php echo 'infoprodutos' === $slug ? esc_html__('Em breve', 'cremeni-store') : esc_html__('Ver produtos', 'cremeni-store'); ?></span>
                    </a>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="esportes" class="sports-section">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('Segmentação por modalidades', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Compre de acordo com o seu esporte.', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('A identidade da Cremeni Store permanece fixa; campanhas, vitrines e recomendações poderão mudar conforme cada modalidade.', 'cremeni-store'); ?></p>
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
                <p class="eyebrow"><?php esc_html_e('CREMENI STORE', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Uma plataforma digital para o ecossistema fitness.', 'cremeni-store'); ?></h2>
            </div>
            <div>
                <p><?php esc_html_e('A operação foi concebida como marketplace e loja multimarcas, utilizando estoques de parceiros e fornecedores. A evolução futura prevê infoprodutos, conteúdos e programas digitais integrados ao mesmo posicionamento.', 'cremeni-store'); ?></p>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();