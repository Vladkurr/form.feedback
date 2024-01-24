<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
class Form extends CBitrixComponent
{
    private function normalizeFiles($name, $vector) // создание удобного массива файлов
    {
        $result = array();

        foreach ($vector as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }

        return $result;
    }

    private function mailer($id) // отправка почты через mail() и активация почтового шаблона
    {
        if ($this->arParams["MAIL_EVENT"] != null) {
            foreach ($this->arParams["MAIL_EVENT"] as $event) {
                $urlInAdmin = 'http://'. $_SERVER["SERVER_NAME"] .'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=' . $this->arParams['IBLOCK_ID'] . '&type=Forms&lang=ru&ID=' . $id . '&find_section_section=-1&WF=Y';
                $_POST["ADMIN_URL"] = $urlInAdmin;
                echo CEvent::Send($event, SITE_ID, $_POST);
            }
        }
        if ($this->arParams["MAIL_TO"] != null) {
            $to = $this->arParams["MAIL_TO"];
            $subject = 'На сайте заполнена форма';
            $message = '';
            foreach ($this->request->getPostList() as $key => $post) {
                if ($key != "TOKEN")
                    $message .= $key . ": " . $post . "\n";
            }
            echo mail($to, $subject, $message);
        }
    }

    private function addToIblock() // добавление в инфоблок
    {
        if ($this->arParams["IBLOCK_ID"] != null) {

            $el = new CIBlockElement;
            $propertyValues = $this->request->getPostList()->getValues();
            foreach ($_FILES as $key => $file) {
                if (is_array($file["name"])) {
                    $_FILES[$key] = $this->normalizeFiles($key, $file);
                }
                $propertyValues[$key] = $_FILES[$key];
            }

            $arLoadProductArray = array(
                'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
                "IBLOCK_TYPE" => $this->arParams["IBLOCK_TYPE"],
                "CODE" => date('l jS \of F Y h:i:s A'),
                "NAME" => "На сайте заполнена форма " . $this->arParams["TOKEN"],
                "ACTIVE" => "N",
                "PROPERTY_VALUES" => $propertyValues,
            );
            $res = $el->Add($arLoadProductArray, false, false, true);
            return $res;
        }
    }

    public function executeComponent() // инициализация компонента
    {
        if ($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]) {
            $GLOBALS["APPLICATION"]->RestartBuffer(); // строка для удобства отладки
            $elemId = $this->addToIblock();
            $this->mailer($elemId);
        } else {
            $this->includeComponentTemplate();
        }
    }
}