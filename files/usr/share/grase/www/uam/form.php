<?php
header("Content-Type: text/javascript; charset=utf-8");

require_once('includes/site.inc.php');
if($Settings->getSetting('autocreategroup'))
{
    $templateEngine->assign('automac', true);
}
?>
var o = document.getElementById('logonForm');
if (o != null) {

o.innerHTML='<?php

$template=str_replace("\n", " ", str_replace("'","\'",$templateEngine->fetch('../json_html.tmpl')));
echo $template;

?>'
}
setTimeout('chilliController.refresh()', 0);

