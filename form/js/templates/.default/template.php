<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>
<form id="<?= $arParams['TOKEN'] ?>">
    <input class="input er" type="text" name="name" placeholder="ФИО" required>
    <input class="input" type="text" name="phone" placeholder="Контактный телефон" required>
    <input class="input" type="text" name="mail" placeholder="email" required>
    <textarea class="textarea" name="message" placeholder="Ваше сообщение"></textarea>
    <button id="button_<?=$arParams['TOKEN']?>" class="button button-primary" type="button"><span>Отправить</span></button>
</form>
<script>
    if (status == null) {
        let status = false;
    } else status = false;
    // reject reload
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("submit", (event) => {
        event.preventDefault()
    });

    //valid
    function valid(e, name, text, regex = /.{1}/) {
        if (e.name == name) {
            if (!regex.test(e.value)) {
                if (e.style != "border: 1px solid red;") {
                    e.style = "border: 1px solid red;"
                    status = false
                }
            } else {
                e.style = ""
                status = true
            }

        }
    }

    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("keyup", (e) => {
        valid(e.target, "name", "Поле обязательно к заполнению")
        valid(e.target, "mail", "почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
        valid(e.target, "phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
    });
    //submit
    document.querySelector("#button_<?=$arParams['TOKEN']?>").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        let params = new URLSearchParams();
        params.set("TOKEN", "<?= $arParams["TOKEN"] ?>")
        let inputs = document.querySelectorAll("#<?= $arParams["TOKEN"] ?> input");
        inputs = Array.from(inputs);
        if (document.querySelector("#<?= $arParams["TOKEN"] ?> textarea") != null) {
            inputs[inputs.length] = document.querySelector("#<?= $arParams["TOKEN"] ?> textarea")
        }
        for (let i = 0; i < inputs.length; i++) {
            let type = inputs[i].type;
            if (type == "file") {
                file = inputs[i].files;
                if (file[0]) params.set(inputs[i].name, window.URL.createObjectURL(inputs[i].files[0]))
            } else{
                value = inputs[i].value;
                if (value) params.set(inputs[i].name, value)
            }
        }
        let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
        url = "/local/components/form/form.js/send.ajax.php"
        if (status == "true") {
            let result = await fetch(curDir, {
                method: 'POST',
                body: params,
            })
        } else {
            valid(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='name']"), "name", "Поле обязательно к заполнению")
            valid(document.querySelector("#<?= $arParams["TOKEN"] ?> [name='mail']"), "mail", "почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
            valid(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='phone']"), "phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
        }
    })
    ;
</script>


