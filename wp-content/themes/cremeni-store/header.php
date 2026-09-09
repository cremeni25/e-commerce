<?php
/**
 * Cabeçalho global do tema.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

$cremeni_official_logo = require get_template_directory() . '/assets/cremeni-official-logo-data.php';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <style id="cremeni-official-brand-hotfix">
        .site-brand--official{display:inline-flex;align-items:center;min-width:220px;text-decoration:none!important}
        .site-brand__official-wordmark{display:block;width:clamp(210px,18vw,300px);height:auto;max-height:72px;object-fit:contain;object-position:left center}
        .hero__official-wordmark{display:block;position:relative;z-index:1;width:min(92%,620px);height:auto;opacity:.22;filter:drop-shadow(0 0 28px rgba(168,255,0,.28));transform:rotate(-3deg);pointer-events:none;user-select:none}
        .hero--brand-first .hero__visual{overflow:hidden}
        @media(max-width:820px){.site-brand--official{min-width:170px}.site-brand__official-wordmark{width:190px;max-height:58px}.hero__official-wordmark{width:min(88%,420px);opacity:.18}}
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#conteudo"><?php esc_html_e('Ir para o conteúdo', 'cremeni-store'); ?></a>
<header class="site-header">
    <div class="site-header__utility">
        <div class="cremeni-container utility-bar">
            <span><?php esc_html_e('CREMENI Esporte • PET Mimos • Guias CREMENI', 'cremeni-store'); ?></span>
            <span><?php esc_html_e('Seleção criteriosa • Dropshipping nacional • Conteúdo próprio', 'cremeni-store'); ?></span>
        </div>
    </div>

    <div class="cremeni-container site-header__main">
        <a class="site-brand site-brand--official" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Página inicial da CREMENI', 'cremeni-store'); ?>">
            <img class="site-brand__official-wordmark" src="<?php echo esc_attr($cremeni_official_logo); ?>" alt="CREMENI" width="1200" height="335">
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
            <a class="sports-link" href="<?php echo esc_url(home_url('/#esportes')); ?>"><?php esc_html_e('CREMENI Esporte', 'cremeni-store'); ?></a>
        </div>
    </div>
</header>
