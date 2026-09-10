<?php
/**
 * Plugin Name: CREMENI Health
 * Description: Diagnóstico mínimo e não sensível da camada comercial CREMENI.
 * Version: 1.0.0
 * Author: CREMENI
 */

if (! defined('ABSPATH')) { exit; }

add_action('template_redirect', function (): void {
    if (! isset($_GET['cremeni_health']) || '1' !== (string) $_GET['cremeni_health']) {
        return;
    }

    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');

    $published = 0;
    if (post_type_exists('product')) {
        $counts = wp_count_posts('product');
        $published = isset($counts->publish) ? (int) $counts->publish : 0;
    }

    echo wp_json_encode([
        'cremeni_health' => 'ok',
        'health_version' => '1.0.0',
        'woocommerce_class' => class_exists('WooCommerce'),
        'product_post_type' => post_type_exists('product'),
        'product_category_taxonomy' => taxonomy_exists('product_cat'),
        'catalog_pilot_loaded' => function_exists('cremeni_catalog_pilot_products'),
        'catalog_reconciler_loaded' => function_exists('cremeni_catalog_reconciler_run'),
        'published_products' => $published,
        'reconciler_last_run' => get_option('cremeni_catalog_reconciler_last_run', null),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}, 0);
