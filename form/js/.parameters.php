<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Localization\Loc;


$arFilter = Array("ACTIVE" => "Y");
$arEvent = Array();
$dbType = CEventType::GetList($arFilter);
while($arType = $dbType->GetNext()) {
    if ($arEvent[$arType["EVENT_NAME"]] == null) {
        $arEvent[$arType["EVENT_NAME"]] = $arType["EVENT_NAME"] . " [" . $arType["ID"] . "]";
    }
}
$arComponentParameters = [
    "PARAMETERS" => [
        "MAIL" => [
            "NAME" => Loc::getMessage('PROP_EMAIL'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "COLS" => 25
        ],
        "MAIL_EVENT" => Array(
            "NAME" => GetMessage("MAIL_EVENT_TYPES"),
            "TYPE"=>"LIST",
            "VALUES" => $arEvent,
            "MULTIPLE"=>"Y",
            "COLS"=>25,
        ),
    ]
];
