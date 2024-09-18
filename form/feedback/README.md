# Bitrix js form component

__Component calling example for default template:__
```php
<?php
 $APPLICATION->IncludeComponent(
    "form:feedback",
    "",
    array(
        "IBLOCK_ID" => "1",
        "IBLOCK_TYPE" => "content",
        "MAIL_EVENT" => "EVENT_NAME",
        "TOKEN" => "form",
    )
);
?>

```
