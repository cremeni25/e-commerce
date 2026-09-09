<?php
/**
 * Plugin Name: Cremeni Commercial Governance
 * Description: Governança comercial de produtos WooCommerce: dropshipping, custo, piso, validade, estoque e elegibilidade de publicação.
 * Version: 0.1.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const CREMENI_MINIMUM_GROSS_DIFFERENCE = 10.00;

function cremeni_commercial_fields(): array
{
    return [
        '_cremeni_vertical' => ['label' => 'Vertical CREMENI', 'type' => 'select', 'options' => ['esporte' => 'Esporte', 'pet-mimos' => 'Pet Mimos', 'guias-cremeni' => 'Guias Cremeni']],
        '_cremeni_commercial_role' => ['label' => 'Papel comercial', 'type' => 'select', 'options' => ['anchor' => 'Âncora', 'complementary' => 'Complementar', 'kit' => 'Kit']],
        '_cremeni_supplier' => ['label' => 'Fornecedor', 'type' => 'text'],
        '_cremeni_supplier_sku' => ['label' => 'SKU do fornecedor', 'type' => 'text'],
        '_cremeni_drop_confirmed' => ['label' => 'Dropshipping confirmado', 'type' => 'checkbox'],
        '_cremeni_drop_cost' => ['label' => 'Custo drop (R$)', 'type' => 'price'],
        '_cremeni_supplier_min_price' => ['label' => 'Preço mínimo/base do fornecedor (R$)', 'type' => 'price'],
        '_cremeni_market_reference' => ['label' => 'Referência de mercado (R$)', 'type' => 'price'],
        '_cremeni_drop_valid_until' => ['label' => 'Condição drop válida até', 'type' => 'date'],
        '_cremeni_cost_valid_until' => ['label' => 'Custo válido até', 'type' => 'date'],
        '_cremeni_supplier_stock' => ['label' => 'Estoque do fornecedor', 'type' => 'number'],
        '_cremeni_personalization' => ['label' => 'Personalização', 'type' => 'select', 'options' => ['none' => 'Sem personalização', 'name' => 'Nome/texto', 'logo' => 'Logo', 'art' => 'Arte/foto']],
        '_cremeni_evidence_url' => ['label' => 'Evidência/URL comercial', 'type' => 'url'],
    ];
}

function cremeni_render_commercial_product_panel(): void
{
    global $post;
    if (! $post instanceof WP_Post) {
        return;
    }

    wp_nonce_field('cremeni_save_commercial_governance', 'cremeni_commercial_nonce');
    echo '<div class="options_group"><p><strong>Governança Comercial CREMENI</strong><br><small>Campos internos. O produto só poderá ser publicado quando as regras comerciais estiverem válidas.</small></p>';

    foreach (cremeni_commercial_fields() as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        $type = $field['type'];

        if ('select' === $type) {
            woocommerce_wp_select(['id' => $key, 'label' => $field['label'], 'value' => $value, 'options' => ['' => 'Selecione'] + $field['options']]);
        } elseif ('checkbox' === $type) {
            woocommerce_wp_checkbox(['id' => $key, 'label' => $field['label'], 'value' => $value, 'cbvalue' => 'yes']);
        } else {
            woocommerce_wp_text_input([
                'id' => $key,
                'label' => $field['label'],
                'value' => $value,
                'type' => in_array($type, ['date', 'number', 'url'], true) ? $type : 'text',
                'data_type' => 'price' === $type ? 'price' : '',
                'custom_attributes' => 'number' === $type ? ['min' => '0', 'step' => '1'] : [],
            ]);
        }
    }

    $evaluation = cremeni_evaluate_product_commercial_status($post->ID);
    echo '<p class="form-field"><strong>Status comercial:</strong> ' . esc_html($evaluation['status']) . '</p>';
    if ($evaluation['reasons']) {
        echo '<p class="form-field"><strong>Bloqueios:</strong> ' . esc_html(implode(' | ', $evaluation['reasons'])) . '</p>';
    }
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'cremeni_render_commercial_product_panel', 90);

function cremeni_save_commercial_product_fields(int $product_id): void
{
    if (! isset($_POST['cremeni_commercial_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cremeni_commercial_nonce'])), 'cremeni_save_commercial_governance')) {
        return;
    }

    if (! current_user_can('edit_post', $product_id)) {
        return;
    }

    foreach (cremeni_commercial_fields() as $key => $field) {
        if ('checkbox' === $field['type']) {
            update_post_meta($product_id, $key, isset($_POST[$key]) ? 'yes' : 'no');
            continue;
        }

        $raw = isset($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
        if ('price' === $field['type']) {
            $value = '' === $raw ? '' : wc_format_decimal($raw);
        } elseif ('number' === $field['type']) {
            $value = max(0, absint($raw));
        } elseif ('url' === $field['type']) {
            $value = esc_url_raw($raw);
        } else {
            $value = sanitize_text_field($raw);
        }
        update_post_meta($product_id, $key, $value);
    }

    $evaluation = cremeni_evaluate_product_commercial_status($product_id);
    update_post_meta($product_id, '_cremeni_commercial_status', $evaluation['status']);
    update_post_meta($product_id, '_cremeni_commercial_block_reasons', $evaluation['reasons']);
    update_post_meta($product_id, '_cremeni_commercial_evaluated_at', current_time('mysql'));
}
add_action('woocommerce_process_product_meta', 'cremeni_save_commercial_product_fields', 90);

function cremeni_product_floor_price(int $product_id): float
{
    $drop_cost = (float) get_post_meta($product_id, '_cremeni_drop_cost', true);
    $supplier_minimum = (float) get_post_meta($product_id, '_cremeni_supplier_min_price', true);
    return max($drop_cost + CREMENI_MINIMUM_GROSS_DIFFERENCE, $supplier_minimum);
}

function cremeni_date_expired(string $date): bool
{
    if ('' === $date) {
        return true;
    }
    $timestamp = strtotime($date . ' 23:59:59');
    return false === $timestamp || $timestamp < current_time('timestamp');
}

function cremeni_evaluate_product_commercial_status(int $product_id): array
{
    $reasons = [];
    $vertical = (string) get_post_meta($product_id, '_cremeni_vertical', true);
    $supplier = trim((string) get_post_meta($product_id, '_cremeni_supplier', true));
    $drop_confirmed = 'yes' === get_post_meta($product_id, '_cremeni_drop_confirmed', true);
    $drop_cost_raw = get_post_meta($product_id, '_cremeni_drop_cost', true);
    $stock_raw = get_post_meta($product_id, '_cremeni_supplier_stock', true);
    $cost_valid_until = (string) get_post_meta($product_id, '_cremeni_cost_valid_until', true);
    $drop_valid_until = (string) get_post_meta($product_id, '_cremeni_drop_valid_until', true);
    $product = wc_get_product($product_id);

    if ('' === $vertical) {
        $reasons[] = 'vertical não definida';
    }
    if ('' === $supplier && 'guias-cremeni' !== $vertical) {
        $reasons[] = 'fornecedor não definido';
    }
    if (! $drop_confirmed && 'guias-cremeni' !== $vertical) {
        $reasons[] = 'dropshipping não confirmado';
    }
    if ('' === (string) $drop_cost_raw && 'guias-cremeni' !== $vertical) {
        $reasons[] = 'custo drop não informado';
    }
    if ('guias-cremeni' !== $vertical && cremeni_date_expired($cost_valid_until)) {
        $reasons[] = 'custo sem validade ou vencido';
    }
    if ('guias-cremeni' !== $vertical && cremeni_date_expired($drop_valid_until)) {
        $reasons[] = 'condição drop sem validade ou vencida';
    }
    if ('guias-cremeni' !== $vertical && ('' === (string) $stock_raw || (int) $stock_raw <= 0)) {
        $reasons[] = 'estoque do fornecedor indisponível';
    }

    if ($product instanceof WC_Product && 'guias-cremeni' !== $vertical) {
        $price = (float) $product->get_regular_price('edit');
        $floor = cremeni_product_floor_price($product_id);
        if ($price <= 0) {
            $reasons[] = 'preço CREMENI não informado';
        } elseif ($price + 0.0001 < $floor) {
            $reasons[] = sprintf('preço abaixo do piso CREMENI de R$ %.2f', $floor);
        }
    }

    return ['status' => $reasons ? 'BLOQUEADO' : 'PUBLICÁVEL', 'reasons' => $reasons];
}

function cremeni_guard_product_publication(array $data, array $postarr): array
{
    if ('product' !== ($data['post_type'] ?? '') || 'publish' !== ($data['post_status'] ?? '')) {
        return $data;
    }

    $product_id = isset($postarr['ID']) ? (int) $postarr['ID'] : 0;
    if ($product_id <= 0 || ! function_exists('wc_get_product')) {
        return $data;
    }

    $evaluation = cremeni_evaluate_product_commercial_status($product_id);
    if ('PUBLICÁVEL' !== $evaluation['status']) {
        $data['post_status'] = 'draft';
        set_transient('cremeni_publication_block_' . get_current_user_id(), $evaluation['reasons'], 60);
    }
    return $data;
}
add_filter('wp_insert_post_data', 'cremeni_guard_product_publication', 99, 2);

function cremeni_commercial_admin_notice(): void
{
    $key = 'cremeni_publication_block_' . get_current_user_id();
    $reasons = get_transient($key);
    if (! is_array($reasons) || ! $reasons) {
        return;
    }
    delete_transient($key);
    echo '<div class="notice notice-error"><p><strong>Publicação bloqueada pela Governança Comercial CREMENI.</strong> ' . esc_html(implode(' | ', $reasons)) . '</p></div>';
}
add_action('admin_notices', 'cremeni_commercial_admin_notice');
