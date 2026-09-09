<?php
/**
 * Plugin Name: Cremeni Store Bootstrap
 * Description: Mantém a estrutura institucional e comercial oficial da CREMENI de forma idempotente.
 * Version: 0.3.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const CREMENI_STORE_BOOTSTRAP_VERSION = '0.3.0';

function cremeni_store_bootstrap_page(string $title, string $slug, string $content = ''): int
{
    $existing = get_page_by_path($slug, OBJECT, 'page');
    if ($existing instanceof WP_Post) {
        return (int) $existing->ID;
    }

    $page_id = wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_content' => $content,
    ], true);

    return is_wp_error($page_id) ? 0 : (int) $page_id;
}

function cremeni_store_bootstrap_product_term(string $name, string $slug, string $description = '', int $parent = 0): int
{
    $existing = term_exists($slug, 'product_cat');
    if (is_array($existing)) {
        return (int) $existing['term_id'];
    }
    if (is_int($existing)) {
        return $existing;
    }

    $term = wp_insert_term($name, 'product_cat', [
        'slug'        => $slug,
        'description' => $description,
        'parent'      => $parent,
    ]);

    return is_wp_error($term) ? 0 : (int) $term['term_id'];
}

function cremeni_store_bootstrap_lower(string $value): string
{
    return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
}

function cremeni_store_bootstrap_woocommerce_pages(): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $pages = [
        'woocommerce_shop_page_id' => [
            'title'   => 'Loja',
            'slug'    => 'loja',
            'content' => '',
        ],
        'woocommerce_cart_page_id' => [
            'title'   => 'Carrinho',
            'slug'    => 'carrinho',
            'content' => '[woocommerce_cart]',
        ],
        'woocommerce_checkout_page_id' => [
            'title'   => 'Finalizar compra',
            'slug'    => 'finalizar-compra',
            'content' => '[woocommerce_checkout]',
        ],
        'woocommerce_myaccount_page_id' => [
            'title'   => 'Minha conta',
            'slug'    => 'minha-conta',
            'content' => '[woocommerce_my_account]',
        ],
    ];

    foreach ($pages as $option => $page) {
        $page_id = cremeni_store_bootstrap_page($page['title'], $page['slug'], $page['content']);
        if ($page_id > 0 && (int) get_option($option) !== $page_id) {
            update_option($option, $page_id);
        }
    }
}

function cremeni_store_run_bootstrap(): void
{
    if (get_option('cremeni_store_bootstrap_version') === CREMENI_STORE_BOOTSTRAP_VERSION) {
        return;
    }

    $home_id = cremeni_store_bootstrap_page(
        'Início',
        'inicio',
        '<!-- wp:paragraph --><p>CREMENI — vida ativa, bem-estar e conexão.</p><!-- /wp:paragraph -->'
    );

    cremeni_store_bootstrap_page('CREMENI Esporte', 'esporte');
    cremeni_store_bootstrap_page('CREMENI Pet Mimos', 'pet-mimos');
    cremeni_store_bootstrap_page('Guias CREMENI', 'guias-cremeni');
    cremeni_store_bootstrap_page('Modalidades', 'modalidades');
    cremeni_store_bootstrap_page('Sobre a CREMENI', 'sobre');
    cremeni_store_bootstrap_page('Atendimento', 'atendimento');
    cremeni_store_bootstrap_page('Política de Privacidade', 'politica-de-privacidade');
    cremeni_store_bootstrap_page('Política de Trocas e Devoluções', 'trocas-e-devolucoes');
    cremeni_store_bootstrap_page('Política de Entrega', 'politica-de-entrega');
    cremeni_store_bootstrap_page('Termos e Condições', 'termos-e-condicoes');

    if ($home_id > 0) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_id);
    }

    cremeni_store_bootstrap_woocommerce_pages();

    if (taxonomy_exists('product_cat')) {
        $sport_parent = cremeni_store_bootstrap_product_term(
            'CREMENI Esporte',
            'esporte',
            'Produtos selecionados para movimento, treino, prática esportiva e performance.'
        );

        cremeni_store_bootstrap_product_term(
            'CREMENI Pet Mimos',
            'pet-mimos',
            'Mimos leves e afetivos selecionados para complementar a jornada CREMENI.'
        );

        cremeni_store_bootstrap_product_term(
            'Guias CREMENI',
            'guias-cremeni',
            'Conteúdo próprio CREMENI para corpo, mente, rotina, convivência e vida ativa.'
        );

        if ($sport_parent > 0) {
            $sports = [
                ['Natação', 'natacao'],
                ['Futebol', 'futebol'],
                ['Futsal', 'futsal'],
                ['Beach Tennis', 'beach-tennis'],
                ['Vôlei', 'volei'],
                ['Badminton', 'badminton'],
                ['Lutas', 'lutas'],
                ['Treino funcional', 'funcional'],
                ['Yoga & Pilates', 'yoga-pilates'],
            ];

            foreach ($sports as [$name, $slug]) {
                cremeni_store_bootstrap_product_term(
                    $name,
                    $slug,
                    sprintf('Produtos CREMENI selecionados para %s.', cremeni_store_bootstrap_lower($name)),
                    $sport_parent
                );
            }
        }
    }

    update_option('cremeni_store_bootstrap_version', CREMENI_STORE_BOOTSTRAP_VERSION);
    flush_rewrite_rules(false);
}
add_action('init', 'cremeni_store_run_bootstrap', 30);
