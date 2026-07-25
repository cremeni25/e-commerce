<?php
/**
 * Contêiner principal para páginas WooCommerce.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo" class="commerce-page">
    <div class="cremeni-container commerce-page__inner">
        <?php woocommerce_content(); ?>
    </div>
</main>
<?php
get_footer();
