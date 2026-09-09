<?php
/** Roteador comercial CREMENI independente de rewrite. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }

get_header();

$product_id = isset($_GET['cremeni_product']) ? absint($_GET['cremeni_product']) : 0;
$catalog = isset($_GET['cremeni_catalog']) ? sanitize_key(wp_unslash($_GET['cremeni_catalog'])) : '';
?>
<main id="conteudo" class="commerce-page cremeni-commerce-router">
  <div class="cremeni-container commerce-page__inner">
  <?php if ($product_id && function_exists('wc_get_product')) :
      $product = wc_get_product($product_id);
      if ($product) : ?>
        <article class="cremeni-product-detail">
          <div class="cremeni-product-detail__media"><?php echo wp_kses_post($product->get_image('woocommerce_single')); ?></div>
          <div class="cremeni-product-detail__content">
            <p class="eyebrow"><?php esc_html_e('CREMENI • PRODUTO', 'cremeni-store'); ?></p>
            <h1><?php echo esc_html($product->get_name()); ?></h1>
            <?php if ($product->get_short_description()) : ?><div class="cremeni-product-detail__lead"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></div><?php endif; ?>
            <?php if ($product->get_price_html()) : ?><div class="cremeni-product-detail__price"><?php echo wp_kses_post($product->get_price_html()); ?></div><?php endif; ?>
            <div class="cremeni-product-detail__description"><?php echo wp_kses_post(wpautop($product->get_description())); ?></div>
            <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
              <a class="button button--primary" href="<?php echo esc_url($product->add_to_cart_url()); ?>"><?php esc_html_e('Adicionar ao carrinho', 'cremeni-store'); ?></a>
            <?php else : ?>
              <span class="cremeni-commercial-status"><?php esc_html_e('Produto em homologação comercial', 'cremeni-store'); ?></span>
            <?php endif; ?>
          </div>
        </article>
      <?php else : ?>
        <section class="page-hero"><p class="eyebrow">404</p><h1><?php esc_html_e('Produto não encontrado.', 'cremeni-store'); ?></h1></section>
      <?php endif;
  else :
      $tax_query = [];
      $title = __('Loja CREMENI', 'cremeni-store');
      $description = __('Seleção comercial CREMENI organizada por esporte, Pet Mimos e conteúdo relacionado.', 'cremeni-store');
      if ($catalog && 'all' !== $catalog) {
          $term = get_term_by('slug', $catalog, 'product_cat');
          if ($term instanceof WP_Term) {
              $title = $term->name;
              $description = $term->description ?: __('Produtos selecionados pela curadoria CREMENI.', 'cremeni-store');
              $tax_query[] = ['taxonomy'=>'product_cat','field'=>'slug','terms'=>[$catalog]];
          }
      }
      $query = new WP_Query(['post_type'=>'product','post_status'=>'publish','posts_per_page'=>24,'orderby'=>'menu_order title','order'=>'ASC','tax_query'=>$tax_query]); ?>
      <header class="cremeni-catalog-head"><p class="eyebrow"><?php esc_html_e('CATÁLOGO CREMENI', 'cremeni-store'); ?></p><h1><?php echo esc_html($title); ?></h1><p><?php echo esc_html($description); ?></p></header>
      <?php if ($query->have_posts()) : ?><div class="cremeni-product-grid">
      <?php while ($query->have_posts()) : $query->the_post(); $product = wc_get_product(get_the_ID()); if (!$product) { continue; } $detail_url = add_query_arg('cremeni_product', $product->get_id(), home_url('/')); ?>
        <article class="cremeni-product-card"><a class="cremeni-product-card__media" href="<?php echo esc_url($detail_url); ?>"><?php echo wp_kses_post($product->get_image('woocommerce_thumbnail')); ?></a><div class="cremeni-product-card__body"><h2><a href="<?php echo esc_url($detail_url); ?>"><?php echo esc_html($product->get_name()); ?></a></h2><?php if ($product->get_price_html()) : ?><div class="cremeni-product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></div><?php endif; ?><a class="text-link" href="<?php echo esc_url($detail_url); ?>"><?php esc_html_e('Ver produto', 'cremeni-store'); ?></a></div></article>
      <?php endwhile; wp_reset_postdata(); ?></div>
      <?php else : ?><div class="cremeni-catalog-empty"><strong><?php esc_html_e('Seleção em preparação.', 'cremeni-store'); ?></strong><p><?php esc_html_e('Esta categoria ainda não possui produtos publicados pela curadoria CREMENI.', 'cremeni-store'); ?></p></div><?php endif;
  endif; ?>
  </div>
</main>
<?php get_footer();
