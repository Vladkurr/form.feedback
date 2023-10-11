<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("COMPONENT"),
    "DESCRIPTION" => Loc::getMessage("COMPONENT_DESCRIPTION"),
    "COMPLEX" => "N",
    "PATH" => [
        "ID" => Loc::getMessage("COMPONENT_PATH_ID"),
        "NAME" => Loc::getMessage("COMPONENT_PATH_NAME"),
    ],
];
?>
