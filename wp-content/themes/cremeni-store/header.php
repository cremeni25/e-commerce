<?php
/** Cabeçalho global do tema. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#conteudo"><?php esc_html_e('Ir para o conteúdo', 'cremeni-store'); ?></a>
<header class="site-header">
    <div class="site-header__utility">
        <div class="cremeni-container utility-bar">
            <span><?php esc_html_e('CREMENI • BOM A QUALQUER HORA', 'cremeni-store'); ?></span>
            <span><?php esc_html_e('Curadoria nacional • Esporte • Pet • Conteúdo', 'cremeni-store'); ?></span>
        </div>
    </div>

    <div class="cremeni-container site-header__main">
        <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>"><?php require get_template_directory() . '/assets/cremeni-wordmark.php'; ?></a>

        <div class="site-header__actions">
            <?php if (function_exists('wc_get_page_permalink') && function_exists('WC')) : ?>
                <?php $account_url = wc_get_page_permalink('myaccount'); $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrinho/'); ?>
                <a class="header-action" href="<?php echo esc_url($account_url ?: home_url('/minha-conta/')); ?>"><?php esc_html_e('Minha conta', 'cremeni-store'); ?></a>
                <a class="header-action header-action--cart" href="<?php echo esc_url($cart_url); ?>"><span><?php esc_html_e('Carrinho', 'cremeni-store'); ?></span><span class="header-action__count"><?php echo esc_html((string) (WC()->cart ? WC()->cart->get_cart_contents_count() : 0)); ?></span></a>
            <?php endif; ?>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principal"><span class="screen-reader-text"><?php esc_html_e('Abrir menu', 'cremeni-store'); ?></span><span></span><span></span><span></span></button>
        </div>

        <div class="site-search"><?php function_exists('get_product_search_form') ? get_product_search_form() : get_search_form(); ?></div>
    </div>

    <div class="site-header__nav" id="menu-principal">
        <div class="cremeni-container site-header__nav-inner">
            <nav class="site-navigation" aria-label="<?php esc_attr_e('Menu principal', 'cremeni-store'); ?>">
                <?php if (has_nav_menu('primary')) { wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'site-navigation__menu','fallback_cb'=>false]); } else { cremeni_store_render_fallback_menu('site-navigation__menu'); } ?>
            </nav>
            <a class="sports-link" href="<?php echo esc_url(home_url('/#esportes')); ?>"><?php esc_html_e('Todas as modalidades', 'cremeni-store'); ?></a>
        </div>
    </div>
</header>
