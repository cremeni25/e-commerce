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
        <nav class="catalog-back"><a href="<?php echo esc_url(cremeni_store_catalog_url()); ?>">← <?php esc_html_e('Voltar para a loja', 'cremeni-store'); ?></a></nav>
        <article class="cremeni-product-detail">
          <div class="cremeni-product-detail__media"><?php echo wp_kses_post($product->get_image('woocommerce_single')); ?><span class="media-badge"><?php esc_html_e('SELEÇÃO CREMENI', 'cremeni-store'); ?></span></div>
          <div class="cremeni-product-detail__content">
            <p class="home-kicker"><?php esc_html_e('PRODUTO CURADO', 'cremeni-store'); ?></p>
            <h1><?php echo esc_html($product->get_name()); ?></h1>
            <?php if ($product->get_short_description()) : ?><div class="cremeni-product-detail__lead"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></div><?php endif; ?>
            <?php if ($product->get_price_html()) : ?><div class="cremeni-product-detail__price"><?php echo wp_kses_post($product->get_price_html()); ?></div><?php endif; ?>
            <div class="product-trust"><span><?php esc_html_e('✓ Fornecedor validado', 'cremeni-store'); ?></span><span><?php esc_html_e('✓ Curadoria CREMENI', 'cremeni-store'); ?></span><span><?php esc_html_e('✓ Operação nacional', 'cremeni-store'); ?></span></div>
            <div class="cremeni-product-detail__description"><?php echo wp_kses_post(wpautop($product->get_description())); ?></div>
            <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
              <a class="button button--primary" href="<?php echo esc_url($product->add_to_cart_url()); ?>"><?php esc_html_e('Adicionar ao carrinho', 'cremeni-store'); ?></a>
            <?php else : ?>
              <div class="commercial-hold"><strong><?php esc_html_e('Ainda não liberado para compra', 'cremeni-store'); ?></strong><span><?php esc_html_e('Estamos concluindo a validação comercial e logística antes de abrir a venda.', 'cremeni-store'); ?></span></div>
            <?php endif; ?>
          </div>
        </article>
      <?php else : ?>
        <section class="catalog-error"><span>404</span><h1><?php esc_html_e('Produto não encontrado.', 'cremeni-store'); ?></h1><a class="button button--primary" href="<?php echo esc_url(cremeni_store_catalog_url()); ?>"><?php esc_html_e('Voltar à loja', 'cremeni-store'); ?></a></section>
      <?php endif;
  else :
      $tax_query = [];
      $title = __('Loja CREMENI', 'cremeni-store');
      $description = __('Uma vitrine curta, criteriosa e conectada à forma como você vive.', 'cremeni-store');
      if ($catalog && 'all' !== $catalog) {
          $term = get_term_by('slug', $catalog, 'product_cat');
          if ($term instanceof WP_Term) {
              $title = $term->name;
              $description = $term->description ?: __('Produtos escolhidos pela curadoria CREMENI.', 'cremeni-store');
              $tax_query[] = ['taxonomy'=>'product_cat','field'=>'slug','terms'=>[$catalog]];
          }
      }
      $query = new WP_Query(['post_type'=>'product','post_status'=>'publish','posts_per_page'=>24,'orderby'=>'menu_order title','order'=>'ASC','tax_query'=>$tax_query]); ?>
      <header class="cremeni-catalog-head">
        <div><p class="home-kicker"><?php esc_html_e('CURADORIA CREMENI', 'cremeni-store'); ?></p><h1><?php echo esc_html($title); ?></h1><p><?php echo esc_html($description); ?></p></div>
        <div class="catalog-head__mark"><b><?php echo esc_html((string) $query->found_posts); ?></b><span><?php esc_html_e('itens publicados', 'cremeni-store'); ?></span></div>
      </header>
      <nav class="catalog-tabs" aria-label="<?php esc_attr_e('Universos da loja', 'cremeni-store'); ?>"><a href="<?php echo esc_url(cremeni_store_catalog_url()); ?>" class="<?php echo (!$catalog || 'all' === $catalog) ? 'is-active' : ''; ?>"><?php esc_html_e('Tudo', 'cremeni-store'); ?></a><a href="<?php echo esc_url(cremeni_store_product_category_url('esporte')); ?>" class="<?php echo 'esporte' === $catalog ? 'is-active' : ''; ?>"><?php esc_html_e('Esporte', 'cremeni-store'); ?></a><a href="<?php echo esc_url(cremeni_store_product_category_url('pet-mimos')); ?>" class="<?php echo 'pet-mimos' === $catalog ? 'is-active' : ''; ?>"><?php esc_html_e('Pet Mimos', 'cremeni-store'); ?></a></nav>
      <?php if ($query->have_posts()) : ?><div class="cremeni-product-grid">
      <?php while ($query->have_posts()) : $query->the_post(); $product = wc_get_product(get_the_ID()); if (!$product) { continue; } $detail_url = cremeni_store_product_url($product->get_id()); ?>
        <article class="cremeni-product-card">
          <a class="cremeni-product-card__media" href="<?php echo esc_url($detail_url); ?>"><?php echo wp_kses_post($product->get_image('woocommerce_thumbnail')); ?><span><?php esc_html_e('CURADORIA CREMENI', 'cremeni-store'); ?></span></a>
          <div class="cremeni-product-card__body"><small><?php esc_html_e('Produto selecionado', 'cremeni-store'); ?></small><h2><a href="<?php echo esc_url($detail_url); ?>"><?php echo esc_html($product->get_name()); ?></a></h2><?php if ($product->get_price_html()) : ?><div class="cremeni-product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></div><?php endif; ?><a class="product-arrow" href="<?php echo esc_url($detail_url); ?>"><?php esc_html_e('Conhecer produto', 'cremeni-store'); ?> →</a></div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?></div>
      <?php else : ?><div class="cremeni-catalog-empty"><span>CREMENI</span><strong><?php esc_html_e('Curadoria em preparação.', 'cremeni-store'); ?></strong><p><?php esc_html_e('Esta categoria ainda não recebeu um produto que passe por todos os nossos critérios de publicação.', 'cremeni-store'); ?></p><a href="<?php echo esc_url(cremeni_store_catalog_url()); ?>"><?php esc_html_e('Ver o que já está disponível', 'cremeni-store'); ?> →</a></div><?php endif;
  endif; ?>
  </div>
</main>
<?php get_footer();
