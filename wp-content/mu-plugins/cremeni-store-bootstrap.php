<?php
/**
 * Plugin Name: Cremeni Store Bootstrap
 * Description: Cria a estrutura inicial da loja, páginas institucionais e categorias WooCommerce de forma idempotente.
 * Version: 0.1.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const CREMENI_STORE_BOOTSTRAP_VERSION = '0.1.0';

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
        '<!-- wp:paragraph --><p>Bem-vindo à Cremeni Store.</p><!-- /wp:paragraph -->'
    );

    cremeni_store_bootstrap_page('Modalidades', 'modalidades');
    cremeni_store_bootstrap_page('Marcas', 'marcas');
    cremeni_store_bootstrap_page('Sobre a Cremeni Store', 'sobre');
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
        $categories = [
            ['Suplementos', 'suplementos', 'Proteínas, creatina, pré-treinos, vitaminas e recuperação.'],
            ['Alimentos fitness', 'alimentos-fitness', 'Produtos industrializados, embalados e expedidos por parceiros.'],
            ['Roupas', 'roupas', 'Vestuário esportivo para treino, competição e rotina ativa.'],
            ['Equipamentos', 'equipamentos', 'Equipamentos para diferentes modalidades e ambientes de treino.'],
            ['Acessórios', 'acessorios', 'Acessórios para esporte, transporte, hidratação e organização.'],
            ['Infoprodutos', 'infoprodutos', 'Cursos, programas, guias e conteúdos digitais do setor.'],
        ];

        foreach ($categories as [$name, $slug, $description]) {
            cremeni_store_bootstrap_product_term($name, $slug, $description);
        }

        $sports_parent = cremeni_store_bootstrap_product_term(
            'Modalidades esportivas',
            'modalidades-esportivas',
            'Produtos organizados por modalidade esportiva.'
        );

        if ($sports_parent > 0) {
            $sports = [
                ['Natação', 'natacao'],
                ['Corrida', 'corrida'],
                ['Ciclismo', 'ciclismo'],
                ['Musculação', 'musculacao'],
                ['Cross training', 'cross-training'],
                ['Lutas', 'lutas'],
                ['Futebol', 'futebol'],
                ['Vôlei', 'volei'],
            ];

            foreach ($sports as [$name, $slug]) {
                cremeni_store_bootstrap_product_term(
                    $name,
                    $slug,
                    sprintf('Produtos selecionados para %s.', mb_strtolower($name)),
                    $sports_parent
                );
            }
        }
    }

    update_option('cremeni_store_bootstrap_version', CREMENI_STORE_BOOTSTRAP_VERSION);
}
add_action('admin_init', 'cremeni_store_run_bootstrap');
