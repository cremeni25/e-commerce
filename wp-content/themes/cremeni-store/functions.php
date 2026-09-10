<?php
/** Configuração principal do tema CREMENI. @package CremeniStore */
declare(strict_types=1);
if (! defined('ABSPATH')) { exit; }

function cremeni_store_setup(): void {
    load_theme_textdomain('cremeni-store', get_template_directory() . '/languages');
    add_theme_support('title-tag'); add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', ['height'=>120,'width'=>420,'flex-height'=>true,'flex-width'=>true]);
    add_theme_support('html5', ['search-form','gallery','caption','style','script']);
    add_theme_support('responsive-embeds'); add_theme_support('align-wide'); add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom'); add_theme_support('wc-product-gallery-lightbox'); add_theme_support('wc-product-gallery-slider');
    register_nav_menus(['primary'=>__('Menu principal','cremeni-store'),'categories'=>__('Menu de universos','cremeni-store'),'sports'=>__('Menu por esportes','cremeni-store'),'footer'=>__('Menu do rodapé','cremeni-store')]);
}
add_action('after_setup_theme','cremeni_store_setup');

function cremeni_store_asset_version(string $relativePath): string { $path=get_template_directory().$relativePath; if(is_file($path)){return(string)filemtime($path);} $theme=wp_get_theme(); return $theme->get('Version')?:'0.7.0'; }
function cremeni_store_assets(): void {
    wp_enqueue_style('cremeni-store',get_stylesheet_uri(),[],cremeni_store_asset_version('/style.css'));
    wp_enqueue_style('cremeni-store-components',get_template_directory_uri().'/assets/css/components.css',['cremeni-store'],cremeni_store_asset_version('/assets/css/components.css'));
    wp_enqueue_style('cremeni-store-catalog',get_template_directory_uri().'/assets/css/catalog.css',['cremeni-store','cremeni-store-components'],cremeni_store_asset_version('/assets/css/catalog.css'));
    wp_enqueue_style('cremeni-store-premium',get_template_directory_uri().'/assets/css/premium.css',['cremeni-store-catalog'],cremeni_store_asset_version('/assets/css/premium.css'));
    wp_enqueue_script('cremeni-store-navigation',get_template_directory_uri().'/assets/js/navigation.js',[],cremeni_store_asset_version('/assets/js/navigation.js'),true);
}
add_action('wp_enqueue_scripts','cremeni_store_assets');
function cremeni_store_brand_icons(): void { $url=get_template_directory_uri().'/assets/images/cremeni-store-mark.svg'; echo '<link rel="icon" href="'.esc_url($url).'" type="image/svg+xml">'."\n"; }
add_action('wp_head','cremeni_store_brand_icons',2); add_action('admin_head','cremeni_store_brand_icons',2);

function cremeni_store_product_categories(): array { return ['esporte'=>['label'=>__('CREMENI Esporte','cremeni-store'),'description'=>__('Produtos selecionados para movimento, treino e prática esportiva.','cremeni-store')],'pet-mimos'=>['label'=>__('CREMENI Pet Mimos','cremeni-store'),'description'=>__('Mimos leves e afetivos para quem também faz parte da sua rotina.','cremeni-store')],'guias-cremeni'=>['label'=>__('Guias CREMENI','cremeni-store'),'description'=>__('Conteúdo próprio para corpo, mente, rotina e convivência.','cremeni-store')]]; }
function cremeni_store_sports(): array { return ['natacao'=>__('Natação','cremeni-store'),'futebol'=>__('Futebol','cremeni-store'),'futsal'=>__('Futsal','cremeni-store'),'beach-tennis'=>__('Beach Tennis','cremeni-store'),'volei'=>__('Vôlei','cremeni-store'),'badminton'=>__('Badminton','cremeni-store'),'lutas'=>__('Lutas','cremeni-store'),'funcional'=>__('Treino funcional','cremeni-store'),'yoga-pilates'=>__('Yoga & Pilates','cremeni-store')]; }
function cremeni_store_initial(string $value): string { if(function_exists('mb_substr')&&function_exists('mb_strtoupper')){return mb_strtoupper(mb_substr($value,0,1,'UTF-8'),'UTF-8');} return strtoupper(substr($value,0,1)); }

