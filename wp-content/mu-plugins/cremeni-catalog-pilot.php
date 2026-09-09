<?php
/**
 * Plugin Name: CREMENI Catalog Pilot
 * Description: Materializa e governa o primeiro catálogo real CREMENI Esporte.
 * Version: 1.1.0
 * Author: CREMENI
 */

declare(strict_types=1);

if (! defined('ABSPATH')) { exit; }

const CREMENI_CATALOG_PILOT_VERSION = '1.1.0';

function cremeni_catalog_pilot_products(): array {
    return [
        'cremeni-raquete-beach-tennis-personalizada' => [
            'name' => 'Raquete Beach Tennis Personalizada',
            'short' => 'Raquete personalizável para Beach Tennis, selecionada para o catálogo inicial CREMENI Esporte.',
            'description' => '<h2>Beach Tennis com identidade própria.</h2><p>Modelo personalizável com nome, marca, logotipo, foto, arte ou frase. A seleção CREMENI prioriza função esportiva clara, personalização e operação nacional.</p><h3>Antes da compra</h3><p>A arte final, acabamento e prazo serão confirmados antes da liberação comercial.</p>',
            'supplier_sku' => '2093164',
            'supplier_cost' => '240.00',
            'supplier_url' => 'https://dinka.com.br/produto/raquete-beach-tennis-personalizado-com-nome-marca-logotipo-empresa-foto-arte-frase-personalizada/',
            'terms' => ['esporte','beach-tennis'],
            'facts' => ['Personalização: nome, marca, logotipo, foto, arte ou frase','Dropshipping disponível nos planos 2, 3, 4 e 5','Estoque revalidado em 09/09/2026'],
            'specs' => ['Modalidade'=>'Beach Tennis','Personalização'=>'Sim','Fornecedor'=>'Dinka','SKU fornecedor'=>'2093164','Condição atual'=>'Pré-operacional','Imagem'=>'Aguardando autorização/homologação'],
        ],
        'cremeni-colchonete-treino-yoga-personalizado' => [
            'name' => 'Colchonete Treino & Yoga Personalizado',
            'short' => 'Colchonete personalizável para treino, Pilates e Yoga, com aplicação de nome, marca ou arte.',
            'description' => '<h2>Um item de rotina, não apenas de treino.</h2><p>Colchonete selecionado para treino, Pilates e Yoga com possibilidade de personalização. É uma solução versátil para uso individual, estúdios, academias e presentes esportivos.</p><h3>Personalização</h3><p>A arte final, prazo e acabamento serão confirmados antes da ativação da venda.</p>',
            'supplier_sku' => '2092894',
            'supplier_cost' => '82.50',
            'supplier_url' => 'https://dinka.com.br/produto/colchonete-academia-exercicio-pilates-treino-yoga-personalizado-com-nome-marca-logotipo-empresa-foto-arte-frase-2/',
            'terms' => ['esporte','funcional','yoga-pilates'],
            'facts' => ['Indicado para treino, Pilates e Yoga','Personalização: nome, marca, logotipo, foto, arte ou frase','Dropshipping disponível nos planos 2, 3, 4 e 5','Estoque revalidado em 09/09/2026'],
            'specs' => ['Modalidades'=>'Treino funcional, Pilates e Yoga','Personalização'=>'Sim','Fornecedor'=>'Dinka','SKU fornecedor'=>'2092894','Condição atual'=>'Pré-operacional','Imagem'=>'Aguardando autorização/homologação'],
        ],
        'cremeni-kit-agilidade-8-cones-4-bastoes' => [
            'name' => 'Kit Agilidade — 8 Cones + 4 Bastões',
            'short' => 'Kit para treino de agilidade, percurso, velocidade e coordenação com 8 cones e 4 bastões.',
            'description' => '<h2>Treino funcional com estrutura.</h2><p>Kit composto por 8 cones furados de 24 cm e 4 bastões de 80 cm. Indicado para montagem de percursos, treino de agilidade, velocidade, coordenação e preparação esportiva.</p><h3>Composição</h3><p>8 cones coloridos + 4 bastões. Produto leve e portátil.</p>',
            'supplier_sku' => '5076',
            'supplier_cost' => '83.85',
            'supplier_url' => 'https://dinka.com.br/produto/kit-barreiras-8-cones-e-4-bastoes-2/',
            'terms' => ['esporte','funcional','futebol','futsal'],
            'facts' => ['8 cones furados de 24 cm','4 bastões de 80 cm','Uso em agilidade, coordenação e velocidade','Estoque revalidado em 09/09/2026'],
            'specs' => ['Aplicação'=>'Treino funcional, futebol e futsal','Composição'=>'8 cones + 4 bastões','Cones'=>'24 cm','Bastões'=>'80 cm','Fornecedor'=>'Dinka','SKU fornecedor'=>'5076','Condição atual'=>'Pré-operacional','Imagem'=>'Aguardando autorização/homologação'],
        ],
        'cremeni-prancha-aprendizagem-natacao-personalizada' => [
            'name' => 'Prancha de Aprendizagem de Natação Personalizada',
            'short' => 'Prancha de apoio para aprendizagem e prática de natação com opção de personalização.',
            'description' => '<h2>Natação desde os primeiros movimentos.</h2><p>Prancha selecionada para apoio à aprendizagem e prática de natação, com possibilidade de personalização com nome, marca, foto ou arte.</p><h3>Uso responsável</h3><p>É um acessório esportivo de apoio e não substitui supervisão, orientação profissional ou equipamento de segurança adequado.</p>',
            'supplier_sku' => '2076655',
            'supplier_cost' => '112.50',
            'supplier_url' => 'https://dinka.com.br/produto/pranchinha-prancha-aprendiz-natacao-praia-boia-infantil-personalizado/',
            'terms' => ['esporte','natacao'],
            'facts' => ['Apoio à aprendizagem e prática de natação','Personalização com nome, marca, foto ou arte','Estoque revalidado em 09/09/2026'],
            'specs' => ['Modalidade'=>'Natação','Uso'=>'Apoio à aprendizagem','Personalização'=>'Sim','Fornecedor'=>'Dinka','SKU fornecedor'=>'2076655','Condição atual'=>'Pré-operacional','Imagem'=>'Aguardando autorização/homologação'],
        ],
    ];
}

