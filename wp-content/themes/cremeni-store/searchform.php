<?php
/**
 * Busca de produtos da Cremeni Store.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<form role="search" method="get" class="product-search" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="cremeni-product-search">
        <?php esc_html_e('Buscar produtos', 'cremeni-store'); ?>
    </label>
    <input
        id="cremeni-product-search"
        class="product-search__field"
        type="search"
        name="s"
        value="<?php echo esc_attr(get_search_query()); ?>"
        placeholder="<?php esc_attr_e('Buscar suplementos, roupas, equipamentos...', 'cremeni-store'); ?>"
        autocomplete="off"
    >
    <input type="hidden" name="post_type" value="product">
    <button class="product-search__submit" type="submit">
        <span aria-hidden="true">⌕</span>
        <span class="screen-reader-text"><?php esc_html_e('Pesquisar', 'cremeni-store'); ?></span>
    </button>
</form>
