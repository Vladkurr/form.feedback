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

    private function getFields(){
        $data = [];

        // соответствие полей
        // стандартные поля:
        // NAME
        // PHONE
        // EMAIL
        // COMMENT
        $data["EMAIL"] = $_POST["MAIL"];
        $data["NAME"] = $_POST["NAME"];
        $data["PHONE"] = $_POST["PHONE"];
        $data["COMMENT"] = $_POST["MESSAGE"] ? "Доп. информация: " . $_POST["MESSAGE"] . "<br>" : "";
        $data["COMMENT"] .= 'ссылка на страницу - ' . $_POST["PAGE"];

        return $data;
    } // установи соответстие полей Bitrix и B24!!

    private function sendRequest($request, $method)
    {
        // поля запроса и метод
        $queryData = http_build_query($request);
        $queryUrl = 'https://investsochi.bitrix24.ru/rest/140/y4sjwv9qaqsj0dh4/' . $method;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        //url берется из созданного вебхука, удалив в нем окончание prifile/
        //и добавив метод $rest на добавление лида
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);

        // возврат результата/ошибки
        if (array_key_exists('error', $result)) {
            return "Ошибка при сохранении лида: " . $result['error_description'];
        } else {
            return $result['result'];
        }
    } // отправка запроса b24

    private function findContact(){
        $fields = $this->GetFields();
        $request = [
            'filter' => [
                "PHONE" => $fields["PHONE"],
            ],
            "select" => [
                "ID"
            ]
        ];
        $method = 'crm.contact.list.json';

        $res = $this->sendRequest($request, $method);
        if(!empty($res)){
            return $res;
        }
        return !empty($this->sendRequest($request, $method));
    } // проверка наличия контакта b24 (true/false)

    private function createContact(){
        $fields = $this->GetFields();
        $request = [
            'fields' => [
                "NAME" => $fields["NAME"],
                "PHONE" => array((object)["VALUE" => $fields["PHONE"], "VALUE_TYPE" => "WORK"]),
                "EMAIL" => array((object)["VALUE" => $fields["EMAIL"], "VALUE_TYPE" => "WORK"])
            ],
        ];
        $method = 'crm.contact.add.json';
        return $this->sendRequest($request, $method);
    } // создание контакта b24

    private function leadCreate()
    {
        $fields = $this->getFields();
        $FindedContact = $this->findContact();
        if(!$FindedContact) $contactId = $this->createContact();
        else $contactId = $FindedContact[0]["ID"];

        $request = [
            'fields' => [
                "TITLE" => 'Заполнена форма на сайте investsochi.ru', //Заголовок лида
                "SOURCE_ID" => 'WEB', //Источник лида
                "NAME" => $fields['NAME'] ? $fields['NAME'] : 'Имя не заполнено', //Имя контакта
                "EMAIL" => [["VALUE" => $fields['EMAIL'], "VALUE_TYPE" => "WORK"]], //Почта контакта
                "PHONE" => [["VALUE" => $fields['PHONE'], "VALUE_TYPE" => "WORK"]], //Телефон контакта
                "COMMENTS" => $fields["COMMENT"], // доп. иформация от пользователя
                "CONTACT_ID" => $contactId ? $contactId : "", // id созданного контакта
                "ASSIGNED_BY_ID" => 1688 // 123 Николай Кузьмин
            ],
            'params' => array("REGISTER_SONET_EVENT" => "Y"), //Говорим, что требуется зарегистрировать новое событие и оповестить всех прчиастных
        ];

        //обращаемся к Битрикс24 при помощи функции curl_exec
        //метод crm.lead.add.json добавляет лид
        $method = 'crm.lead.add.json';
        return $this->sendRequest($request, $method);
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
        // если есть $_POST - компонент не инициализирует форму,а запускает ее отправку
        if ($this->request->getPost("TOKEN") == $this->arParams["TOKEN"]) {
            $GLOBALS["APPLICATION"]->RestartBuffer(); // строка для удобства отладки
            $id = $this->addToIblock(); // добавление в инфоблок
            $this->mailer($id); // отправка почты
            $this->leadCreate(); // создание лида (b24)
        } else {
            // обычный запуск компонента
            $this->includeComponentTemplate();
        }
    }
}