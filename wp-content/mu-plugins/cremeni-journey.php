<?php
/**
 * Plugin Name: Cremeni Journey
 * Description: Relações curadas entre produtos esportivos, PET Mimos e Guias Cremeni.
 * Version: 0.2.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_journey_parse_ids(string $raw): array
{
    $ids = array_map('absint', preg_split('/\s*,\s*/', trim($raw)) ?: []);
    return array_values(array_unique(array_filter($ids)));
}

function cremeni_journey_meta_box(): void
{
    add_meta_box(
        'cremeni-journey-relations',
        'Jornada CREMENI',
        'cremeni_journey_render_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes_product', 'cremeni_journey_meta_box');

function cremeni_journey_render_meta_box(WP_Post $post): void
{
    wp_nonce_field('cremeni_journey_save', 'cremeni_journey_nonce');
    $pet_ids = (string) get_post_meta($post->ID, '_cremeni_pet_cross_sell_ids', true);
    $guide_id = (string) get_post_meta($post->ID, '_cremeni_related_guide', true);
    ?>
    <p><label for="cremeni_pet_cross_sell_ids"><strong>PET Mimos relacionados</strong></label></p>
    <input class="widefat" id="cremeni_pet_cross_sell_ids" name="cremeni_pet_cross_sell_ids" value="<?php echo esc_attr($pet_ids); ?>" placeholder="IDs separados por vírgula">
    <p class="description">Somente PET Mimos curados, publicados e aprovados pela governança comercial serão exibidos.</p>

    <p><label for="cremeni_journey_guide_id"><strong>Guia Cremeni relacionado</strong></label></p>
    <input class="widefat" id="cremeni_journey_guide_id" name="cremeni_journey_guide_id" inputmode="numeric" value="<?php echo esc_attr($guide_id); ?>" placeholder="ID do Guia">
    <p class="description">O Guia precisa estar publicado e editorialmente aprovado para aparecer na jornada.</p>
    <?php
}

function cremeni_journey_save_product(int $post_id): void
{
    if (! isset($_POST['cremeni_journey_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cremeni_journey_nonce'])), 'cremeni_journey_save')) {
        return;
    }

    if (! current_user_can('edit_post', $post_id) || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    $pet_raw = isset($_POST['cremeni_pet_cross_sell_ids']) ? sanitize_text_field(wp_unslash($_POST['cremeni_pet_cross_sell_ids'])) : '';
    $pet_ids = cremeni_journey_parse_ids($pet_raw);
    update_post_meta($post_id, '_cremeni_pet_cross_sell_ids', implode(',', $pet_ids));

    $guide_id = isset($_POST['cremeni_journey_guide_id']) ? absint($_POST['cremeni_journey_guide_id']) : 0;
    update_post_meta($post_id, '_cremeni_related_guide', $guide_id > 0 ? (string) $guide_id : '');
}
add_action('save_post_product', 'cremeni_journey_save_product', 40);

function cremeni_journey_is_pet_mimo_eligible(int $product_id): bool
{
    if (get_post_type($product_id) !== 'product') {
        return false;
    }

    if ((string) get_post_meta($product_id, '_cremeni_vertical', true) !== 'pet_mimos') {
        return false;
    }

    if (get_post_meta($product_id, '_cremeni_governance_enabled', true) !== '1') {
        return false;
    }

    if (! function_exists('cremeni_governance_is_product_eligible') || ! cremeni_governance_is_product_eligible($product_id)) {
        return false;
    }

    return get_post_status($product_id) === 'publish';
}

function cremeni_journey_pet_ids_for_product(int $product_id): array
{
    $raw = (string) get_post_meta($product_id, '_cremeni_pet_cross_sell_ids', true);
    $ids = cremeni_journey_parse_ids($raw);

    return array_values(array_filter($ids, 'cremeni_journey_is_pet_mimo_eligible'));
}

function cremeni_journey_related_guide(int $product_id): ?WP_Post
{
    $guide_id = (int) get_post_meta($product_id, '_cremeni_related_guide', true);
    if ($guide_id <= 0) {
        return null;
    }

    $guide = get_post($guide_id);
    if (! $guide instanceof WP_Post || $guide->post_type !== 'cremeni_guide' || $guide->post_status !== 'publish') {
        return null;
    }

    if ((string) get_post_meta($guide_id, '_cremeni_guide_editorial_status', true) !== 'aprovado') {
        return null;
    }

    return $guide;
}

