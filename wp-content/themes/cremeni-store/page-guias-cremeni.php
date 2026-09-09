<?php
/** Página Guias CREMENI. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
get_header();
$guides=[
['colecao'=>'CREMENI JUNTOS','titulo'=>'Caminhar Juntos','texto'=>'Movimento, rotina e conexão para você e seu cão. Primeiro guia editorial CREMENI.'],
['colecao'=>'CREMENI CORPO','titulo'=>'Mobilidade no dia a dia','texto'=>'Práticas curtas para incorporar movimento à rotina com simplicidade.'],
['colecao'=>'CREMENI MENTE','titulo'=>'Movimento como hábito','texto'=>'Organização e constância para transformar intenção em rotina.'],
['colecao'=>'CREMENI PET','titulo'=>'Enriquecimento da rotina','texto'=>'Ideias de convivência, brincadeira e estímulo para cães e gatos.'],
];
?>
<main id="conteudo">
<section class="page-hero"><div class="cremeni-container"><p class="eyebrow">GUIAS CREMENI</p><h1>Conteúdo para continuar em movimento.</h1><p>Guias curtos, práticos e responsáveis para corpo, mente, rotina e convivência. A CREMENI não termina na compra.</p></div></section>
<section class="sports-catalog"><div class="cremeni-container"><div class="section-heading"><p class="eyebrow">BIBLIOTECA EM CONSTRUÇÃO</p><h2>Primeiras coleções editoriais.</h2><p>Os conteúdos serão publicados por versão e revisão. Temas clínicos ou veterinários exigirão revisão profissional antes da publicação.</p></div><div class="sports-catalog__grid"><?php foreach($guides as $guide): ?><article class="sport-card"><span class="sport-card__code"><?php echo esc_html($guide['colecao']); ?></span><h2><?php echo esc_html($guide['titulo']); ?></h2><p><?php echo esc_html($guide['texto']); ?></p><span class="cremeni-product-status">Em preparação editorial</span></article><?php endforeach; ?></div></div></section>
</main><?php get_footer();
