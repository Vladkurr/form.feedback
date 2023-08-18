<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class Form extends CBitrixComponent {
    private $_request;
    private function _checkModules() {
        return true;
    }
    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }
    private function _user() {
        global $USER;
        return $USER;
    }
    public function onPrepareComponentParams($arParams) {
        return $arParams;
    }
    public function executeComponent() {
        if($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]){
            if($this->arParams["MAIL_EVENT"] != null){
                foreach ($this->arParams["MAIL_EVENT"] as $event){
                    CEvent::Send($event, SITE_ID, $_POST);
                }
            }
        }
        $this->_checkModules();
        $this->_request = Application::getInstance()->getContext()->getRequest();
        $this->includeComponentTemplate();
    }
}