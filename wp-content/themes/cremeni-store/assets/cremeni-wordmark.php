<?php
/** Assinatura gráfica oficial CREMENI, preservada como imagem e não reconstruída por fonte CSS. @package CremeniStore */
if (! defined('ABSPATH')) { exit; }
$cremeni_official_logo = require get_template_directory() . '/assets/cremeni-official-logo-data.php';
?>
<span class="cremeni-wordmark cremeni-wordmark--official">
    <img src="<?php echo esc_attr((string) $cremeni_official_logo); ?>" alt="CREMENI" decoding="async">
</span>
