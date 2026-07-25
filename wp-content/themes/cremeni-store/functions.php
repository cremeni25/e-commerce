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
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => __('Menu principal', 'cremeni-store'),
        'footer'  => __('Menu do rodapé', 'cremeni-store'),
    ]);
}
add_action('after_setup_theme', 'cremeni_store_setup');

function cremeni_store_assets(): void
{
    $theme = wp_get_theme();

    wp_enqueue_style(
        'cremeni-store',
        get_stylesheet_uri(),
        [],
        $theme->get('Version') ?: null
    );
}
add_action('wp_enqueue_scripts', 'cremeni_store_assets');
