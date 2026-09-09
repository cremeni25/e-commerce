<?php
/**
 * Arquivo da biblioteca Guias Cremeni.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo">
    <section class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('GUIAS CREMENI', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Conteúdo curto para uma vida mais ativa, equilibrada e conectada.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Corpo, mente, pet e rotina em materiais próprios da Cremeni, organizados para leitura simples e aplicação no dia a dia.', 'cremeni-store'); ?></p>
            </div>

            <?php if (have_posts()) : ?>
                <div class="store-categories__grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $subtitle = (string) get_post_meta(get_the_ID(), '_cremeni_guide_subtitle', true);
                        $distribution = (string) get_post_meta(get_the_ID(), '_cremeni_guide_distribution', true);
                        $terms = get_the_terms(get_the_ID(), 'cremeni_guide_collection');
                        $collection = is_array($terms) && $terms ? $terms[0]->name : __('Guia Cremeni', 'cremeni-store');
                        ?>
                        <article class="category-card">
                            <span class="category-card__index"><?php echo esc_html($collection); ?></span>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php if ($subtitle !== '') : ?><p><?php echo esc_html($subtitle); ?></p><?php else : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
                            <span class="category-card__action"><?php echo esc_html($distribution === 'premium_futuro' ? __('Premium futuro', 'cremeni-store') : __('Ler guia', 'cremeni-store')); ?></span>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p><?php esc_html_e('Os primeiros Guias Cremeni estão em preparação editorial.', 'cremeni-store'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
