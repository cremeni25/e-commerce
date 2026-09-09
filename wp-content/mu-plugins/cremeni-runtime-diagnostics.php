<?php
/**
 * Plugin Name: CREMENI Runtime Diagnostics
 * Description: Registra de forma restrita o último erro fatal para diagnóstico administrativo.
 * Version: 1.0.0
 * Author: CREMENI
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_runtime_capture_fatal(): void
{
    $error = error_get_last();
    if (! is_array($error)) {
        return;
    }

    $fatal_types = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if (! in_array((int) ($error['type'] ?? 0), $fatal_types, true)) {
        return;
    }

    $message = sanitize_text_field((string) ($error['message'] ?? 'Erro fatal não identificado'));
    $file = basename((string) ($error['file'] ?? 'desconhecido'));
    $line = (int) ($error['line'] ?? 0);

    update_option('cremeni_runtime_last_fatal', [
        'time'    => gmdate('c'),
        'message' => $message,
        'file'    => $file,
        'line'    => $line,
    ], false);
}
register_shutdown_function('cremeni_runtime_capture_fatal');

function cremeni_runtime_admin_notice(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $fatal = get_option('cremeni_runtime_last_fatal');
    if (! is_array($fatal) || empty($fatal['message'])) {
        return;
    }

    printf(
        '<div class="notice notice-error"><p><strong>%s</strong><br>%s<br><code>%s:%d</code><br><small>%s</small></p></div>',
        esc_html__('CREMENI — último erro fatal registrado', 'cremeni-store'),
        esc_html((string) $fatal['message']),
        esc_html((string) ($fatal['file'] ?? 'desconhecido')),
        (int) ($fatal['line'] ?? 0),
        esc_html((string) ($fatal['time'] ?? ''))
    );
}
add_action('admin_notices', 'cremeni_runtime_admin_notice');
