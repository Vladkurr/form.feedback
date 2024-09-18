<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
?>
    <form id="<?= $arParams["TOKEN"] ?>">
        <input type="text" name="NAME">
        <input type="email" name="MAIL">
        <input type="file" name="FILE">
        <textarea name="MESSAGE"></textarea>
        <button type="submit" >Отправить</button>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    </form>

<script type="text/javascript">
    BX.ready(function () {
        new initForm("<?= $component->getName() ?>", <?= json_encode($arParams) ?>)
    });
</script>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
