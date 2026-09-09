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
    <style id="cremeni-official-brand-hotfix">
        .site-brand--official{display:inline-flex;align-items:center;min-width:220px;text-decoration:none!important}
        .site-brand__wordmark,.hero__brand-signature{font-family:Arial,Helvetica,sans-serif;font-weight:800;letter-spacing:-.055em;text-transform:lowercase;line-height:.82;color:#91c400;background:linear-gradient(180deg,#baff00 0%,#80a900 46%,#445d1a 100%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;text-shadow:0 0 20px rgba(168,255,0,.18)}
        .site-brand__wordmark{font-size:clamp(2.15rem,3vw,3.35rem)}
        .hero__brand-signature{position:relative;z-index:1;font-size:clamp(5.5rem,11vw,10rem);opacity:.20;white-space:nowrap;transform:rotate(-4deg);pointer-events:none;user-select:none}
        .hero--brand-first .hero__visual{overflow:hidden}
        @media(max-width:820px){.site-brand--official{min-width:170px}.site-brand__wordmark{font-size:2rem}.hero__brand-signature{font-size:clamp(4rem,20vw,7rem)}}
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#conteudo"><?php esc_html_e('Ir para o conteúdo', 'cremeni-store'); ?></a>
<header class="site-header">
    <div class="site-header__utility">
        <div class="cremeni-container utility-bar">
            <span><?php esc_html_e('Produtos de parceiros selecionados • Entrega para todo o Brasil', 'cremeni-store'); ?></span>
            <span><?php esc_html_e('Compra segura e atendimento especializado', 'cremeni-store'); ?></span>
        </div>
    </div>

    <div class="cremeni-container site-header__main">
        <a class="site-brand site-brand--official" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Página inicial da Cremeni', 'cremeni-store'); ?>">
            <span class="site-brand__wordmark" aria-hidden="true">cremeni</span>
            <span class="screen-reader-text">CREMENI</span>
        </a>

        <?php if (function_exists('get_product_search_form')) : ?>
            <div class="site-search"><?php get_product_search_form(); ?></div>
        <?php else : ?>
            <div class="site-search"><?php get_search_form(); ?></div>
        <?php endif; ?>

        <div class="site-header__actions">
            <?php if (function_exists('wc_get_page_permalink')) : ?>
                <a class="header-action" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                    <span><?php esc_html_e('Entrar', 'cremeni-store'); ?></span>
                </a>
                <a class="header-action header-action--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>">
                    <span><?php esc_html_e('Carrinho', 'cremeni-store'); ?></span>
                    <span class="header-action__count"><?php echo esc_html((string) (WC()->cart ? WC()->cart->get_cart_contents_count() : 0)); ?></span>
                </a>
            <?php endif; ?>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principal">
                <span class="screen-reader-text"><?php esc_html_e('Abrir menu', 'cremeni-store'); ?></span>
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <div class="site-header__nav" id="menu-principal">
        <div class="cremeni-container site-header__nav-inner">
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
            <a class="sports-link" href="#esportes"><?php esc_html_e('Comprar por esporte', 'cremeni-store'); ?></a>
        </div>
    </div>
</header>