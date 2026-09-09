<?php
/** Assinatura gráfica oficial CREMENI embutida inline para evitar falha de entrega SVG na hospedagem. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
$logo_path = get_template_directory() . '/assets/images/cremeni-store-logo.svg';
?>
<span class="cremeni-wordmark cremeni-wordmark--official" aria-label="CREMENI">
<?php
if (is_file($logo_path)) {
    $svg = file_get_contents($logo_path);
    if (is_string($svg) && $svg !== '') {
        echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ativo SVG versionado e controlado pela CREMENI.
    }
}
?>
</span>
