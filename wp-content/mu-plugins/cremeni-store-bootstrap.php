<?php
/**
 * Plugin Name: Cremeni Store Bootstrap
 * Description: Cria a estrutura inicial da loja, páginas institucionais e categorias WooCommerce de forma idempotente.
 * Version: 0.2.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const CREMENI_STORE_BOOTSTRAP_VERSION = '0.2.0';

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

function cremeni_store_bootstrap_product_term(
    string $name,
    string $slug,
    string $description = '',
    int $parent = 0
): int {
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

function cremeni_store_run_bootstrap(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    if (get_option('cremeni_store_bootstrap_version') === CREMENI_STORE_BOOTSTRAP_VERSION) {
        return;
    }

    $home_id = cremeni_store_bootstrap_page(
        'Início',
        'inicio',
        '<!-- wp:paragraph --><p>Bem-vindo à Cremeni.</p><!-- /wp:paragraph -->'
    );

    cremeni_store_bootstrap_page('Esporte', 'esporte');
    cremeni_store_bootstrap_page('Pet Mimos', 'pet-mimos');
    cremeni_store_bootstrap_page('Guias Cremeni', 'guias-cremeni');
    cremeni_store_bootstrap_page('Modalidades', 'modalidades');
    cremeni_store_bootstrap_page('Sobre a Cremeni', 'sobre');
    cremeni_store_bootstrap_page('Atendimento', 'atendimento');
    cremeni_store_bootstrap_page('Política de Privacidade', 'politica-de-privacidade');
    cremeni_store_bootstrap_page('Política de Trocas e Devoluções', 'trocas-e-devolucoes');
    cremeni_store_bootstrap_page('Política de Entrega', 'politica-de-entrega');
    cremeni_store_bootstrap_page('Termos e Condições', 'termos-e-condicoes');

    if ($home_id > 0) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_id);
    }

    if (taxonomy_exists('product_cat')) {
        $esporte_id = cremeni_store_bootstrap_product_term(
            'Esporte',
            'esporte',
            'Produtos selecionados para prática esportiva, treino, mobilidade e vida ativa.'
        );

        cremeni_store_bootstrap_product_term(
            'Pet Mimos',
            'pet-mimos',
            'Pequenos mimos, brinquedos e acessórios leves para companhia, passeio e vínculo.'
        );

        cremeni_store_bootstrap_product_term(
            'Guias Cremeni',
            'guias-cremeni',
            'Conteúdos proprietários sobre corpo, mente, rotina, bem-estar e convivência com pets.'
        );

        if ($esporte_id > 0) {
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
                    sprintf('Produtos selecionados para %s.', mb_strtolower($name)),
                    $esporte_id
                );
            }
        }
    }

    update_option('cremeni_store_bootstrap_version', CREMENI_STORE_BOOTSTRAP_VERSION);
}
add_action('admin_init', 'cremeni_store_run_bootstrap');
