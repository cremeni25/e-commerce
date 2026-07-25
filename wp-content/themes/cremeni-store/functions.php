<?php
/**
 * Configuração principal do tema Cremeni Store.
 *
 * @package CremeniStore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_store_setup(): void
{
    load_theme_textdomain('cremeni-store', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 420,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary'    => __('Menu principal', 'cremeni-store'),
        'categories' => __('Menu de categorias', 'cremeni-store'),
        'sports'     => __('Menu por esportes', 'cremeni-store'),
        'footer'     => __('Menu do rodapé', 'cremeni-store'),
    ]);
}
add_action('after_setup_theme', 'cremeni_store_setup');

function cremeni_store_assets(): void
{
    $theme = wp_get_theme();
    $version = $theme->get('Version') ?: '0.1.0';

    wp_enqueue_style(
        'cremeni-store',
        get_stylesheet_uri(),
        [],
        $version
    );

    wp_enqueue_script(
        'cremeni-store-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        [],
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'cremeni_store_assets');

function cremeni_store_product_categories(): array
{
    return [
        'suplementos' => [
            'label'       => __('Suplementos', 'cremeni-store'),
            'description' => __('Proteínas, creatina, pré-treinos, vitaminas e recuperação.', 'cremeni-store'),
        ],
        'alimentos-fitness' => [
            'label'       => __('Alimentos fitness', 'cremeni-store'),
            'description' => __('Produtos industrializados, embalados e enviados por parceiros.', 'cremeni-store'),
        ],
        'roupas' => [
            'label'       => __('Roupas', 'cremeni-store'),
            'description' => __('Vestuário esportivo para treino, competição e rotina ativa.', 'cremeni-store'),
        ],
        'equipamentos' => [
            'label'       => __('Equipamentos', 'cremeni-store'),
            'description' => __('Itens para treino em casa, academia e modalidades específicas.', 'cremeni-store'),
        ],
        'acessorios' => [
            'label'       => __('Acessórios', 'cremeni-store'),
            'description' => __('Complementos para prática esportiva, transporte e organização.', 'cremeni-store'),
        ],
        'infoprodutos' => [
            'label'       => __('Infoprodutos', 'cremeni-store'),
            'description' => __('Cursos, programas, guias e conteúdos digitais em preparação.', 'cremeni-store'),
        ],
    ];
}

function cremeni_store_sports(): array
{
    return [
        'natacao'    => __('Natação', 'cremeni-store'),
        'corrida'    => __('Corrida', 'cremeni-store'),
        'ciclismo'   => __('Ciclismo', 'cremeni-store'),
        'musculacao' => __('Musculação', 'cremeni-store'),
        'crossfit'   => __('Cross training', 'cremeni-store'),
        'lutas'      => __('Lutas', 'cremeni-store'),
        'futebol'    => __('Futebol', 'cremeni-store'),
        'volei'      => __('Vôlei', 'cremeni-store'),
    ];
}

function cremeni_store_cart_count_fragment(array $fragments): array
{
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    ob_start();
    ?>
    <span class="header-action__count"><?php echo esc_html((string) WC()->cart->get_cart_contents_count()); ?></span>
    <?php
    $fragments['span.header-action__count'] = (string) ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'cremeni_store_cart_count_fragment');

function cremeni_store_woocommerce_loop_columns(): int
{
    return 4;
}
add_filter('loop_shop_columns', 'cremeni_store_woocommerce_loop_columns');

function cremeni_store_woocommerce_products_per_page(): int
{
    return 12;
}
add_filter('loop_shop_per_page', 'cremeni_store_woocommerce_products_per_page');
