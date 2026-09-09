<?php
/**
 * Template Name: Modalidades esportivas
 * @package CremeniStore
 */
if (! defined('ABSPATH')) { exit; }
get_header();
$sports = function_exists('cremeni_store_sports') ? cremeni_store_sports() : [];
?>
<main id="conteudo" class="sports-page">
    <section class="page-hero">
        <div class="cremeni-container">
            <p class="eyebrow"><?php esc_html_e('CREMENI Esporte', 'cremeni-store'); ?></p>
            <h1><?php esc_html_e('Encontre sua modalidade.', 'cremeni-store'); ?></h1>
            <p><?php esc_html_e('Uma seleção objetiva de produtos esportivos, organizada por prática e função — sem catálogo inflado e sem itens sem propósito.', 'cremeni-store'); ?></p>
        </div>
    </section>

    <section class="sports-catalog">
        <div class="cremeni-container sports-catalog__grid">
            <?php foreach ($sports as $slug => $label) : ?>
                <article class="sport-card sport-card--<?php echo esc_attr($slug); ?>">
                    <span class="sport-card__code"><?php echo esc_html(strtoupper(substr($slug, 0, 3))); ?></span>
                    <h2><?php echo esc_html($label); ?></h2>
                    <p><?php esc_html_e('Produtos esportivos selecionados por função, qualidade e condição comercial.', 'cremeni-store'); ?></p>
                    <a class="button button--secondary" href="<?php echo esc_url(cremeni_store_product_category_url($slug)); ?>"><?php esc_html_e('Explorar modalidade', 'cremeni-store'); ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php get_footer();
