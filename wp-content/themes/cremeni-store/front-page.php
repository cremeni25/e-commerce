<?php
/**
 * Home CREMENI V2 — materializa a matriz comercial validada nas execuções 001–006.
 *
 * @package CremeniStore
 */

if (! defined('ABSPATH')) {
    exit;
}

$universes = cremeni_store_product_categories();
$sports = cremeni_store_sports();
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/loja/');
$cremeni_official_logo = require get_template_directory() . '/assets/cremeni-official-logo-data.php';

$pilot_products = [
    [
        'name' => 'Raquete Beach Tennis Personalizada',
        'sport' => 'Beach Tennis',
        'detail' => 'Personalização com nome, marca, logotipo, foto, arte ou frase.',
    ],
    [
        'name' => 'Colchonete Academia, Pilates & Yoga Personalizado',
        'sport' => 'Yoga & Pilates · Funcional',
        'detail' => 'Produto âncora para treino, mobilidade e exercícios no solo.',
    ],
    [
        'name' => 'Kit Agilidade — 8 Cones + 4 Bastões',
        'sport' => 'Futebol · Futsal · Funcional',
        'detail' => 'Treino de agilidade, coordenação e deslocamento.',
    ],
    [
        'name' => 'Prancha Aprendiz Natação Personalizada',
        'sport' => 'Natação',
        'detail' => 'Produto de aprendizagem com possibilidade de personalização.',
    ],
];

