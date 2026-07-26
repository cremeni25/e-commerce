<?php
/**
 * Template Name: Modalidades esportivas
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$sports = function_exists('cremeni_store_sports') ? cremeni_store_sports() : [];
?>
<main id="conteudo" class="sports-page">
    <section class="page-hero">
        <div class="cremeni-container">
            <p class="eyebrow"><?php esc_html_e('Compre por modalidade', 'cremeni-store'); ?></p>
            <h1><?php esc_html_e('Encontre produtos alinhados ao seu esporte.', 'cremeni-store'); ?></h1>
            <p><?php esc_html_e('Cada modalidade terá comunicação, curadoria e vitrines próprias, preservando a identidade central da Cremeni Store.', 'cremeni-store'); ?></p>
        </div>
    </section>

    <section class="sports-catalog">
        <div class="cremeni-container sports-catalog__grid">
            <?php foreach ($sports as $slug => $label) : ?>
                <?php
                $term = get_term_by('slug', $slug === 'crossfit' ? 'cross-training' : $slug, 'product_cat');
                $url = $term instanceof WP_Term ? get_term_link($term) : home_url('/categoria-produto/modalidades-esportivas/' . ($slug === 'crossfit' ? 'cross-training' : $slug) . '/');
                ?>
                <article class="sport-card sport-card--<?php echo esc_attr($slug); ?>">
                    <span class="sport-card__code"><?php echo esc_html(strtoupper(substr($slug, 0, 3))); ?></span>
                    <h2><?php echo esc_html($label); ?></h2>
                    <p><?php echo esc_html(sprintf(__('Suplementos, vestuário, acessórios e equipamentos selecionados para %s.', 'cremeni-store'), mb_strtolower((string) $label))); ?></p>
                    <?php if (! is_wp_error($url)) : ?>
                        <a class="button button--secondary" href="<?php echo esc_url((string) $url); ?>">
                            <?php esc_html_e('Explorar modalidade', 'cremeni-store'); ?>
                        </a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php
get_footer();
