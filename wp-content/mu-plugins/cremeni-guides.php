<?php
/**
 * Plugin Name: Guias Cremeni
 * Description: Biblioteca editorial proprietária da CREMENI para corpo, mente, pet, vínculo e raças.
 * Version: 0.2.0
 * Author: Cremeni
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function cremeni_guides_register_content_model(): void
{
    register_post_type('cremeni_guide', [
        'labels' => [
            'name'          => 'Guias Cremeni',
            'singular_name' => 'Guia Cremeni',
            'add_new_item'  => 'Adicionar Guia Cremeni',
            'edit_item'     => 'Editar Guia Cremeni',
            'view_item'     => 'Ver Guia Cremeni',
            'search_items'  => 'Buscar Guias Cremeni',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'guias'],
        'menu_icon'    => 'dashicons-book-alt',
        'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions'],
    ]);

    register_taxonomy('cremeni_guide_collection', ['cremeni_guide'], [
        'labels' => [
            'name'          => 'Coleções',
            'singular_name' => 'Coleção',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'guias/colecao'],
    ]);
}
add_action('init', 'cremeni_guides_register_content_model');

function cremeni_guides_seed_collections(): void
{
    if (! taxonomy_exists('cremeni_guide_collection')) {
        return;
    }

    $collections = [
        ['CREMENI Corpo', 'corpo', 'Movimento, mobilidade, recuperação, sono e hábitos físicos cotidianos.'],
        ['CREMENI Mente', 'mente', 'Rotina, foco, presença, organização e bem-estar cotidiano.'],
        ['CREMENI Pet', 'pet', 'Cuidados gerais, brincadeiras, passeio, segurança e enriquecimento.'],
        ['CREMENI Juntos', 'juntos', 'Conteúdos que conectam corpo, mente, vida ativa e companhia do pet.'],
        ['CREMENI Raças', 'racas', 'Guias rápidos de características gerais, rotina, atividade e cuidados por raça ou perfil.'],
    ];

    foreach ($collections as [$name, $slug, $description]) {
        if (! term_exists($slug, 'cremeni_guide_collection')) {
            wp_insert_term($name, 'cremeni_guide_collection', [
                'slug'        => $slug,
                'description' => $description,
            ]);
        }
    }
}
add_action('init', 'cremeni_guides_seed_collections', 20);

function cremeni_guides_distribution_modes(): array
{
    return [
        'gratuito'         => 'Gratuito',
        'associado_compra' => 'Benefício associado à compra',
        'premium_futuro'   => 'Premium futuro',
    ];
}

function cremeni_guides_editorial_statuses(): array
{
    return [
        'rascunho'            => 'Rascunho',
        'pesquisa'             => 'Pesquisa em andamento',
        'revisao_editorial'    => 'Revisão editorial',
        'revisao_profissional' => 'Revisão profissional',
        'aprovado'             => 'Aprovado',
    ];
}

function cremeni_guides_meta_box(): void
{
    add_meta_box('cremeni-guide-governance', 'Governança Editorial CREMENI', 'cremeni_guides_render_meta_box', 'cremeni_guide', 'normal', 'high');
}
add_action('add_meta_boxes_cremeni_guide', 'cremeni_guides_meta_box');

function cremeni_guides_render_meta_box(WP_Post $post): void
{
    wp_nonce_field('cremeni_guides_save', 'cremeni_guides_nonce');
    $get = static fn(string $key): string => (string) get_post_meta($post->ID, $key, true);
    ?>
    <table class="form-table" role="presentation">
        <tr><th><label for="cremeni_guide_subtitle">Subtítulo</label></th><td><input class="large-text" id="cremeni_guide_subtitle" name="cremeni_guide_subtitle" value="<?php echo esc_attr($get('_cremeni_guide_subtitle')); ?>"></td></tr>
        <tr><th><label for="cremeni_guide_version">Versão</label></th><td><input id="cremeni_guide_version" name="cremeni_guide_version" value="<?php echo esc_attr($get('_cremeni_guide_version') ?: '1.0'); ?>"></td></tr>
        <tr><th><label for="cremeni_guide_audience">Público</label></th><td><input class="large-text" id="cremeni_guide_audience" name="cremeni_guide_audience" value="<?php echo esc_attr($get('_cremeni_guide_audience')); ?>"></td></tr>
        <tr><th><label for="cremeni_guide_sensitivity">Sensibilidade</label></th><td><select id="cremeni_guide_sensitivity" name="cremeni_guide_sensitivity"><option value="1" <?php selected($get('_cremeni_guide_sensitivity') ?: '1', '1'); ?>>Nível 1 — cotidiano e boas práticas</option><option value="2" <?php selected($get('_cremeni_guide_sensitivity'), '2'); ?>>Nível 2 — saúde e bem-estar</option><option value="3" <?php selected($get('_cremeni_guide_sensitivity'), '3'); ?>>Nível 3 — clínico/veterinário</option></select></td></tr>
        <tr><th><label for="cremeni_guide_editorial_status">Status editorial</label></th><td><select id="cremeni_guide_editorial_status" name="cremeni_guide_editorial_status"><?php foreach (cremeni_guides_editorial_statuses() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_guide_editorial_status') ?: 'rascunho', $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_guide_distribution">Distribuição</label></th><td><select id="cremeni_guide_distribution" name="cremeni_guide_distribution"><?php foreach (cremeni_guides_distribution_modes() as $value => $label) : ?><option value="<?php echo esc_attr($value); ?>" <?php selected($get('_cremeni_guide_distribution') ?: 'gratuito', $value); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
        <tr><th><label for="cremeni_guide_editor">Responsável editorial</label></th><td><input class="regular-text" id="cremeni_guide_editor" name="cremeni_guide_editor" value="<?php echo esc_attr($get('_cremeni_guide_editor')); ?>"></td></tr>
        <tr><th><label for="cremeni_guide_professional_reviewer">Revisor profissional</label></th><td><input class="regular-text" id="cremeni_guide_professional_reviewer" name="cremeni_guide_professional_reviewer" value="<?php echo esc_attr($get('_cremeni_guide_professional_reviewer')); ?>" placeholder="Obrigatório para conteúdo clínico antes da publicação"></td></tr>
        <tr><th><label for="cremeni_guide_review_date">Data de revisão</label></th><td><input type="date" id="cremeni_guide_review_date" name="cremeni_guide_review_date" value="<?php echo esc_attr($get('_cremeni_guide_review_date')); ?>"></td></tr>
        <tr><th><label for="cremeni_guide_sources">Fontes</label></th><td><textarea class="large-text" rows="6" id="cremeni_guide_sources" name="cremeni_guide_sources"><?php echo esc_textarea($get('_cremeni_guide_sources')); ?></textarea><p class="description">Uma fonte por linha. Registrar referências utilizadas na elaboração.</p></td></tr>
        <tr><th><label for="cremeni_guide_related_products">Produtos relacionados</label></th><td><input class="large-text" id="cremeni_guide_related_products" name="cremeni_guide_related_products" value="<?php echo esc_attr($get('_cremeni_guide_related_products')); ?>" placeholder="IDs WooCommerce separados por vírgula"></td></tr>
        <tr><th><label for="cremeni_guide_pdf_attachment">PDF final</label></th><td><input id="cremeni_guide_pdf_attachment" name="cremeni_guide_pdf_attachment" inputmode="numeric" value="<?php echo esc_attr($get('_cremeni_guide_pdf_attachment')); ?>" placeholder="ID do anexo na Biblioteca de Mídia"></td></tr>
        <tr><th><label for="cremeni_guide_disclaimer">Disclaimer</label></th><td><textarea class="large-text" rows="4" id="cremeni_guide_disclaimer" name="cremeni_guide_disclaimer"><?php echo esc_textarea($get('_cremeni_guide_disclaimer')); ?></textarea></td></tr>
    </table>
    <?php
}

function cremeni_guides_save(int $post_id): void
{
    static $processing = false;
    if ($processing) {
        return;
    }

    if (! isset($_POST['cremeni_guides_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cremeni_guides_nonce'])), 'cremeni_guides_save')) {
        return;
    }

    if (! current_user_can('edit_post', $post_id) || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    $processing = true;

    $fields = [
        'cremeni_guide_subtitle'              => '_cremeni_guide_subtitle',
        'cremeni_guide_version'               => '_cremeni_guide_version',
        'cremeni_guide_audience'              => '_cremeni_guide_audience',
        'cremeni_guide_sensitivity'           => '_cremeni_guide_sensitivity',
        'cremeni_guide_editorial_status'      => '_cremeni_guide_editorial_status',
        'cremeni_guide_distribution'          => '_cremeni_guide_distribution',
        'cremeni_guide_editor'                => '_cremeni_guide_editor',
        'cremeni_guide_professional_reviewer' => '_cremeni_guide_professional_reviewer',
        'cremeni_guide_review_date'           => '_cremeni_guide_review_date',
        'cremeni_guide_related_products'      => '_cremeni_guide_related_products',
        'cremeni_guide_pdf_attachment'        => '_cremeni_guide_pdf_attachment',
    ];

    foreach ($fields as $request_key => $meta_key) {
        $value = isset($_POST[$request_key]) ? sanitize_text_field(wp_unslash($_POST[$request_key])) : '';
        update_post_meta($post_id, $meta_key, $value);
    }

    $sources = isset($_POST['cremeni_guide_sources']) ? sanitize_textarea_field(wp_unslash($_POST['cremeni_guide_sources'])) : '';
    $disclaimer = isset($_POST['cremeni_guide_disclaimer']) ? sanitize_textarea_field(wp_unslash($_POST['cremeni_guide_disclaimer'])) : '';
    update_post_meta($post_id, '_cremeni_guide_sources', $sources);
    update_post_meta($post_id, '_cremeni_guide_disclaimer', $disclaimer);
    update_post_meta($post_id, '_cremeni_guide_last_editorial_update', current_time('mysql'));

    $sensitivity = (int) get_post_meta($post_id, '_cremeni_guide_sensitivity', true);
    $editorial_status = (string) get_post_meta($post_id, '_cremeni_guide_editorial_status', true);
    $professional_reviewer = trim((string) get_post_meta($post_id, '_cremeni_guide_professional_reviewer', true));

    if (get_post_status($post_id) === 'publish' && $editorial_status !== 'aprovado') {
        wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
        set_transient('cremeni_guide_publish_blocked_' . get_current_user_id(), 'Todo Guia Cremeni precisa estar com status editorial Aprovado antes da publicação.', 60);
    } elseif ($sensitivity >= 3 && get_post_status($post_id) === 'publish' && $professional_reviewer === '') {
        wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
        set_transient('cremeni_guide_publish_blocked_' . get_current_user_id(), 'Conteúdo clínico/veterinário exige revisor profissional identificado antes da publicação.', 60);
    }

    $processing = false;
}
add_action('save_post_cremeni_guide', 'cremeni_guides_save', 30);

function cremeni_guides_admin_notice(): void
{
    $key = 'cremeni_guide_publish_blocked_' . get_current_user_id();
    $message = get_transient($key);
    if (! is_string($message) || $message === '') {
        return;
    }

    delete_transient($key);
    echo '<div class="notice notice-error"><p><strong>Publicação do Guia Cremeni bloqueada.</strong> ' . esc_html($message) . '</p></div>';
}
add_action('admin_notices', 'cremeni_guides_admin_notice');

function cremeni_guides_pdf_url(int $guide_id): string
{
    $attachment_id = (int) get_post_meta($guide_id, '_cremeni_guide_pdf_attachment', true);
    if ($attachment_id <= 0) {
        return '';
    }

    $url = wp_get_attachment_url($attachment_id);
    return is_string($url) ? $url : '';
}
