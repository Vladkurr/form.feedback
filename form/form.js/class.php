<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;


class Form extends CBitrixComponent {
    public function executeComponent() {
        if($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]){
            if($this->arParams["MAIL_EVENT"] != null){
                foreach ($this->arParams["MAIL_EVENT"] as $event){
                    CEvent::Send($event, SITE_ID, $_POST);
                }
            }
        }
        $this->includeComponentTemplate();
    }
}