<?php
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>

<?php echo "<pre>"; var_dump($_POST); echo "</pre>"; ?>
<?php
$props = [];
foreach ($_POST as $key => $prop) {
    if($key != "EVENT") $props[$key] = $prop;
}
CEvent::Send($_POST["EVENT"], SITE_ID, $props);
?>