/** Rotas internas CREMENI: funcionam sem depender de rewrite/permalinks da hospedagem. */
function cremeni_store_page_url(string $slug): string { $page=get_page_by_path($slug,OBJECT,'page'); if($page instanceof WP_Post){$url=get_permalink($page);if(is_string($url)&&$url!==''){return$url;}} return home_url('/'.trim($slug,'/').'/'); }
function cremeni_store_catalog_url(): string { return add_query_arg('cremeni_catalog','all',home_url('/')); }
function cremeni_store_product_category_url(string $slug): string { return add_query_arg('cremeni_catalog',sanitize_key($slug),home_url('/')); }
function cremeni_store_product_url(int $product_id): string { return add_query_arg('cremeni_product',$product_id,home_url('/')); }

function cremeni_store_force_commerce_template(string $template): string {
    if (isset($_GET['cremeni_catalog']) || isset($_GET['cremeni_product'])) {
        $custom = get_template_directory() . '/template-cremeni-commerce.php';
        if (is_file($custom)) { return $custom; }
    }
    return $template;
}
add_filter('template_include','cremeni_store_force_commerce_template',99);

function cremeni_store_fallback_menu_items(): array { return [['label'=>__('Início','cremeni-store'),'url'=>home_url('/')],['label'=>__('Loja','cremeni-store'),'url'=>cremeni_store_catalog_url()],['label'=>__('Esporte','cremeni-store'),'url'=>cremeni_store_product_category_url('esporte')],['label'=>__('Pet Mimos','cremeni-store'),'url'=>cremeni_store_product_category_url('pet-mimos')],['label'=>__('Guias CREMENI','cremeni-store'),'url'=>cremeni_store_page_url('guias-cremeni')],['label'=>__('Atendimento','cremeni-store'),'url'=>cremeni_store_page_url('atendimento')]]; }
function cremeni_store_render_fallback_menu(string $class): void { echo '<ul class="'.esc_attr($class).'">'; foreach(cremeni_store_fallback_menu_items() as $item){echo '<li><a href="'.esc_url($item['url']).'">'.esc_html($item['label']).'</a></li>';} echo '</ul>'; }
function cremeni_store_cart_count_fragment(array $fragments): array { if(!function_exists('WC')||!WC()->cart){return$fragments;} ob_start(); ?><span class="header-action__count"><?php echo esc_html((string)WC()->cart->get_cart_contents_count());?></span><?php $fragments['span.header-action__count']=(string)ob_get_clean(); return$fragments; }
add_filter('woocommerce_add_to_cart_fragments','cremeni_store_cart_count_fragment');
function cremeni_store_woocommerce_loop_columns(): int{return 4;} add_filter('loop_shop_columns','cremeni_store_woocommerce_loop_columns');
function cremeni_store_woocommerce_products_per_page(): int{return 12;} add_filter('loop_shop_per_page','cremeni_store_woocommerce_products_per_page');
function cremeni_store_body_classes(array $classes): array { if((function_exists('is_woocommerce')&&is_woocommerce())||isset($_GET['cremeni_catalog'])||isset($_GET['cremeni_product'])){$classes[]='cremeni-commerce';} return$classes; }
add_filter('body_class','cremeni_store_body_classes');
function cremeni_store_placeholder_image(string $src): string { return get_template_directory_uri().'/assets/images/product-image-pending.svg'; }
add_filter('woocommerce_placeholder_img_src','cremeni_store_placeholder_image');
function cremeni_store_account_intro(): void { echo '<p class="cremeni-account-intro">'.esc_html__('Acompanhe pedidos, endereços, downloads e dados da sua conta CREMENI.','cremeni-store').'</p>'; }
add_action('woocommerce_account_dashboard','cremeni_store_account_intro',5);
