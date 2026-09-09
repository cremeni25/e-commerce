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
    return $theme->get('Version') ?: '0.5.0';
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

/**
 * CSS crítico final para impedir que estilos adicionais/plugins comprimam o layout desktop.
 * É impresso depois do CSS customizado do WordPress para preservar a largura real da loja.
 */
function cremeni_store_layout_guard(): void
{
    ?>
    <style id="cremeni-layout-guard">
        html,
        body {
            width: 100% !important;
            min-width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            display: block !important;
            background: #0d0d0d !important;
            overflow-x: hidden !important;
            transform: none !important;
            zoom: 1 !important;
        }

        .site-header,
        #conteudo,
        .site-footer {
            width: 100% !important;
            max-width: none !important;
            margin-inline: 0 !important;
        }

        .cremeni-container {
            width: min(calc(100% - 48px), 1440px) !important;
            max-width: 1440px !important;
            min-width: 0 !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .hero__grid {
            grid-template-columns: minmax(0, 1.08fr) minmax(360px, .92fr) !important;
        }

        .hero h1 {
            font-size: clamp(3.6rem, 5.4vw, 6.2rem) !important;
        }

        @media (max-width: 900px) {
            .cremeni-container {
                width: min(calc(100% - 32px), 100%) !important;
            }

            .hero__grid {
                grid-template-columns: 1fr !important;
            }

            .hero h1 {
                font-size: clamp(2.8rem, 11vw, 5rem) !important;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'cremeni_store_layout_guard', 999);

function cremeni_store_brand_icons(): void
{
    $markUrl = get_template_directory_uri() . '/assets/images/cremeni-store-mark.svg';
    echo '<link rel="icon" href="' . esc_url($markUrl) . '" type="image/svg+xml">' . "\n";
    echo '<link rel="mask-icon" href="' . esc_url($markUrl) . '" color="#a8ff00">' . "\n";
}
add_action('wp_head', 'cremeni_store_brand_icons', 2);
add_action('admin_head', 'cremeni_store_brand_icons', 2);

/**
 * Frentes comerciais oficiais da CREMENI.
 *
 * ESPORTE: compra principal, margem unitária protegida.
 * PET MIMOS: cross-sell, afeto e recorrência com baixa fricção.
 * GUIAS CREMENI: conteúdo proprietário para relacionamento e retenção.
 */
function cremeni_store_product_categories(): array
{
    return [
        'esporte' => [
            'label'       => __('Esporte', 'cremeni-store'),
            'description' => __('Produtos selecionados para prática esportiva, treino, mobilidade e vida ativa.', 'cremeni-store'),
        ],
        'pet-mimos' => [
            'label'       => __('Pet Mimos', 'cremeni-store'),
            'description' => __('Pequenos mimos, brinquedos e acessórios leves para companhia, passeio e vínculo.', 'cremeni-store'),
        ],
        'guias-cremeni' => [
            'label'       => __('Guias Cremeni', 'cremeni-store'),
            'description' => __('Conteúdos curtos sobre corpo, mente, rotina, bem-estar e convivência com pets.', 'cremeni-store'),
        ],
    ];
}

function cremeni_store_sports(): array
{
    return [
        'natacao'       => __('Natação', 'cremeni-store'),
        'futebol'       => __('Futebol', 'cremeni-store'),
        'futsal'        => __('Futsal', 'cremeni-store'),
        'beach-tennis'  => __('Beach Tennis', 'cremeni-store'),
        'volei'         => __('Vôlei', 'cremeni-store'),
        'badminton'     => __('Badminton', 'cremeni-store'),
        'lutas'         => __('Lutas', 'cremeni-store'),
        'funcional'     => __('Treino funcional', 'cremeni-store'),
        'yoga-pilates'  => __('Yoga & Pilates', 'cremeni-store'),
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
    echo '<p class="cremeni-account-intro">' . esc_html__('Acompanhe pedidos, endereços, downloads e dados da sua conta Cremeni.', 'cremeni-store') . '</p>';
}
add_action('woocommerce_account_dashboard', 'cremeni_store_account_intro', 5);