get_header();
?>
<main id="conteudo" class="cremeni-v2-home">
    <section class="hero hero--brand-first">
        <div class="cremeni-container hero__grid">
            <div class="hero__content">
                <p class="eyebrow"><?php esc_html_e('CREMENI • ESPORTE • PET • CONTEÚDO', 'cremeni-store'); ?></p>
                <h1><?php esc_html_e('Movimento para você. Cuidado para quem faz parte da sua vida.', 'cremeni-store'); ?></h1>
                <p><?php esc_html_e('A CREMENI conecta produtos esportivos selecionados, pequenos mimos para pets e conteúdo próprio em uma jornada única, construída para evoluir sem estoque próprio.', 'cremeni-store'); ?></p>
                <div class="hero__actions">
                    <a class="button button--primary" href="#esporte-cremeni"><?php esc_html_e('Conhecer CREMENI Esporte', 'cremeni-store'); ?></a>
                    <a class="button button--secondary" href="#universos-cremeni"><?php esc_html_e('Ver os três universos', 'cremeni-store'); ?></a>
                </div>
                <ul class="hero__proof" aria-label="<?php esc_attr_e('Modelo operacional CREMENI', 'cremeni-store'); ?>">
                    <li><?php esc_html_e('Dropshipping nacional', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Governança por SKU', 'cremeni-store'); ?></li>
                    <li><?php esc_html_e('Conteúdo próprio CREMENI', 'cremeni-store'); ?></li>
                </ul>
            </div>
            <div class="hero__visual" aria-hidden="true">
                <div class="hero__halo"></div>
                <img class="hero__official-wordmark" src="<?php echo esc_attr($cremeni_official_logo); ?>" alt="" width="1200" height="335" loading="eager" decoding="async">
                <div class="hero__product-card">
                    <span><?php esc_html_e('ESPORTE → PET MIMOS → GUIAS CREMENI', 'cremeni-store'); ?></span>
                    <strong><?php esc_html_e('Uma compra que pode continuar como relacionamento.', 'cremeni-store'); ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <div class="cremeni-container trust-strip__grid">
            <div><strong><?php esc_html_e('Sem estoque próprio', 'cremeni-store'); ?></strong><span><?php esc_html_e('Produtos físicos entram somente com condição operacional de parceiro validada.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Preço com regra', 'cremeni-store'); ?></strong><span><?php esc_html_e('Nenhum SKU esportivo entra abaixo do piso comercial definido pela CREMENI.', 'cremeni-store'); ?></span></div>
            <div><strong><?php esc_html_e('Publicação bloqueável', 'cremeni-store'); ?></strong><span><?php esc_html_e('Estoque, drop, custo e logística precisam estar vigentes antes da liberação.', 'cremeni-store'); ?></span></div>
        </div>
    </section>

    <section id="universos-cremeni" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading section-heading--split">
                <div>
                    <p class="eyebrow"><?php esc_html_e('Universos CREMENI', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Três frentes com papéis comerciais diferentes.', 'cremeni-store'); ?></h2>
                </div>
            </div>
            <div class="store-categories__grid">
                <?php $index = 1; ?>
                <?php foreach ($universes as $slug => $universe) : ?>
                    <article class="category-card">
                        <span class="category-card__index"><?php echo esc_html(str_pad((string) $index, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3><?php echo esc_html($universe['label']); ?></h3>
                        <p><?php echo esc_html($universe['description']); ?></p>
                        <span class="category-card__action"><?php echo 'esporte' === $slug ? esc_html__('Compra principal', 'cremeni-store') : ('pet-mimos' === $slug ? esc_html__('Cross-sell e recorrência', 'cremeni-store') : esc_html__('Relacionamento e retenção', 'cremeni-store')); ?></span>
                    </article>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="esporte-cremeni" class="featured-products">
        <div class="cremeni-container">
            <div class="section-heading section-heading--split">
                <div>
                    <p class="eyebrow"><?php esc_html_e('CREMENI ESPORTE • CATÁLOGO PILOTO', 'cremeni-store'); ?></p>
                    <h2><?php esc_html_e('Produtos já identificados com dropshipping confirmado.', 'cremeni-store'); ?></h2>
                    <p><?php esc_html_e('Estes itens fazem parte do staging comercial. A publicação para venda continua bloqueada até preço, validade operacional e demais critérios serem liberados pela governança.', 'cremeni-store'); ?></p>
                </div>
            </div>
            <div class="store-categories__grid">
                <?php foreach ($pilot_products as $product) : ?>
                    <article class="category-card cremeni-pilot-product">
                        <span class="category-card__index"><?php echo esc_html($product['sport']); ?></span>
                        <h3><?php echo esc_html($product['name']); ?></h3>
                        <p><?php echo esc_html($product['detail']); ?></p>
                        <span class="category-card__action"><?php esc_html_e('DROP CONFIRMADO • EM VALIDAÇÃO', 'cremeni-store'); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="esportes" class="sports-section">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('Modalidades definidas', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('A curadoria esportiva nasce por modalidade e produto específico.', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('A modalidade não é um cartão vazio: cada frente evolui somente quando houver produto, fornecedor, condição de drop e regra comercial comprovados.', 'cremeni-store'); ?></p>
            </div>
            <div class="sports-grid">
                <?php foreach ($sports as $slug => $sport) : ?>
                    <div class="sport-card">
                        <span class="sport-card__mark"><?php echo esc_html(mb_strtoupper(mb_substr($sport, 0, 1))); ?></span>
                        <strong><?php echo esc_html($sport); ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="pet-mimos" class="store-categories">
        <div class="cremeni-container">
            <div class="section-heading">
                <p class="eyebrow"><?php esc_html_e('CREMENI PET MIMOS', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Um complemento afetivo pensado para o momento da compra.', 'cremeni-store'); ?></h2>
                <p><?php esc_html_e('A jornada comercial já prevê relacionar um produto esportivo a Pet Mimos elegíveis. No carrinho, a experiência pode perguntar: “Um mimo para quem faz parte da sua rotina?”. Somente itens aprovados pela governança aparecem.', 'cremeni-store'); ?></p>
            </div>
            <div class="store-categories__grid">
                <article class="category-card"><span class="category-card__index">01</span><h3><?php esc_html_e('Pet Mimos', 'cremeni-store'); ?></h3><p><?php esc_html_e('Itens leves, de baixa fricção e contribuição incremental positiva.', 'cremeni-store'); ?></p><span class="category-card__action"><?php esc_html_e('Cross-sell', 'cremeni-store'); ?></span></article>
                <article class="category-card"><span class="category-card__index">02</span><h3><?php esc_html_e('Pet Premium', 'cremeni-store'); ?></h3><p><?php esc_html_e('Vertical prevista para produtos de maior valor, com logística e contribuição próprias.', 'cremeni-store'); ?></p><span class="category-card__action"><?php esc_html_e('Expansão controlada', 'cremeni-store'); ?></span></article>
                <article class="category-card"><span class="category-card__index">03</span><h3><?php esc_html_e('Jornada integrada', 'cremeni-store'); ?></h3><p><?php esc_html_e('Esporte → Pet Mimos → conteúdo relacionado, sem obrigar o cliente a navegar por lojas separadas.', 'cremeni-store'); ?></p><span class="category-card__action"><?php esc_html_e('Recorrência', 'cremeni-store'); ?></span></article>
            </div>
        </div>
    </section>

    <section id="guias-cremeni" class="brand-story">
        <div class="cremeni-container brand-story__grid">
            <div>
                <p class="eyebrow"><?php esc_html_e('GUIAS CREMENI', 'cremeni-store'); ?></p>
                <h2><?php esc_html_e('Conteúdo próprio que continua a relação depois da compra.', 'cremeni-store'); ?></h2>
            </div>
            <div>
                <p><?php esc_html_e('A biblioteca editorial já está estruturada em CREMENI Corpo, Mente, Pet, Juntos e Raças, com controle de versão, status editorial, fontes, revisão profissional quando necessária e modalidades de distribuição gratuita, associada à compra ou premium futura.', 'cremeni-store'); ?></p>
                <p><strong><?php esc_html_e('Guia piloto 001:', 'cremeni-store'); ?></strong> <?php esc_html_e('Caminhar Juntos — movimento, rotina e companhia.', 'cremeni-store'); ?></p>
            </div>
        </div>
    </section>

    <?php if (class_exists('WooCommerce')) : ?>
        <section class="featured-products">
            <div class="cremeni-container">
                <div class="section-heading section-heading--split">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('LOJA CREMENI', 'cremeni-store'); ?></p>
                        <h2><?php esc_html_e('Produtos liberados para venda', 'cremeni-store'); ?></h2>
                    </div>
                    <a class="text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ir para a loja', 'cremeni-store'); ?></a>
                </div>
                <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer();
