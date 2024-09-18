<?php

use Bitrix\Main,
    Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!\Bitrix\Main\Loader::includeModule('iblock')) return;

class Form extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}