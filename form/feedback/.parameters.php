<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Localization\Loc;
if(!CModule::IncludeModule("iblock")) return;

$arFilter = array("ACTIVE" => "Y");
$arEvent = array();
$dbType = CEventType::GetList($arFilter);
while ($arType = $dbType->GetNext()) {
    if ($arEvent[$arType["EVENT_NAME"]] == null) {
        $arEvent[$arType["EVENT_NAME"]] = $arType["EVENT_NAME"] . " [" . $arType["ID"] . "]";
    }
}
$arTypesEx = CIBlockParameters::GetIBlockTypes();
$iblockFilter = [
    'ACTIVE' => 'Y',
];
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $iblockFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

$db_iblock = CIBlock::GetList(["SORT" => "ASC"], $iblockFilter);
while ($arRes = $db_iblock->Fetch()) {
    $arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "news",
            "REFRESH" => "Y",
        ],
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("IBLOCK_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "REFRESH" => "Y",
        ],
        "MAIL_EVENT" => array(
            "NAME" => GetMessage("MAIL_EVENT_TYPES"),
            "TYPE" => "LIST",
            "VALUES" => $arEvent,
            "MULTIPLE" => "Y",
            "COLS" => 25,
        ),
        "CRM" => array(
            "NAME" => GetMessage("CRM"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
    ]
];
