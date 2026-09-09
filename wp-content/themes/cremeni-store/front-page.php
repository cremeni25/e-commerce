<?php
/** Página inicial CREMENI. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
$categories = cremeni_store_product_categories();
$sports = cremeni_store_sports();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/loja/');
get_header();
?>
<main id="conteudo">
<section class="hero hero--brand-first">
  <div class="cremeni-container hero__grid">
    <div class="hero__content">
      <p class="eyebrow"><?php esc_html_e('ESPORTE • BEM-ESTAR • CONEXÃO', 'cremeni-store'); ?></p>
      <h1><?php esc_html_e('Viva em movimento.', 'cremeni-store'); ?></h1>
      <p class="hero__lead"><?php esc_html_e('Esporte para você. Cuidado para quem caminha ao seu lado. Conteúdo para uma rotina melhor.', 'cremeni-store'); ?></p>
      <div class="hero__actions">
        <a class="button button--primary" href="#universos-cremeni"><?php esc_html_e('Explorar CREMENI', 'cremeni-store'); ?></a>
        <a class="button button--secondary" href="#esportes"><?php esc_html_e('Ver esportes', 'cremeni-store'); ?></a>
      </div>
      <ul class="hero__proof"><li><?php esc_html_e('Curadoria criteriosa', 'cremeni-store'); ?></li><li><?php esc_html_e('Parceiros nacionais', 'cremeni-store'); ?></li><li><?php esc_html_e('Conteúdo próprio', 'cremeni-store'); ?></li></ul>
    </div>
    <div class="hero__visual" aria-hidden="true">
      <div class="hero__brand-panel"><?php require get_template_directory() . '/assets/cremeni-wordmark.php'; ?><span><?php esc_html_e('CORPO • MENTE • PET', 'cremeni-store'); ?></span></div>
    </div>
  </div>
</section>

<section class="trust-strip"><div class="cremeni-container trust-strip__grid">
<div><strong><?php esc_html_e('Esporte', 'cremeni-store'); ?></strong><span><?php esc_html_e('Produtos escolhidos por função, qualidade e competitividade.', 'cremeni-store'); ?></span></div>
<div><strong><?php esc_html_e('Pet Mimos', 'cremeni-store'); ?></strong><span><?php esc_html_e('Pequenos cuidados que acompanham uma vida ativa.', 'cremeni-store'); ?></span></div>
<div><strong><?php esc_html_e('Guias CREMENI', 'cremeni-store'); ?></strong><span><?php esc_html_e('Conteúdo útil para corpo, mente, rotina e convivência.', 'cremeni-store'); ?></span></div>
</div></section>

<section id="universos-cremeni" class="store-categories"><div class="cremeni-container">
<div class="section-heading section-heading--split"><div><p class="eyebrow"><?php esc_html_e('UNIVERSOS CREMENI', 'cremeni-store'); ?></p><h2><?php esc_html_e('Escolha como a CREMENI entra na sua rotina.', 'cremeni-store'); ?></h2></div><a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ver catálogo', 'cremeni-store'); ?></a></div>
<div class="store-categories__grid"><?php $index=1; foreach ($categories as $slug=>$category) : $url='guias-cremeni'===$slug ? home_url('/guias-cremeni/') : (function_exists('get_term_link') ? get_term_link($slug,'product_cat') : $shop_url); if (is_wp_error($url)) {$url=$shop_url;} ?><a class="category-card" href="<?php echo esc_url($url); ?>"><span class="category-card__index"><?php echo esc_html(str_pad((string)$index,2,'0',STR_PAD_LEFT)); ?></span><h3><?php echo esc_html($category['label']); ?></h3><p><?php echo esc_html($category['description']); ?></p><span class="category-card__action"><?php esc_html_e('Conhecer', 'cremeni-store'); ?></span></a><?php $index++; endforeach; ?></div>
</div></section>

<section id="esportes" class="sports-section"><div class="cremeni-container"><div class="section-heading"><p class="eyebrow"><?php esc_html_e('CREMENI ESPORTE', 'cremeni-store'); ?></p><h2><?php esc_html_e('Seu esporte. Sua rotina.', 'cremeni-store'); ?></h2><p><?php esc_html_e('Uma seleção objetiva por modalidade, sem catálogo inflado e sem produto sem função.', 'cremeni-store'); ?></p></div><div class="sports-grid"><?php foreach($sports as $slug=>$sport): ?><a class="sport-card" href="<?php echo esc_url(add_query_arg('esporte',$slug,$shop_url)); ?>"><span class="sport-card__mark"><?php echo esc_html(mb_strtoupper(mb_substr($sport,0,1))); ?></span><strong><?php echo esc_html($sport); ?></strong></a><?php endforeach; ?></div></div></section>

<?php if(class_exists('WooCommerce')): ?><section class="featured-products"><div class="cremeni-container"><div class="section-heading section-heading--split"><div><p class="eyebrow"><?php esc_html_e('SELEÇÃO CREMENI','cremeni-store'); ?></p><h2><?php esc_html_e('Produtos em destaque.','cremeni-store'); ?></h2></div><a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ir para a loja','cremeni-store'); ?></a></div><?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?></div></section><?php endif; ?>

<section class="brand-story"><div class="cremeni-container brand-story__grid"><div><p class="eyebrow"><?php esc_html_e('GUIAS CREMENI','cremeni-store'); ?></p><h2><?php esc_html_e('Conteúdo que continua depois da compra.','cremeni-store'); ?></h2></div><div><p><?php esc_html_e('Guias curtos e úteis conectam movimento, hábitos, bem-estar e convivência para manter a CREMENI presente na rotina.','cremeni-store'); ?></p><a class="text-link" href="<?php echo esc_url(home_url('/guias-cremeni/')); ?>"><?php esc_html_e('Conhecer os Guias','cremeni-store'); ?></a></div></div></section>
</main>
<?php get_footer();
