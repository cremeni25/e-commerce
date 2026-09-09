<?php
/**
 * Plugin Name: Cremeni Catalog Staging Actions
 * Description: Ações administrativas seguras para promoção de candidatos aprovados a produtos WooCommerce em rascunho.
 * Version: 0.1.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_staging_candidate_row_actions(array $actions, WP_Post $post): array
{
    if ($post->post_type !== 'cremeni_candidate' || ! current_user_can('edit_post', $post->ID)) {
        return $actions;
    }

    $existing_product = (int) get_post_meta($post->ID, '_cremeni_promoted_product_id', true);
    if ($existing_product > 0 && get_post_type($existing_product) === 'product') {
        $actions['cremeni_product'] = '<a href="' . esc_url(get_edit_post_link($existing_product, 'url')) . '">Abrir produto promovido</a>';
        return $actions;
    }

    if (! function_exists('cremeni_staging_candidate_is_promotable')) {
        return $actions;
    }

    $evaluation = cremeni_staging_candidate_is_promotable($post->ID);
    if (! ($evaluation['promotable'] ?? false)) {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin-post.php?action=cremeni_promote_candidate&candidate_id=' . $post->ID),
        'cremeni_promote_candidate_' . $post->ID
    );

    $actions['cremeni_promote'] = '<a href="' . esc_url($url) . '"><strong>Promover para produto (rascunho)</strong></a>';
    return $actions;
}
add_filter('post_row_actions', 'cremeni_staging_candidate_row_actions', 20, 2);

function cremeni_staging_handle_promotion(): void
{
    $candidate_id = isset($_GET['candidate_id']) ? absint($_GET['candidate_id']) : 0;

    if ($candidate_id <= 0 || get_post_type($candidate_id) !== 'cremeni_candidate') {
        wp_die('Candidato inválido.');
    }

    if (! current_user_can('edit_post', $candidate_id)) {
        wp_die('Você não tem permissão para promover este candidato.');
    }

    check_admin_referer('cremeni_promote_candidate_' . $candidate_id);

    if (! function_exists('cremeni_staging_candidate_is_promotable') || ! function_exists('cremeni_staging_promote_to_product')) {
        wp_die('Módulo de staging indisponível.');
    }

    $evaluation = cremeni_staging_candidate_is_promotable($candidate_id);
    if (! ($evaluation['promotable'] ?? false)) {
        $blockers = is_array($evaluation['blockers'] ?? null) ? $evaluation['blockers'] : [];
        set_transient('cremeni_staging_notice_' . get_current_user_id(), [
            'type'    => 'error',
            'message' => 'Promoção bloqueada: ' . implode(' ', array_map('strval', $blockers)),
        ], 60);
        wp_safe_redirect(admin_url('edit.php?post_type=cremeni_candidate'));
        exit;
    }

    $product_id = cremeni_staging_promote_to_product($candidate_id);
    if ($product_id <= 0) {
        set_transient('cremeni_staging_notice_' . get_current_user_id(), [
            'type'    => 'error',
            'message' => 'Não foi possível criar o produto em rascunho.',
        ], 60);
        wp_safe_redirect(admin_url('edit.php?post_type=cremeni_candidate'));
        exit;
    }

    set_transient('cremeni_staging_notice_' . get_current_user_id(), [
        'type'    => 'success',
        'message' => 'Candidato promovido com segurança. O produto foi criado em rascunho e ainda precisa passar pela governança comercial antes de qualquer publicação.',
    ], 60);

    $edit_url = get_edit_post_link($product_id, 'url');
    wp_safe_redirect(is_string($edit_url) && $edit_url !== '' ? $edit_url : admin_url('post.php?post=' . $product_id . '&action=edit'));
    exit;
}
add_action('admin_post_cremeni_promote_candidate', 'cremeni_staging_handle_promotion');

function cremeni_staging_admin_notice(): void
{
    $key = 'cremeni_staging_notice_' . get_current_user_id();
    $notice = get_transient($key);

    if (! is_array($notice)) {
        return;
    }

    delete_transient($key);
    $type = ($notice['type'] ?? '') === 'success' ? 'success' : 'error';
    $message = (string) ($notice['message'] ?? '');

    if ($message === '') {
        return;
    }

    echo '<div class="notice notice-' . esc_attr($type) . ' is-dismissible"><p>' . esc_html($message) . '</p></div>';
}
add_action('admin_notices', 'cremeni_staging_admin_notice');
