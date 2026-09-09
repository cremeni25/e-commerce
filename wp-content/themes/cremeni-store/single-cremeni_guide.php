<?php
/**
 * Página individual de Guia Cremeni.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $subtitle = (string) get_post_meta(get_the_ID(), '_cremeni_guide_subtitle', true);
        $version = (string) get_post_meta(get_the_ID(), '_cremeni_guide_version', true);
        $distribution = (string) get_post_meta(get_the_ID(), '_cremeni_guide_distribution', true);
        $disclaimer = (string) get_post_meta(get_the_ID(), '_cremeni_guide_disclaimer', true);
        $pdf_url = function_exists('cremeni_guides_pdf_url') ? cremeni_guides_pdf_url(get_the_ID()) : '';
        ?>
        <article class="brand-story">
            <div class="cremeni-container">
                <p class="eyebrow"><?php esc_html_e('GUIAS CREMENI', 'cremeni-store'); ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if ($subtitle !== '') : ?><p><?php echo esc_html($subtitle); ?></p><?php endif; ?>
                <p><?php echo esc_html(sprintf(__('Versão %s', 'cremeni-store'), $version !== '' ? $version : '1.0')); ?> · <?php echo esc_html($distribution === 'associado_compra' ? __('Benefício associado à compra', 'cremeni-store') : ($distribution === 'premium_futuro' ? __('Premium futuro', 'cremeni-store') : __('Conteúdo gratuito', 'cremeni-store'))); ?></p>
            </div>
        </article>

        <section class="store-categories">
            <div class="cremeni-container">
                <div class="cremeni-guide__content">
                    <?php the_content(); ?>
                </div>

                <?php if ($pdf_url !== '') : ?>
                    <p><a class="button button--primary" href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Abrir PDF do Guia', 'cremeni-store'); ?></a></p>
                <?php endif; ?>

                <?php if ($disclaimer !== '') : ?>
                    <aside class="cremeni-guide__disclaimer">
                        <strong><?php esc_html_e('Importante', 'cremeni-store'); ?></strong>
                        <p><?php echo esc_html($disclaimer); ?></p>
                    </aside>
                <?php endif; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer();
