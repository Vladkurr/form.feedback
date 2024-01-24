<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]) {
    $GLOBALS["APPLICATION"]->RestartBuffer();

    $this->mailer();
    $this->addToIblock();


} else {
    $this->includeComponentTemplate();
}
?>