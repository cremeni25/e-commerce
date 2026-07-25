<?php
/**
 * Template principal do tema.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo" class="cremeni-container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('Nenhum conteúdo encontrado.', 'cremeni-store'); ?></p>
    <?php endif; ?>
</main>
<?php
get_footer();
