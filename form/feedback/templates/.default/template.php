<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<form id="<?= $arParams['TOKEN'] ?>">
    <input class="input" type="text" name="NAME" placeholder="Имя" required>
    <input class="input" type="text" name="PHONE" placeholder="Контактный телефон" required>
    <input class="input" type="text" name="MAIL" placeholder="Электронная почта" required>
    <input class="input" type="file" name="FILE" multiple required>
    <input class="input" type="file" name="FILE2[]" multiple required>
    <button class="submit4 button button-primary" type="button">Отправить</button>
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
    //function valid(e, name, text, regex = /.{1}/) {
    //    if (e.name == name) {
    //        if (!regex.test(e.value)) {
    //            if (e.style != "border: 1px solid red;") {
    //                e.style = "border: 1px solid red;"
    //                status = false
    //            }
    //        } else {
    //            e.style = ""
    //            status = true
    //        }
    //
    //    }
    //}
    //document.querySelector("#<?php //= $arParams["TOKEN"] ?>//").addEventListener("keyup", (e) => {
    //    valid(e.target, "name", "Поле обязательно к заполнению")
    //    valid(e.target, "mail", "почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
    //    valid(e.target, "phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
    //});
    //submit
    document.querySelector(".submit4").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        let data = new FormData(document.querySelector("#<?= $arParams["TOKEN"] ?>"))
        data.append("TOKEN", "<?= $arParams["TOKEN"] ?>")
        let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
        if (true) {
            let result = await fetch(curDir, {
                method: 'POST',
                body: data,
            }).then(response => {
                return response.text()
            }).then(data => {
                return data;
            })
            console.log(result)
        } else {
            //valid(document.querySelector("#<?php //= $arParams["TOKEN"] ?>// input[name='name']"), "name", "Поле обязательно к заполнению")
            //valid(document.querySelector("#<?php //= $arParams["TOKEN"] ?>// [name='mail']"), "mail", "почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
            //valid(document.querySelector("#<?php //= $arParams["TOKEN"] ?>// input[name='phone']"), "phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
        }
    })
    ;
</script>