function cremeni_catalog_pilot_seed(): void {
    if (! class_exists('WooCommerce') || get_option('cremeni_catalog_pilot_version') === CREMENI_CATALOG_PILOT_VERSION) { return; }

    foreach (cremeni_catalog_pilot_products() as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'product');
        $product_id = $existing instanceof WP_Post ? (int) $existing->ID : 0;
        if ($product_id === 0) {
            $product_id = wp_insert_post(['post_type'=>'product','post_status'=>'publish','post_title'=>$data['name'],'post_name'=>$slug,'post_excerpt'=>$data['short'],'post_content'=>$data['description']], true);
            if (is_wp_error($product_id)) { continue; }
            $product_id = (int) $product_id;
        } else {
            wp_update_post(['ID'=>$product_id,'post_title'=>$data['name'],'post_excerpt'=>$data['short'],'post_content'=>$data['description']]);
        }

        wp_set_object_terms($product_id, $data['terms'], 'product_cat', false);
        wp_set_object_terms($product_id, 'simple', 'product_type', false);
        update_post_meta($product_id, '_sku', 'CRE-' . $data['supplier_sku']);
        update_post_meta($product_id, '_cremeni_supplier', 'Dinka');
        update_post_meta($product_id, '_cremeni_supplier_sku', $data['supplier_sku']);
        update_post_meta($product_id, '_cremeni_supplier_cost', $data['supplier_cost']);
        update_post_meta($product_id, '_cremeni_supplier_url', esc_url_raw($data['supplier_url']));
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
    }

    update_option('cremeni_catalog_pilot_version', CREMENI_CATALOG_PILOT_VERSION);
}
add_action('init', 'cremeni_catalog_pilot_seed', 60);

function cremeni_catalog_pilot_not_purchasable(bool $purchasable, WC_Product $product): bool {
    return get_post_meta($product->get_id(), '_cremeni_sales_enabled', true) === 'no' ? false : $purchasable;
}
add_filter('woocommerce_is_purchasable', 'cremeni_catalog_pilot_not_purchasable', 10, 2);

function cremeni_catalog_pilot_card_status(): void {
    global $product;
    if ($product instanceof WC_Product && get_post_meta($product->get_id(), '_cremeni_commercial_status', true) === 'pre_operacional') {
        echo '<p class="cremeni-product-status">' . esc_html__('Venda em ativação', 'cremeni-store') . '</p>';
    }
}
add_action('woocommerce_after_shop_loop_item_title', 'cremeni_catalog_pilot_card_status', 7);

function cremeni_catalog_pilot_single_status(): void {
    global $product;
    if (! $product instanceof WC_Product || get_post_meta($product->get_id(), '_cremeni_commercial_status', true) !== 'pre_operacional') { return; }
    $facts = json_decode((string) get_post_meta($product->get_id(), '_cremeni_public_facts', true), true);
    echo '<div class="cremeni-product-readiness"><strong>' . esc_html__('Produto real em preparação comercial', 'cremeni-store') . '</strong><p>' . esc_html__('Fornecedor, estoque e operação foram revalidados. A compra será liberada somente após ativação da condição operacional CREMENI.', 'cremeni-store') . '</p>';
    if (is_array($facts)) { echo '<ul>'; foreach ($facts as $fact) { echo '<li>' . esc_html((string) $fact) . '</li>'; } echo '</ul>'; }
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'cremeni_catalog_pilot_single_status', 25);

function cremeni_catalog_pilot_specs(): void {
    global $product;
    if (! $product instanceof WC_Product) { return; }
    $specs = json_decode((string) get_post_meta($product->get_id(), '_cremeni_public_specs', true), true);
    if (! is_array($specs) || $specs === []) { return; }
    echo '<section class="cremeni-product-specs"><h2>' . esc_html__('Ficha CREMENI', 'cremeni-store') . '</h2><dl>';
    foreach ($specs as $label => $value) { echo '<div><dt>' . esc_html((string) $label) . '</dt><dd>' . esc_html((string) $value) . '</dd></div>'; }
    echo '</dl></section>';
}
add_action('woocommerce_after_single_product_summary', 'cremeni_catalog_pilot_specs', 7);