function cremeni_journey_render_pet_cross_sell(int $product_id): void
{
    $ids = cremeni_journey_pet_ids_for_product($product_id);
    if ($ids === []) {
        return;
    }

    $ids_param = implode(',', $ids);
    ?>
    <section class="cremeni-cross-sell">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e('PET MIMOS', 'cremeni-store'); ?></p>
            <h2><?php esc_html_e('Tem alguém te esperando em casa?', 'cremeni-store'); ?></h2>
            <p><?php esc_html_e('Pequenos mimos selecionados para complementar sua compra sem transformar o carrinho em uma nova decisão de alto valor.', 'cremeni-store'); ?></p>
        </div>
        <?php echo do_shortcode('[products ids="' . esc_attr($ids_param) . '" columns="3" orderby="post__in"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </section>
    <?php
}

function cremeni_journey_single_product_pet(): void
{
    global $product;
    if (! $product instanceof WC_Product) {
        return;
    }

    if ((string) get_post_meta($product->get_id(), '_cremeni_vertical', true) !== 'esporte') {
        return;
    }

    cremeni_journey_render_pet_cross_sell($product->get_id());
}
add_action('woocommerce_after_single_product_summary', 'cremeni_journey_single_product_pet', 18);

function cremeni_journey_single_product_guide(): void
{
    global $product;
    if (! $product instanceof WC_Product) {
        return;
    }

    $guide = cremeni_journey_related_guide($product->get_id());
    if (! $guide instanceof WP_Post) {
        return;
    }

    $subtitle = (string) get_post_meta($guide->ID, '_cremeni_guide_subtitle', true);
    ?>
    <section class="brand-story cremeni-related-guide">
        <div>
            <p class="eyebrow"><?php esc_html_e('GUIA CREMENI RELACIONADO', 'cremeni-store'); ?></p>
            <h2><?php echo esc_html(get_the_title($guide)); ?></h2>
        </div>
        <div>
            <?php if ($subtitle !== '') : ?><p><?php echo esc_html($subtitle); ?></p><?php endif; ?>
            <p><a class="text-link" href="<?php echo esc_url(get_permalink($guide)); ?>"><?php esc_html_e('Ler Guia Cremeni', 'cremeni-store'); ?></a></p>
        </div>
    </section>
    <?php
}
add_action('woocommerce_after_single_product_summary', 'cremeni_journey_single_product_guide', 25);

function cremeni_journey_cart_pet_ids(): array
{
    if (! function_exists('WC') || ! WC()->cart) {
        return [];
    }

    $suggested = [];
    $in_cart = [];

    foreach (WC()->cart->get_cart() as $item) {
        $product_id = (int) ($item['product_id'] ?? 0);
        if ($product_id <= 0) {
            continue;
        }

        $in_cart[] = $product_id;
        if ((string) get_post_meta($product_id, '_cremeni_vertical', true) !== 'esporte') {
            continue;
        }

        $suggested = array_merge($suggested, cremeni_journey_pet_ids_for_product($product_id));
    }

    $suggested = array_values(array_unique($suggested));
    return array_values(array_diff($suggested, array_unique($in_cart)));
}

function cremeni_journey_cart_pet_cross_sell(): void
{
    $ids = cremeni_journey_cart_pet_ids();
    if ($ids === []) {
        return;
    }

    ?>
    <section class="cremeni-cart-pet-mimos">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e('ANTES DE FECHAR O PEDIDO', 'cremeni-store'); ?></p>
            <h2><?php esc_html_e('Um mimo para quem faz parte da sua rotina?', 'cremeni-store'); ?></h2>
        </div>
        <?php echo do_shortcode('[products ids="' . esc_attr(implode(',', $ids)) . '" columns="4" orderby="post__in"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </section>
    <?php
}
add_action('woocommerce_after_cart_table', 'cremeni_journey_cart_pet_cross_sell', 20);
