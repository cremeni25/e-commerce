<?php
/**
 * Cabeçalho global do tema.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="cremeni-container site-header__inner">
        <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Página inicial da Cremeni', 'cremeni-store'); ?>">
            <span class="site-brand__name">CREMENI</span>
            <span class="site-brand__tagline"><?php esc_html_e('Nutrição para performance', 'cremeni-store'); ?></span>
        </a>

        <nav class="site-navigation" aria-label="<?php esc_attr_e('Menu principal', 'cremeni-store'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'site-navigation__menu',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>

        <div class="site-header__actions">
            <?php if (function_exists('wc_get_cart_url')) : ?>
                <a class="header-action" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                    <?php esc_html_e('Minha conta', 'cremeni-store'); ?>
                </a>
                <a class="header-action header-action--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>">
                    <?php esc_html_e('Carrinho', 'cremeni-store'); ?>
                    <span class="header-action__count"><?php echo esc_html((string) WC()->cart->get_cart_contents_count()); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
