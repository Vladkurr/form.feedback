<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/*var SITE_ID */

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

    private function leadCreate()
    {
        $data = $_POST;

        // соответствие полей
        $data["EMAIL"] = $data["MAIL"];
        $data["COMMENT"] = $_POST["MESSAGE"] ? "Доп. информация: " . $_POST["MESSAGE"] . "<br>" : "";
        $data["COMMENT"] .= 'ссылка на страницу - ' . $data["PAGE"];

        $queryData = http_build_query(array(
            'fields' => array(
                "TITLE" => 'Заполнена форма на сайте investsochi.ru', //Заголовок лида
                "SOURCE_ID" => 'WEB', //Источник лида
                "NAME" => $data['NAME'] ? $data['NAME'] : 'Имя не заполнено', //Имя контакта
                "EMAIL" => [["VALUE" => $data['EMAIL'], "VALUE_TYPE" => "WORK"]], //Почта контакта
                "PHONE" => [["VALUE" => $data['PHONE'], "VALUE_TYPE" => "WORK"]], //Телефон контакта
                "COMMENTS" => $data["COMMENT"],
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y"), //Говорим, что требуется зарегистрировать новое событие и оповестить всех прчиастных
        ));

        //обращаемся к Битрикс24 при помощи функции curl_exec
        //метод crm.lead.add.json добавляет лид
        $rest = 'crm.lead.add.json';

        //url берется из созданного вебхука, удалив в нем окончание prifile/
        //и добавив метод $rest на добавление лида
        $queryUrl = 'https://investsochi.bitrix24.ru/rest/140/y4sjwv9qaqsj0dh4/' . $rest;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);

        if (array_key_exists('error', $result)) {
            echo "Ошибка при сохранении лида: " . $result['error_description'] . "";
        } else {
            echo $result['result'];
        }
    } // создание лида в b24 (стандартные поля)

    private function mailer($id) // отправка почты через mail() и активация почтового шаблона
    {
        if ($this->arParams["MAIL_EVENT"] != null) {
            global $APPLICATION;
            foreach ($this->arParams["MAIL_EVENT"] as $event) {
                $urlInAdmin = 'http://' . $_SERVER["SERVER_NAME"] . '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=' . $this->arParams['IBLOCK_ID'] . '&type=Forms&lang=ru&ID=' . $id . '&find_section_section=-1&WF=Y';
                $_POST["PAGE"] = "https://" . $_SERVER["SERVER_NAME"] . $APPLICATION->GetCurPage();
                $_POST["ADMIN_URL"] = $urlInAdmin;
                echo CEvent::Send($event, SITE_ID, $_POST);
            }
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
                "NAME" => "На сайте заполнена форма",
                "ACTIVE" => "Y",
                "PROPERTY_VALUES" => $propertyValues,
            );
            $res = $el->Add($arLoadProductArray, false, false, true);
            return $res;
        }
    }

    public function executeComponent() // инициализация компонента
    {
        // если есть $_POST - компонент не инициализирует форму, запускает ее отправку
        if ($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]) {
            $GLOBALS["APPLICATION"]->RestartBuffer(); // строка для удобства отладки
            $id = $this->addToIblock(); // добавление в инфоблок
            $this->mailer($id); // отправка почты
            if($this->arParams["CRM"] == "Y") $this->leadCreate(); // создание лида (b24)
            // file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/test.txt", serialize($_POST)); // debug
        } else {
            // обычный запуск компонента
            $this->includeComponentTemplate();
        }
    }
}