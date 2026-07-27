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

function cremeni_store_asset_version(string $relativePath): string
{
    $absolutePath = get_template_directory() . $relativePath;

    if (is_file($absolutePath)) {
        return (string) filemtime($absolutePath);
    }

    $theme = wp_get_theme();
    return $theme->get('Version') ?: '0.4.0';
}

function cremeni_store_assets(): void
{
    wp_enqueue_style(
        'cremeni-store',
        get_stylesheet_uri(),
        [],
        cremeni_store_asset_version('/style.css')
    );

    wp_enqueue_style(
        'cremeni-store-components',
        get_template_directory_uri() . '/assets/css/components.css',
        ['cremeni-store'],
        cremeni_store_asset_version('/assets/css/components.css')
    );

    wp_enqueue_script(
        'cremeni-store-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        [],
        cremeni_store_asset_version('/assets/js/navigation.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'cremeni_store_assets');

function cremeni_store_brand_icons(): void
{
    $markUrl = get_template_directory_uri() . '/assets/images/cremeni-store-mark.svg';
    echo '<link rel="icon" href="' . esc_url($markUrl) . '" type="image/svg+xml">' . "\n";
    echo '<link rel="mask-icon" href="' . esc_url($markUrl) . '" color="#a8ff00">' . "\n";
}
add_action('wp_head', 'cremeni_store_brand_icons', 2);
add_action('admin_head', 'cremeni_store_brand_icons', 2);

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

function cremeni_store_body_classes(array $classes): array
{
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        $classes[] = 'cremeni-commerce';
    }

    return $classes;
}
add_filter('body_class', 'cremeni_store_body_classes');

function cremeni_store_account_intro(): void
{
    echo '<p class="cremeni-account-intro">' . esc_html__('Acompanhe pedidos, endereços, downloads e dados da sua conta Cremeni Store.', 'cremeni-store') . '</p>';
}
add_action('woocommerce_account_dashboard', 'cremeni_store_account_intro', 5);
