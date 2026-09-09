<?php
/**
 * Plugin Name: Cremeni Catalog Staging
 * Description: Staging administrativo de candidatos de catálogo antes da promoção segura para WooCommerce.
 * Version: 0.1.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_staging_register_post_type(): void
{
    register_post_type('cremeni_candidate', [
        'labels' => [
            'name'          => 'Candidatos de Catálogo',
            'singular_name' => 'Candidato de Catálogo',
            'add_new_item'  => 'Adicionar candidato',
            'edit_item'     => 'Editar candidato',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-filter',
        'supports'     => ['title', 'editor', 'revisions'],
    ]);
}
add_action('init', 'cremeni_staging_register_post_type');

function cremeni_staging_statuses(): array
{
    return [
        'pesquisa'            => 'PESQUISA',
        'drop_confirmado'     => 'DROP_CONFIRMADO',
        'comercial_aprovado'  => 'COMERCIAL_APROVADO',
        'preco_validado'      => 'PREÇO_VALIDADO',
        'publicavel'          => 'PUBLICÁVEL',
        'revisao_comercial'   => 'REVISÃO_COMERCIAL',
        'descartado'          => 'DESCARTADO',
    ];
}

function cremeni_staging_meta_box(): void
{
    add_meta_box('cremeni-candidate-data', 'Dados Comerciais do Candidato', 'cremeni_staging_render_meta_box', 'cremeni_candidate', 'normal', 'high');
}
add_action('add_meta_boxes_cremeni_candidate', 'cremeni_staging_meta_box');

function cremeni_staging_render_meta_box(WP_Post $post): void
{
    wp_nonce_field('cremeni_staging_save', 'cremeni_staging_nonce');
    $get = static fn(string $key): string => (string) get_post_meta($post->ID, $key, true);
    ?>
    <table class="form-table" role="presentation">
        <tr><th><label for="cremeni_candidate_vertical">Vertical</label></th><td><select id="cremeni_candidate_vertical" name="cremeni_candidate_vertical"><option value="">Selecione</option><?php foreach (cremeni_governance_verticals() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_candidate_vertical'), $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_candidate_role">Papel comercial</label></th><td><select id="cremeni_candidate_role" name="cremeni_candidate_role"><option value="">Selecione</option><?php foreach (cremeni_governance_roles() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_candidate_role'), $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_candidate_supplier">Fornecedor</label></th><td><input class="regular-text" id="cremeni_candidate_supplier" name="cremeni_candidate_supplier" value="<?php echo esc_attr($get('_cremeni_candidate_supplier')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_supplier_sku">SKU fornecedor</label></th><td><input class="regular-text" id="cremeni_candidate_supplier_sku" name="cremeni_candidate_supplier_sku" value="<?php echo esc_attr($get('_cremeni_candidate_supplier_sku')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_source">Fonte/evidência</label></th><td><input class="large-text" id="cremeni_candidate_source" name="cremeni_candidate_source" value="<?php echo esc_attr($get('_cremeni_candidate_source')); ?>"></td></tr>
        <tr><th>Dropshipping confirmado</th><td><label><input type="checkbox" name="cremeni_candidate_drop_confirmed" value="1" <?php checked($get('_cremeni_candidate_drop_confirmed'), '1'); ?>> Sim</label></td></tr>
        <tr><th><label for="cremeni_candidate_cost">Custo drop (R$)</label></th><td><input id="cremeni_candidate_cost" name="cremeni_candidate_cost" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_candidate_cost')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_market">Referência mercado (R$)</label></th><td><input id="cremeni_candidate_market" name="cremeni_candidate_market" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_candidate_market')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_price">Preço CREMENI (R$)</label></th><td><input id="cremeni_candidate_price" name="cremeni_candidate_price" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_candidate_price')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_incremental_costs">Custos incrementais (R$)</label></th><td><input id="cremeni_candidate_incremental_costs" name="cremeni_candidate_incremental_costs" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_candidate_incremental_costs')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_incremental_freight">Frete incremental (R$)</label></th><td><input id="cremeni_candidate_incremental_freight" name="cremeni_candidate_incremental_freight" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_candidate_incremental_freight')); ?>"></td></tr>
        <tr><th>Logística validada</th><td><label><input type="checkbox" name="cremeni_candidate_logistics_validated" value="1" <?php checked($get('_cremeni_candidate_logistics_validated'), '1'); ?>> Sim</label></td></tr>
        <tr><th><label for="cremeni_candidate_stock">Estoque</label></th><td><select id="cremeni_candidate_stock" name="cremeni_candidate_stock"><option value="unknown" <?php selected($get('_cremeni_candidate_stock'), 'unknown'); ?>>Não validado</option><option value="in_stock" <?php selected($get('_cremeni_candidate_stock'), 'in_stock'); ?>>Disponível</option><option value="out_of_stock" <?php selected($get('_cremeni_candidate_stock'), 'out_of_stock'); ?>>Indisponível</option></select></td></tr>
        <tr><th><label for="cremeni_candidate_cost_valid_until">Custo válido até</label></th><td><input type="date" id="cremeni_candidate_cost_valid_until" name="cremeni_candidate_cost_valid_until" value="<?php echo esc_attr($get('_cremeni_candidate_cost_valid_until')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_drop_valid_until">Drop válido até</label></th><td><input type="date" id="cremeni_candidate_drop_valid_until" name="cremeni_candidate_drop_valid_until" value="<?php echo esc_attr($get('_cremeni_candidate_drop_valid_until')); ?>"></td></tr>
        <tr><th><label for="cremeni_candidate_status">Status</label></th><td><select id="cremeni_candidate_status" name="cremeni_candidate_status"><?php foreach (cremeni_staging_statuses() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_candidate_status') ?: 'pesquisa', $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
    </table>
    <?php
}

function cremeni_staging_save(int $post_id): void
{
    if (! isset($_POST['cremeni_staging_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cremeni_staging_nonce'])), 'cremeni_staging_save')) {
        return;
    }

    if (! current_user_can('edit_post', $post_id) || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    update_post_meta($post_id, '_cremeni_candidate_drop_confirmed', isset($_POST['cremeni_candidate_drop_confirmed']) ? '1' : '0');
    update_post_meta($post_id, '_cremeni_candidate_logistics_validated', isset($_POST['cremeni_candidate_logistics_validated']) ? '1' : '0');

    $text = [
        'cremeni_candidate_vertical'         => '_cremeni_candidate_vertical',
        'cremeni_candidate_role'             => '_cremeni_candidate_role',
        'cremeni_candidate_supplier'         => '_cremeni_candidate_supplier',
        'cremeni_candidate_supplier_sku'     => '_cremeni_candidate_supplier_sku',
        'cremeni_candidate_source'           => '_cremeni_candidate_source',
        'cremeni_candidate_stock'            => '_cremeni_candidate_stock',
        'cremeni_candidate_cost_valid_until' => '_cremeni_candidate_cost_valid_until',
        'cremeni_candidate_drop_valid_until' => '_cremeni_candidate_drop_valid_until',
        'cremeni_candidate_status'           => '_cremeni_candidate_status',
    ];

    foreach ($text as $request_key => $meta_key) {
        $value = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '';
        update_post_meta($post_id, $meta_key, $value);
    }

    $decimal = [
        'cremeni_candidate_cost'                => '_cremeni_candidate_cost',
        'cremeni_candidate_market'              => '_cremeni_candidate_market',
        'cremeni_candidate_price'               => '_cremeni_candidate_price',
        'cremeni_candidate_incremental_costs'   => '_cremeni_candidate_incremental_costs',
        'cremeni_candidate_incremental_freight' => '_cremeni_candidate_incremental_freight',
    ];

    foreach ($decimal as $request_key => $meta_key) {
        $raw = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '0';
        update_post_meta($post_id, $meta_key, (string) cremeni_governance_decimal($raw));
    }
}
add_action('save_post_cremeni_candidate', 'cremeni_staging_save', 30);

function cremeni_staging_candidate_is_promotable(int $candidate_id): array
{
    $blockers = [];
    $vertical = (string) get_post_meta($candidate_id, '_cremeni_candidate_vertical', true);
    $role = (string) get_post_meta($candidate_id, '_cremeni_candidate_role', true);
    $supplier = trim((string) get_post_meta($candidate_id, '_cremeni_candidate_supplier', true));
    $supplier_sku = trim((string) get_post_meta($candidate_id, '_cremeni_candidate_supplier_sku', true));
    $drop_confirmed = get_post_meta($candidate_id, '_cremeni_candidate_drop_confirmed', true) === '1';
    $cost = (float) get_post_meta($candidate_id, '_cremeni_candidate_cost', true);
    $market = (float) get_post_meta($candidate_id, '_cremeni_candidate_market', true);
    $price = (float) get_post_meta($candidate_id, '_cremeni_candidate_price', true);
    $incremental_costs = (float) get_post_meta($candidate_id, '_cremeni_candidate_incremental_costs', true);
    $incremental_freight = (float) get_post_meta($candidate_id, '_cremeni_candidate_incremental_freight', true);
    $logistics = get_post_meta($candidate_id, '_cremeni_candidate_logistics_validated', true) === '1';
    $stock = (string) get_post_meta($candidate_id, '_cremeni_candidate_stock', true);
    $cost_valid_until = (string) get_post_meta($candidate_id, '_cremeni_candidate_cost_valid_until', true);
    $drop_valid_until = (string) get_post_meta($candidate_id, '_cremeni_candidate_drop_valid_until', true);
    $status = (string) get_post_meta($candidate_id, '_cremeni_candidate_status', true);

    if (! array_key_exists($vertical, cremeni_governance_verticals())) $blockers[] = 'Vertical inválida.';
    if (! array_key_exists($role, cremeni_governance_roles())) $blockers[] = 'Papel comercial inválido.';
    if ($supplier === '') $blockers[] = 'Fornecedor ausente.';
    if ($supplier_sku === '') $blockers[] = 'SKU fornecedor ausente.';
    if (! $drop_confirmed) $blockers[] = 'Dropshipping não confirmado.';
    if ($cost <= 0) $blockers[] = 'Custo drop inválido.';
    if ($price <= 0) $blockers[] = 'Preço CREMENI inválido.';
    if ($stock !== 'in_stock') $blockers[] = 'Estoque não confirmado.';
    if ($cost_valid_until === '' || cremeni_governance_date_is_expired($cost_valid_until)) $blockers[] = 'Custo sem validade vigente.';
    if ($drop_valid_until === '' || cremeni_governance_date_is_expired($drop_valid_until)) $blockers[] = 'Drop sem validade vigente.';
    if (! in_array($status, ['comercial_aprovado', 'preco_validado', 'publicavel'], true)) $blockers[] = 'Status ainda não permite promoção.';

    if ($vertical === 'esporte' && ($price - $cost) < 10) $blockers[] = 'Esporte abaixo do piso de R$10.';
    if ($vertical === 'pet_mimos') {
        if (! $logistics) $blockers[] = 'Logística PET Mimos não validada.';
        if ($market <= 0 || $price > $market) $blockers[] = 'Preço PET Mimos sem benchmark competitivo.';
        if (($price - $cost - $incremental_costs - $incremental_freight) <= 0) $blockers[] = 'PET Mimos sem contribuição incremental positiva.';
    }

    return ['promotable' => $blockers === [], 'blockers' => $blockers];
}

function cremeni_staging_promote_to_product(int $candidate_id): int
{
    if (! class_exists('WooCommerce')) {
        return 0;
    }

    $evaluation = cremeni_staging_candidate_is_promotable($candidate_id);
    if (! $evaluation['promotable']) {
        return 0;
    }

    $existing_product = (int) get_post_meta($candidate_id, '_cremeni_promoted_product_id', true);
    if ($existing_product > 0 && get_post_type($existing_product) === 'product') {
        return $existing_product;
    }

    $product_id = wp_insert_post([
        'post_type'    => 'product',
        'post_status'  => 'draft',
        'post_title'   => get_the_title($candidate_id),
        'post_content' => (string) get_post_field('post_content', $candidate_id),
    ], true);

    if (is_wp_error($product_id)) {
        return 0;
    }

    $map = [
        '_cremeni_candidate_vertical'            => '_cremeni_vertical',
        '_cremeni_candidate_role'                => '_cremeni_commercial_role',
        '_cremeni_candidate_supplier'            => '_cremeni_supplier',
        '_cremeni_candidate_supplier_sku'        => '_cremeni_supplier_sku',
        '_cremeni_candidate_drop_confirmed'      => '_cremeni_drop_confirmed',
        '_cremeni_candidate_cost'                => '_cremeni_drop_cost',
        '_cremeni_candidate_market'              => '_cremeni_market_reference',
        '_cremeni_candidate_price'               => '_cremeni_target_price',
        '_cremeni_candidate_incremental_costs'   => '_cremeni_incremental_costs',
        '_cremeni_candidate_incremental_freight' => '_cremeni_incremental_freight',
        '_cremeni_candidate_logistics_validated' => '_cremeni_logistics_validated',
        '_cremeni_candidate_stock'               => '_cremeni_supplier_stock',
        '_cremeni_candidate_cost_valid_until'    => '_cremeni_cost_valid_until',
        '_cremeni_candidate_drop_valid_until'    => '_cremeni_drop_valid_until',
    ];

    foreach ($map as $candidate_key => $product_key) {
        update_post_meta((int) $product_id, $product_key, get_post_meta($candidate_id, $candidate_key, true));
    }

    update_post_meta((int) $product_id, '_cremeni_governance_enabled', '1');
    update_post_meta((int) $product_id, '_cremeni_commercial_status', 'revisao_comercial');
    update_post_meta($candidate_id, '_cremeni_promoted_product_id', (string) $product_id);
    update_post_meta($candidate_id, '_cremeni_candidate_status', 'publicavel');

    return (int) $product_id;
}
