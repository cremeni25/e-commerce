<?php
/**
 * Página inicial da loja Cremeni.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="conteudo">
    <section class="hero">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('Ecossistema fitness e performance', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Tudo para treinar, evoluir e viver em movimento.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('Suplementos, alimentos funcionais, roupas, acessórios e equipamentos para atletas e pessoas com rotina ativa.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <?php if (function_exists('wc_get_page_permalink')) : ?>
                        <a class="button button--primary" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                            <?php esc_html_e('Explorar a loja', 'cremeni-store'); ?>
                        </a>
                    <?php endif; ?>
                    <a class="button button--secondary" href="#categorias">
                        <?php esc_html_e('Ver categorias', 'cremeni-store'); ?>
                    </a>
                </div>
            </div>

            <div class="hero__visual" aria-hidden="true">
                <div class="hero__product-card">
                    <span><?php esc_html_e('CREMENI PERFORMANCE', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Nutrição, vestuário e equipamentos', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section id="categorias" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('Compre por categoria', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Um único destino para sua rotina fitness.', 'cremeni-store'); ?></h2>
            </div>

            <div class="store-categories__grid">
                <article class="category-card">
                    <span class="category-card__index">01</span>
                    <h3><?php esc_html_e('Suplementos', 'cremeni-store'); ?></h3>
                    <p><?php esc_html_e('Proteínas, creatina, energia, recuperação e produtos funcionais.', 'cremeni-store'); ?></p>
                </article>
                <article class="category-card">
                    <span class="category-card__index">02</span>
                    <h3><?php esc_html_e('Alimentos fitness', 'cremeni-store'); ?></h3>
                    <p><?php esc_html_e('Soluções práticas para consumo antes, durante e depois do treino.', 'cremeni-store'); ?></p>
                </article>
                <article class="category-card">
                    <span class="category-card__index">03</span>
                    <h3><?php esc_html_e('Roupas e acessórios', 'cremeni-store'); ?></h3>
                    <p><?php esc_html_e('Vestuário e itens para treino, competição e estilo de vida ativo.', 'cremeni-store'); ?></p>
                </article>
                <article class="category-card">
                    <span class="category-card__index">04</span>
                    <h3><?php esc_html_e('Equipamentos', 'cremeni-store'); ?></h3>
                    <p><?php esc_html_e('Produtos para treino funcional, musculação, corrida e outras modalidades.', 'cremeni-store'); ?></p>
                </article>
                <article class="category-card category-card--future">
                    <span class="category-card__index">05</span>
                    <h3><?php esc_html_e('Conteúdo e infoprodutos', 'cremeni-store'); ?></h3>
                    <p><?php esc_html_e('Estrutura preparada para cursos, programas, guias e conteúdos digitais do setor.', 'cremeni-store'); ?></p>
                </article>
            </div>
        </div>
    </section>

    <section id="diferenciais" class="benefits">
        <div class="cremeni-container benefits__grid">
            <article class="benefit-card">
                <h2><?php esc_html_e('Curadoria', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Seleção orientada a saúde, performance e confiança.', 'cremeni-store'); ?></p>
            </article>
            <article class="benefit-card">
                <h2><?php esc_html_e('Praticidade', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Produtos físicos e digitais reunidos em uma experiência simples.', 'cremeni-store'); ?></p>
            </article>
            <article class="benefit-card">
                <h2><?php esc_html_e('Evolução contínua', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('Plataforma preparada para ampliar marcas, linhas e formatos de venda.', 'cremeni-store'); ?></p>
            </article>
        </div>
    </section>

    <?php if (class_exists('WooCommerce')) : ?>
        <section class="featured-products">
            <div class="cremeni-container">
                <div class="section-heading">
                    <p class="eyebrow"><?php esc_html_e('Loja Cremeni', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Produtos em destaque', 'cremeni-store'); ?></h2>
                </div>
                <?php echo do_shortcode('[products limit="4" columns="4" visibility="featured"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="brand-story">
        <div class="cremeni-container brand-story__content">
            <p class="eyebrow"><?php esc_html_e('CREMENI', 'cremeni-store'); ?></p>
            <h2><?php esc_html_e('Uma plataforma completa para o universo fitness.', 'cremeni-store'); ?></h2>
            <p><?php esc_html_e('A loja nasce com foco em suplementos e alimentos funcionais e evolui para integrar vestuário, equipamentos, acessórios e produtos digitais.', 'cremeni-store'); ?></p>
        </div>
    </section>
</main>
<?php
get_footer();
