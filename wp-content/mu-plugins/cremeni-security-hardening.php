<?php
/**
 * Plugin Name: Cremeni Store — Security Hardening
 * Description: Regras mínimas de segurança aplicadas automaticamente à loja.
 * Version: 1.0.0
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_is_application_passwords_available', '__return_false');

function cremeni_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header("Content-Security-Policy: upgrade-insecure-requests; frame-ancestors 'self'");
}
add_action('send_headers', 'cremeni_security_headers');

function cremeni_disable_file_editor(): void
{
    if (! defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('muplugins_loaded', 'cremeni_disable_file_editor', 1);

function cremeni_login_errors(): string
{
    return __('Não foi possível concluir o login. Verifique os dados informados.', 'cremeni-store');
}
add_filter('login_errors', 'cremeni_login_errors');

function cremeni_force_ssl_admin_when_available(): void
{
    if (is_ssl() && ! defined('FORCE_SSL_ADMIN')) {
        define('FORCE_SSL_ADMIN', true);
    }
}
add_action('plugins_loaded', 'cremeni_force_ssl_admin_when_available', 1);

function cremeni_restrict_rest_users($endpoints)
{
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }

    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }

    return $endpoints;
}
add_filter('rest_endpoints', 'cremeni_restrict_rest_users');
