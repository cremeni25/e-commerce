<?php
/**
 * Plugin Name: CREMENI Catalog Reconciler
 * Description: Garante de forma idempotente a existência e integridade do catálogo piloto CREMENI.
 * Version: 1.0.0
 * Author: CREMENI
 */

declare(strict_types=1);

if (! defined('ABSPATH')) { exit; }

function cremeni_catalog_reconciler_ensure_term(string $slug, string $name, int $parent = 0): int {
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term instanceof WP_Term) {
        return (int) $term->term_id;
    }

    $created = wp_insert_term($name, 'product_cat', ['slug' => $slug, 'parent' => $parent]);
    if (is_wp_error($created)) {
        return 0;
    }

    return (int) $created['term_id'];
}

function cremeni_catalog_reconciler_terms(): array {
    $esporte = cremeni_catalog_reconciler_ensure_term('esporte', 'CREMENI Esporte');

    return [
        'esporte'      => $esporte,
        'natacao'      => cremeni_catalog_reconciler_ensure_term('natacao', 'Natação', $esporte),
        'futebol'      => cremeni_catalog_reconciler_ensure_term('futebol', 'Futebol', $esporte),
        'futsal'       => cremeni_catalog_reconciler_ensure_term('futsal', 'Futsal', $esporte),
        'beach-tennis' => cremeni_catalog_reconciler_ensure_term('beach-tennis', 'Beach Tennis', $esporte),
        'funcional'    => cremeni_catalog_reconciler_ensure_term('funcional', 'Treino funcional', $esporte),
        'yoga-pilates' => cremeni_catalog_reconciler_ensure_term('yoga-pilates', 'Yoga & Pilates', $esporte),
    ];
}

function cremeni_catalog_reconciler_find_product(string $slug, string $supplier_sku): int {
    $existing = get_page_by_path($slug, OBJECT, 'product');
    if ($existing instanceof WP_Post) {
        return (int) $existing->ID;
    }

    $by_sku = get_posts([
        'post_type'      => 'product',
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_cremeni_supplier_sku',
        'meta_value'     => $supplier_sku,
    ]);

    return $by_sku ? (int) $by_sku[0] : 0;
}

function cremeni_catalog_reconciler_run(): void {
    if (! class_exists('WooCommerce') || ! post_type_exists('product') || ! taxonomy_exists('product_cat')) {
        return;
    }

    if (! function_exists('cremeni_catalog_pilot_products')) {
        return;
    }

    $known_terms = cremeni_catalog_reconciler_terms();
    $success = 0;

    foreach (cremeni_catalog_pilot_products() as $slug => $data) {
        $supplier_sku = (string) ($data['supplier_sku'] ?? '');
        $product_id = cremeni_catalog_reconciler_find_product($slug, $supplier_sku);

        $post_data = [
            'post_type'    => 'product',
            'post_status'  => 'publish',
            'post_title'   => (string) $data['name'],
            'post_name'    => $slug,
            'post_excerpt' => (string) $data['short'],
            'post_content' => (string) $data['description'],
        ];

        if ($product_id > 0) {
            $post_data['ID'] = $product_id;
            $result = wp_update_post($post_data, true);
        } else {
            $result = wp_insert_post($post_data, true);
        }

        if (is_wp_error($result) || ! $result) {
            continue;
        }

        $product_id = (int) $result;
        $term_ids = [];
        foreach ((array) $data['terms'] as $term_slug) {
            if (isset($known_terms[$term_slug]) && $known_terms[$term_slug] > 0) {
                $term_ids[] = $known_terms[$term_slug];
            }
        }
        if ($term_ids) {
            wp_set_object_terms($product_id, $term_ids, 'product_cat', false);
        }
        wp_set_object_terms($product_id, 'simple', 'product_type', false);

        update_post_meta($product_id, '_sku', 'CRE-' . $supplier_sku);
        update_post_meta($product_id, '_cremeni_supplier', 'Dinka');
        update_post_meta($product_id, '_cremeni_supplier_sku', $supplier_sku);
        update_post_meta($product_id, '_cremeni_supplier_cost', (string) $data['supplier_cost']);
        update_post_meta($product_id, '_cremeni_supplier_url', esc_url_raw((string) $data['supplier_url']));
        update_post_meta($product_id, '_cremeni_drop_confirmed', 'yes');
        update_post_meta($product_id, '_cremeni_last_validation', '2026-09-09');
        update_post_meta($product_id, '_cremeni_sales_enabled', 'no');
        update_post_meta($product_id, '_cremeni_commercial_status', 'pre_operacional');
        update_post_meta($product_id, '_cremeni_image_status', 'aguardando_autorizacao');
        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, '_manage_stock', 'no');
        update_post_meta($product_id, '_cremeni_public_facts', wp_json_encode($data['facts'], JSON_UNESCAPED_UNICODE));
        update_post_meta($product_id, '_cremeni_public_specs', wp_json_encode($data['specs'], JSON_UNESCAPED_UNICODE));

        delete_post_meta($product_id, '_regular_price');
        delete_post_meta($product_id, '_sale_price');
        delete_post_meta($product_id, '_price');

        clean_post_cache($product_id);
        $success++;
    }

    update_option('cremeni_catalog_reconciler_last_run', [
        'time' => gmdate('c'),
        'products_reconciled' => $success,
    ], false);
}
add_action('init', 'cremeni_catalog_reconciler_run', 95);
