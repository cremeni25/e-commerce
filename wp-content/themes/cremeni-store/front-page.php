<?php
/** Home premium CREMENI. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
$sports = cremeni_store_sports();
$shop_url = cremeni_store_catalog_url();
get_header();
?>
<main id="conteudo" class="cremeni-home">
<section class="home-hero">
  <div class="cremeni-container home-hero__grid">
    <div class="home-hero__copy">
      <span class="home-kicker"><?php esc_html_e('CURADORIA PARA UMA VIDA EM MOVIMENTO', 'cremeni-store'); ?></span>
      <h1><?php esc_html_e('Mais do que comprar. Escolher melhor.', 'cremeni-store'); ?></h1>
      <p><?php esc_html_e('Esporte, bem-estar, cuidado com quem está ao seu lado e conteúdo útil reunidos em uma experiência CREMENI.', 'cremeni-store'); ?></p>
      <div class="home-hero__actions">
        <a class="button button--primary" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Entrar na loja', 'cremeni-store'); ?></a>
        <a class="button button--ghost" href="#descobrir"><?php esc_html_e('Descobrir por interesse', 'cremeni-store'); ?></a>
      </div>
      <div class="home-hero__signals">
        <span><b>01</b><?php esc_html_e('Produtos selecionados', 'cremeni-store'); ?></span>
        <span><b>02</b><?php esc_html_e('Operação nacional', 'cremeni-store'); ?></span>
        <span><b>03</b><?php esc_html_e('Conteúdo próprio', 'cremeni-store'); ?></span>
      </div>
    </div>
    <aside class="home-hero__commerce" aria-label="<?php esc_attr_e('Acessos rápidos CREMENI', 'cremeni-store'); ?>">
      <div class="home-hero__commerce-brand"><?php require get_template_directory() . '/assets/cremeni-wordmark.php'; ?></div>
      <span class="home-kicker"><?php esc_html_e('ENCONTRE SEU CAMINHO', 'cremeni-store'); ?></span>
      <h2><?php esc_html_e('O que você procura hoje?', 'cremeni-store'); ?></h2>
      <nav class="home-hero__quicklinks">
        <a href="<?php echo esc_url(cremeni_store_product_category_url('esporte')); ?>"><strong><?php esc_html_e('Esporte', 'cremeni-store'); ?></strong><span><?php esc_html_e('Treino e modalidades', 'cremeni-store'); ?></span><b>→</b></a>
        <a href="<?php echo esc_url(cremeni_store_product_category_url('pet-mimos')); ?>"><strong><?php esc_html_e('Pet Mimos', 'cremeni-store'); ?></strong><span><?php esc_html_e('Cuidado e convivência', 'cremeni-store'); ?></span><b>→</b></a>
        <a href="<?php echo esc_url(cremeni_store_page_url('guias-cremeni')); ?>"><strong><?php esc_html_e('Guias CREMENI', 'cremeni-store'); ?></strong><span><?php esc_html_e('Conteúdo e rotina', 'cremeni-store'); ?></span><b>→</b></a>
      </nav>
    </aside>
  </div>
</section>

<section id="descobrir" class="discover-strip">
  <div class="cremeni-container">
    <div class="section-intro section-intro--row">
      <div><span class="home-kicker"><?php esc_html_e('COMO VOCÊ QUER VIVER HOJE?', 'cremeni-store'); ?></span><h2><?php esc_html_e('Escolha pelo momento, não pela prateleira.', 'cremeni-store'); ?></h2></div>
      <p><?php esc_html_e('A CREMENI organiza produtos e conteúdo pela intenção de uso. Menos catálogo genérico. Mais contexto.', 'cremeni-store'); ?></p>
    </div>
    <div class="intent-grid">
      <a class="intent-card intent-card--sport" href="<?php echo esc_url(cremeni_store_product_category_url('esporte')); ?>"><span>01</span><strong><?php esc_html_e('Quero me movimentar', 'cremeni-store'); ?></strong><small><?php esc_html_e('Treino, prática esportiva e rotina ativa.', 'cremeni-store'); ?></small><em><?php esc_html_e('Explorar esporte', 'cremeni-store'); ?> →</em></a>
      <a class="intent-card intent-card--pet" href="<?php echo esc_url(cremeni_store_product_category_url('pet-mimos')); ?>"><span>02</span><strong><?php esc_html_e('Quero cuidar de quem está comigo', 'cremeni-store'); ?></strong><small><?php esc_html_e('Mimos, passeio e convivência.', 'cremeni-store'); ?></small><em><?php esc_html_e('Explorar Pet Mimos', 'cremeni-store'); ?> →</em></a>
      <a class="intent-card intent-card--guide" href="<?php echo esc_url(cremeni_store_page_url('guias-cremeni')); ?>"><span>03</span><strong><?php esc_html_e('Quero aprender algo útil', 'cremeni-store'); ?></strong><small><?php esc_html_e('Guias curtos para corpo, mente e rotina.', 'cremeni-store'); ?></small><em><?php esc_html_e('Abrir Guias CREMENI', 'cremeni-store'); ?> →</em></a>
    </div>
  </div>
</section>

<?php if (class_exists('WooCommerce')) : ?>
<section class="home-products">
  <div class="cremeni-container">
    <div class="section-intro section-intro--row">
      <div><span class="home-kicker"><?php esc_html_e('SELEÇÃO CREMENI', 'cremeni-store'); ?></span><h2><?php esc_html_e('Poucos produtos. Bons motivos para estarem aqui.', 'cremeni-store'); ?></h2></div>
      <a class="section-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Ver loja completa', 'cremeni-store'); ?> →</a>
    </div>
    <div class="home-products__rail"><?php echo do_shortcode('[products limit="4" columns="4" category="esporte" orderby="date" order="DESC"]'); ?></div>
    <p class="home-products__note"><?php esc_html_e('A venda só é liberada quando fornecedor, estoque, custo, preço e condição operacional estiverem validados.', 'cremeni-store'); ?></p>
  </div>
</section>
<?php endif; ?>

<section class="home-sports" id="esportes">
  <div class="cremeni-container">
    <div class="section-intro section-intro--row">
      <div><span class="home-kicker"><?php esc_html_e('CREMENI ESPORTE', 'cremeni-store'); ?></span><h2><?php esc_html_e('Sua modalidade sem ruído.', 'cremeni-store'); ?></h2></div>
      <p><?php esc_html_e('Entre direto no universo que faz sentido para você.', 'cremeni-store'); ?></p>
    </div>
    <div class="sport-chips"><?php foreach ($sports as $slug => $sport) : ?><a href="<?php echo esc_url(cremeni_store_product_category_url($slug)); ?>"><span><?php echo esc_html(cremeni_store_initial($sport)); ?></span><?php echo esc_html($sport); ?></a><?php endforeach; ?></div>
  </div>
</section>

<section class="home-editorial">
  <div class="cremeni-container home-editorial__grid">
    <div class="home-editorial__lead">
      <span class="home-kicker"><?php esc_html_e('GUIAS CREMENI', 'cremeni-store'); ?></span>
      <h2><?php esc_html_e('A compra termina. A relação continua.', 'cremeni-store'); ?></h2>
      <p><?php esc_html_e('Conteúdo próprio para ajudar a transformar produto em uso, uso em hábito e hábito em qualidade de vida.', 'cremeni-store'); ?></p>
      <a class="button button--yellow" href="<?php echo esc_url(cremeni_store_page_url('guias-cremeni')); ?>"><?php esc_html_e('Conhecer os guias', 'cremeni-store'); ?></a>
    </div>
    <div class="home-editorial__cards">
      <article><span><?php esc_html_e('CORPO', 'cremeni-store'); ?></span><strong><?php esc_html_e('Movimento, mobilidade e recuperação.', 'cremeni-store'); ?></strong></article>
      <article><span><?php esc_html_e('MENTE', 'cremeni-store'); ?></span><strong><?php esc_html_e('Foco, disciplina e rotina.', 'cremeni-store'); ?></strong></article>
      <article><span><?php esc_html_e('JUNTOS', 'cremeni-store'); ?></span><strong><?php esc_html_e('Vida ativa com quem faz parte da sua história.', 'cremeni-store'); ?></strong></article>
    </div>
  </div>
</section>

<section class="home-principles"><div class="cremeni-container home-principles__grid"><strong><?php esc_html_e('Por que comprar na CREMENI?', 'cremeni-store'); ?></strong><span><?php esc_html_e('Curadoria antes de volume', 'cremeni-store'); ?></span><span><?php esc_html_e('Preço antes de impulso', 'cremeni-store'); ?></span><span><?php esc_html_e('Conteúdo antes de abandono', 'cremeni-store'); ?></span><span><?php esc_html_e('Relacionamento depois da compra', 'cremeni-store'); ?></span></div></section>
</main>
<?php get_footer();
