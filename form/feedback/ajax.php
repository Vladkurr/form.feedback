<?php
namespace Vendor\Example\Controller;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\ActionFilter;
if (!\Bitrix\Main\Loader::includeModule('iblock')) return;

class Item extends \Bitrix\Main\Engine\Controller
{
    /**
     * @return array[]
     */
    public function configureActions()
    {
        return [
            'send' => [
                'prefilters' => [

                ],
                '-prefilters' => [
                    \Bitrix\Main\Engine\ActionFilter\Authentication::class
                ],
            ]
        ];
    }

    /**
     * @return true|void
     */
    public function sendAction() :bool
    {
        $post = \Bitrix\Main\Context::getCurrent()->getRequest()->getPostList()->toArray();

        $this->addToIblock($post["IBLOCK_ID"], $post);
        $this->send($post["MAIL_EVENT"], $post); // отправка почты

        return true;
    }

    /**
     * @param $vector
     * @return array
     */
    private function normalizeFiles($vector) :array
    {
        $result = array();

        foreach ($vector as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }

        return $result;
    }

    /**
     * @param $event
     * @param $fields
     * @return null
     */
    private function send($event, $fields): ?int
    {
        return \CEvent::Send($event, SITE_ID, $fields);
    }

    /**
     * @param $iblockId
     * @param $fields
     * @return null
     */
    private function addToIblock($iblockId, $fields): ?int
    {
        $el = new \CIBlockElement;
        foreach ($_FILES as $key => $file) {
            if (is_array($file["name"])) {
                $_FILES[$key] = $this->normalizeFiles($file);
            }
            $fields[$key] = $_FILES[$key];
        }

        $arLoadProductArray = array(
            'IBLOCK_ID' => $iblockId,
            "CODE" => date('l jS \of F Y h:i:s A'),
            "NAME" => "На сайте заполнена форма",
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => $fields,
        );
        $res = $el->Add($arLoadProductArray, false, false, true);
        return $res;
    }
}
