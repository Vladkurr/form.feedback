# Bitrix js form component

__Component calling example for default template:__
```php
<? $APPLICATION->IncludeComponent(
	"form:feedback", 
	".default", 
	array(
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "Forms",
		"MAIL_EVENT" => array(
		    0 => "MAIN_QUESTION",
		),
		"TOKEN" => "test",
		"COMPONENT_TEMPLATE" => ".default",
		"MAIL_TO" => "test@mail.ru"
	),
	false
); ?>

```
