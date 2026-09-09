<?php
/**
 * Plugin Name: Cremeni Commercial Governance
 * Description: Governança comercial de SKUs WooCommerce para Esporte, Pet Mimos, Pet Premium e Guias Cremeni.
 * Version: 0.2.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

const CREMENI_GOVERNANCE_VERSION = '0.2.0';

function cremeni_governance_verticals(): array
{
    return [
        'esporte'     => 'CREMENI Esporte',
        'pet_mimos'   => 'CREMENI Pet Mimos',
        'pet_premium' => 'CREMENI Pet Premium',
        'guias'       => 'Guias Cremeni',
    ];
}

function cremeni_governance_roles(): array
{
    return [
        'ancora'       => 'Âncora',
        'complementar' => 'Complementar',
        'kit'           => 'Kit',
        'conteudo'      => 'Conteúdo',
    ];
}

function cremeni_governance_decimal(string $value): float
{
    $value = trim($value);
    if ($value === '') {
        return 0.0;
    }

    if (str_contains($value, ',')) {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
    }

    return max(0.0, (float) $value);
}

function cremeni_governance_date_is_expired(string $date): bool
{
    if ($date === '') {
        return false;
    }

    $timezone = wp_timezone();
    $expiry = DateTimeImmutable::createFromFormat('!Y-m-d', $date, $timezone);
    if (! $expiry) {
        return true;
    }

    $expiry = $expiry->setTime(23, 59, 59);
    return $expiry < new DateTimeImmutable('now', $timezone);
}

function cremeni_governance_pricing_rule(string $vertical): string
{
    return match ($vertical) {
        'esporte'     => 'custo_drop_mais_piso_absoluto_10',
        'pet_mimos'   => 'markup_variavel_mercado_contribuicao_logistica',
        'pet_premium' => 'contribuicao_positiva_mercado',
        'guias'       => 'conteudo_digital',
        default       => 'nao_definida',
    };
}

function cremeni_governance_evaluate(int $product_id): array
{
    if (get_post_type($product_id) !== 'product') {
        return ['enabled' => false, 'eligible' => true, 'blockers' => [], 'metrics' => []];
    }

    $enabled = get_post_meta($product_id, '_cremeni_governance_enabled', true) === '1';
    if (! $enabled) {
        return ['enabled' => false, 'eligible' => true, 'blockers' => [], 'metrics' => []];
    }

    $vertical = (string) get_post_meta($product_id, '_cremeni_vertical', true);
    $role = (string) get_post_meta($product_id, '_cremeni_commercial_role', true);
    $supplier = trim((string) get_post_meta($product_id, '_cremeni_supplier', true));
    $supplier_sku = trim((string) get_post_meta($product_id, '_cremeni_supplier_sku', true));
    $drop_confirmed = get_post_meta($product_id, '_cremeni_drop_confirmed', true) === '1';
    $logistics_validated = get_post_meta($product_id, '_cremeni_logistics_validated', true) === '1';
    $drop_cost = (float) get_post_meta($product_id, '_cremeni_drop_cost', true);
    $market_reference = (float) get_post_meta($product_id, '_cremeni_market_reference', true);
    $target_price = (float) get_post_meta($product_id, '_cremeni_target_price', true);
    $incremental_costs = (float) get_post_meta($product_id, '_cremeni_incremental_costs', true);
    $incremental_freight = (float) get_post_meta($product_id, '_cremeni_incremental_freight', true);
    $supplier_stock = (string) get_post_meta($product_id, '_cremeni_supplier_stock', true);
    $cost_valid_until = (string) get_post_meta($product_id, '_cremeni_cost_valid_until', true);
    $drop_valid_until = (string) get_post_meta($product_id, '_cremeni_drop_valid_until', true);

    $blockers = [];
    $physical_verticals = ['esporte', 'pet_mimos', 'pet_premium'];

    if (! array_key_exists($vertical, cremeni_governance_verticals())) {
        $blockers[] = 'Vertical comercial não definida.';
    }

    if (! array_key_exists($role, cremeni_governance_roles())) {
        $blockers[] = 'Papel comercial não definido.';
    }

    if (in_array($vertical, $physical_verticals, true)) {
        if ($supplier === '') {
            $blockers[] = 'Fornecedor não informado.';
        }
        if ($supplier_sku === '') {
            $blockers[] = 'SKU do fornecedor não informado.';
        }
        if (! $drop_confirmed) {
            $blockers[] = 'Dropshipping nacional ainda não confirmado.';
        }
        if ($drop_cost <= 0) {
            $blockers[] = 'Custo drop inválido ou não informado.';
        }
        if ($target_price <= 0) {
            $blockers[] = 'Preço CREMENI não informado.';
        }
        if ($supplier_stock !== 'in_stock') {
            $blockers[] = 'Estoque do fornecedor não está confirmado como disponível.';
        }
        if ($cost_valid_until === '') {
            $blockers[] = 'Validade do custo drop não informada.';
        }
        if ($drop_valid_until === '') {
            $blockers[] = 'Validade da condição de dropshipping não informada.';
        }
    }

    if ($cost_valid_until !== '' && cremeni_governance_date_is_expired($cost_valid_until)) {
        $blockers[] = 'Validade do custo drop expirou.';
    }

    if ($drop_valid_until !== '' && cremeni_governance_date_is_expired($drop_valid_until)) {
        $blockers[] = 'Validade da condição de dropshipping expirou.';
    }

    $gross_difference = $target_price - $drop_cost;
    $incremental_contribution = $target_price - $drop_cost - $incremental_costs - $incremental_freight;
    $markup_percent = $drop_cost > 0 ? (($target_price / $drop_cost) - 1) * 100 : 0.0;

    if ($vertical === 'esporte' && $drop_cost > 0 && $target_price > 0 && $gross_difference < 10) {
        $blockers[] = 'Produto Esporte abaixo do piso comercial de R$ 10,00 sobre o custo drop.';
    }

    if ($vertical === 'pet_mimos' && $drop_cost > 0 && $target_price > 0) {
        if (! $logistics_validated) {
            $blockers[] = 'Logística/frete incremental do PET Mimos ainda não validado.';
        }
        if ($incremental_contribution <= 0) {
            $blockers[] = 'PET Mimos sem contribuição incremental positiva para o carrinho.';
        }
        if ($market_reference <= 0) {
            $blockers[] = 'PET Mimos sem referência de mercado validada.';
        } elseif ($target_price > $market_reference) {
            $blockers[] = 'Preço PET Mimos acima da referência de mercado cadastrada.';
        }
    }

    if ($vertical === 'pet_premium' && $drop_cost > 0 && $target_price > 0) {
        if (! $logistics_validated) {
            $blockers[] = 'Logística/frete incremental do PET Premium ainda não validado.';
        }
        if ($incremental_contribution <= 0) {
            $blockers[] = 'PET Premium sem contribuição incremental positiva.';
        }
    }

    return [
        'enabled'  => true,
        'eligible' => $blockers === [],
        'blockers' => $blockers,
        'metrics'  => [
            'gross_difference'         => round($gross_difference, 2),
            'incremental_contribution' => round($incremental_contribution, 2),
            'markup_percent'           => round($markup_percent, 2),
            'pricing_rule'             => cremeni_governance_pricing_rule($vertical),
        ],
    ];
}

function cremeni_governance_meta_box(): void
{
    add_meta_box(
        'cremeni-commercial-governance',
        'Governança Comercial CREMENI',
        'cremeni_governance_render_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_product', 'cremeni_governance_meta_box');

function cremeni_governance_render_meta_box(WP_Post $post): void
{
    wp_nonce_field('cremeni_governance_save', 'cremeni_governance_nonce');

    $get = static fn(string $key): string => (string) get_post_meta($post->ID, $key, true);
    $evaluation = cremeni_governance_evaluate($post->ID);
    ?>
    <p><label><input type="checkbox" name="cremeni_governance_enabled" value="1" <?php checked($get('_cremeni_governance_enabled'), '1'); ?>> Ativar governança comercial para este SKU</label></p>
    <table class="form-table" role="presentation">
        <tr><th><label for="cremeni_vertical">Vertical</label></th><td><select id="cremeni_vertical" name="cremeni_vertical"><option value="">Selecione</option><?php foreach (cremeni_governance_verticals() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_vertical'), $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_commercial_role">Papel comercial</label></th><td><select id="cremeni_commercial_role" name="cremeni_commercial_role"><option value="">Selecione</option><?php foreach (cremeni_governance_roles() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_commercial_role'), $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_supplier">Fornecedor</label></th><td><input class="regular-text" id="cremeni_supplier" name="cremeni_supplier" value="<?php echo esc_attr($get('_cremeni_supplier')); ?>"></td></tr>
        <tr><th><label for="cremeni_supplier_sku">SKU fornecedor</label></th><td><input class="regular-text" id="cremeni_supplier_sku" name="cremeni_supplier_sku" value="<?php echo esc_attr($get('_cremeni_supplier_sku')); ?>"></td></tr>
        <tr><th>Dropshipping confirmado</th><td><label><input type="checkbox" name="cremeni_drop_confirmed" value="1" <?php checked($get('_cremeni_drop_confirmed'), '1'); ?>> Sim</label></td></tr>
        <tr><th><label for="cremeni_drop_cost">Custo drop (R$)</label></th><td><input id="cremeni_drop_cost" name="cremeni_drop_cost" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_drop_cost')); ?>"></td></tr>
        <tr><th><label for="cremeni_market_reference">Referência de mercado (R$)</label></th><td><input id="cremeni_market_reference" name="cremeni_market_reference" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_market_reference')); ?>"></td></tr>
        <tr><th><label for="cremeni_target_price">Preço CREMENI (R$)</label></th><td><input id="cremeni_target_price" name="cremeni_target_price" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_target_price')); ?>"></td></tr>
        <tr><th><label for="cremeni_incremental_costs">Custos incrementais (R$)</label></th><td><input id="cremeni_incremental_costs" name="cremeni_incremental_costs" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_incremental_costs')); ?>"></td></tr>
        <tr><th><label for="cremeni_incremental_freight">Frete incremental (R$)</label></th><td><input id="cremeni_incremental_freight" name="cremeni_incremental_freight" inputmode="decimal" value="<?php echo esc_attr($get('_cremeni_incremental_freight')); ?>"></td></tr>
        <tr><th>Logística validada</th><td><label><input type="checkbox" name="cremeni_logistics_validated" value="1" <?php checked($get('_cremeni_logistics_validated'), '1'); ?>> Frete/origem logística conferidos</label></td></tr>
        <tr><th><label for="cremeni_supplier_stock">Estoque fornecedor</label></th><td><select id="cremeni_supplier_stock" name="cremeni_supplier_stock"><option value="unknown" <?php selected($get('_cremeni_supplier_stock'), 'unknown'); ?>>Não validado</option><option value="in_stock" <?php selected($get('_cremeni_supplier_stock'), 'in_stock'); ?>>Disponível</option><option value="out_of_stock" <?php selected($get('_cremeni_supplier_stock'), 'out_of_stock'); ?>>Indisponível</option></select></td></tr>
        <tr><th><label for="cremeni_cost_valid_until">Custo válido até</label></th><td><input type="date" id="cremeni_cost_valid_until" name="cremeni_cost_valid_until" value="<?php echo esc_attr($get('_cremeni_cost_valid_until')); ?>"></td></tr>
        <tr><th><label for="cremeni_drop_valid_until">Drop válido até</label></th><td><input type="date" id="cremeni_drop_valid_until" name="cremeni_drop_valid_until" value="<?php echo esc_attr($get('_cremeni_drop_valid_until')); ?>"></td></tr>
        <tr><th><label for="cremeni_personalization">Personalização</label></th><td><input class="regular-text" id="cremeni_personalization" name="cremeni_personalization" value="<?php echo esc_attr($get('_cremeni_personalization')); ?>" placeholder="nenhuma, nome, logo, arte, foto..."></td></tr>
        <tr><th><label for="cremeni_related_guide">Guia relacionado</label></th><td><input class="regular-text" id="cremeni_related_guide" name="cremeni_related_guide" value="<?php echo esc_attr($get('_cremeni_related_guide')); ?>"></td></tr>
    </table>
    <?php if ($evaluation['enabled']) : ?>
        <div style="padding:12px;border-left:4px solid <?php echo $evaluation['eligible'] ? '#00a32a' : '#d63638'; ?>;background:#fff;">
            <strong><?php echo $evaluation['eligible'] ? 'PUBLICÁVEL' : 'BLOQUEADO'; ?></strong>
            <?php if (! $evaluation['eligible']) : ?><ul><?php foreach ($evaluation['blockers'] as $blocker) : ?><li><?php echo esc_html($blocker); ?></li><?php endforeach; ?></ul><?php endif; ?>
            <p>Diferença bruta: R$ <?php echo esc_html(number_format((float) $evaluation['metrics']['gross_difference'], 2, ',', '.')); ?> · Contribuição incremental: R$ <?php echo esc_html(number_format((float) $evaluation['metrics']['incremental_contribution'], 2, ',', '.')); ?> · Markup: <?php echo esc_html(number_format((float) $evaluation['metrics']['markup_percent'], 2, ',', '.')); ?>%</p>
        </div>
    <?php endif;
}

function cremeni_governance_save_product(int $post_id): void
{
    static $processing = false;
    if ($processing) {
        return;
    }

    if (! isset($_POST['cremeni_governance_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cremeni_governance_nonce'])), 'cremeni_governance_save')) {
        return;
    }

    if (! current_user_can('edit_post', $post_id) || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    $processing = true;

    update_post_meta($post_id, '_cremeni_governance_enabled', isset($_POST['cremeni_governance_enabled']) ? '1' : '0');
    update_post_meta($post_id, '_cremeni_drop_confirmed', isset($_POST['cremeni_drop_confirmed']) ? '1' : '0');
    update_post_meta($post_id, '_cremeni_logistics_validated', isset($_POST['cremeni_logistics_validated']) ? '1' : '0');

    $text_fields = [
        'cremeni_vertical'         => '_cremeni_vertical',
        'cremeni_commercial_role'  => '_cremeni_commercial_role',
        'cremeni_supplier'         => '_cremeni_supplier',
        'cremeni_supplier_sku'     => '_cremeni_supplier_sku',
        'cremeni_supplier_stock'   => '_cremeni_supplier_stock',
        'cremeni_cost_valid_until' => '_cremeni_cost_valid_until',
        'cremeni_drop_valid_until' => '_cremeni_drop_valid_until',
        'cremeni_personalization'  => '_cremeni_personalization',
        'cremeni_related_guide'    => '_cremeni_related_guide',
    ];

    foreach ($text_fields as $request_key => $meta_key) {
        $value = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '';
        update_post_meta($post_id, $meta_key, $value);
    }

    $decimal_fields = [
        'cremeni_drop_cost'           => '_cremeni_drop_cost',
        'cremeni_market_reference'    => '_cremeni_market_reference',
        'cremeni_target_price'        => '_cremeni_target_price',
        'cremeni_incremental_costs'   => '_cremeni_incremental_costs',
        'cremeni_incremental_freight' => '_cremeni_incremental_freight',
    ];

    foreach ($decimal_fields as $request_key => $meta_key) {
        $raw = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '0';
        update_post_meta($post_id, $meta_key, (string) cremeni_governance_decimal($raw));
    }

    $vertical = (string) get_post_meta($post_id, '_cremeni_vertical', true);
    update_post_meta($post_id, '_cremeni_pricing_rule', cremeni_governance_pricing_rule($vertical));

    $evaluation = cremeni_governance_evaluate($post_id);
    update_post_meta($post_id, '_cremeni_commercial_status', $evaluation['eligible'] ? 'publicavel' : 'revisao_comercial');
    update_post_meta($post_id, '_cremeni_last_evaluation', current_time('mysql'));

    if ($evaluation['enabled'] && ! $evaluation['eligible'] && get_post_status($post_id) === 'publish') {
        wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
        set_transient('cremeni_governance_blocked_' . get_current_user_id(), $evaluation['blockers'], 60);
    }

    $processing = false;
}
add_action('save_post_product', 'cremeni_governance_save_product', 30);

function cremeni_governance_prevent_invalid_publish(array $data, array $postarr): array
{
    if (($data['post_type'] ?? '') !== 'product' || ($data['post_status'] ?? '') !== 'publish') {
        return $data;
    }

    $post_id = isset($postarr['ID']) ? (int) $postarr['ID'] : 0;
    if ($post_id <= 0 || get_post_meta($post_id, '_cremeni_governance_enabled', true) !== '1') {
        return $data;
    }

    $evaluation = cremeni_governance_evaluate($post_id);
    if (! $evaluation['eligible']) {
        $data['post_status'] = 'draft';
        set_transient('cremeni_governance_blocked_' . get_current_user_id(), $evaluation['blockers'], 60);
    }

    return $data;
}
add_filter('wp_insert_post_data', 'cremeni_governance_prevent_invalid_publish', 20, 2);

function cremeni_governance_admin_notice(): void
{
    $key = 'cremeni_governance_blocked_' . get_current_user_id();
    $blockers = get_transient($key);
    if (! is_array($blockers) || $blockers === []) {
        return;
    }

    delete_transient($key);
    echo '<div class="notice notice-error"><p><strong>Publicação bloqueada pela Governança Comercial CREMENI.</strong></p><ul>';
    foreach ($blockers as $blocker) {
        echo '<li>' . esc_html((string) $blocker) . '</li>';
    }
    echo '</ul></div>';
}
add_action('admin_notices', 'cremeni_governance_admin_notice');

function cremeni_governance_is_product_eligible(int $product_id): bool
{
    $evaluation = cremeni_governance_evaluate($product_id);
    return ! $evaluation['enabled'] || $evaluation['eligible'];
}

function cremeni_governance_purchasable(bool $purchasable, WC_Product $product): bool
{
    return $purchasable && cremeni_governance_is_product_eligible($product->get_id());
}
add_filter('woocommerce_is_purchasable', 'cremeni_governance_purchasable', 20, 2);

function cremeni_governance_visible(bool $visible, int $product_id): bool
{
    return $visible && cremeni_governance_is_product_eligible($product_id);
}
add_filter('woocommerce_product_is_visible', 'cremeni_governance_visible', 20, 2);

function cremeni_governance_product_columns(array $columns): array
{
    $columns['cremeni_governance'] = 'Governança CREMENI';
    return $columns;
}
add_filter('manage_edit-product_columns', 'cremeni_governance_product_columns', 30);

function cremeni_governance_product_column(string $column, int $post_id): void
{
    if ($column !== 'cremeni_governance') {
        return;
    }

    $evaluation = cremeni_governance_evaluate($post_id);
    if (! $evaluation['enabled']) {
        echo '<span>Não ativada</span>';
        return;
    }

    echo $evaluation['eligible']
        ? '<strong style="color:#00a32a">PUBLICÁVEL</strong>'
        : '<strong style="color:#d63638">BLOQUEADO</strong>';
}
add_action('manage_product_posts_custom_column', 'cremeni_governance_product_column', 10, 2);
