<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>
<form action="" id="<?= $arParams["TOKEN"] ?>">
    <input type="text" name="name">
    <input type="text" name="phone">
    <input type="text" name="mail">
    <input type="text" name="address">
    <textarea name="message"></textarea>
    <input type="file" name="file">
    <button class="submit">Отправить</button>
</form>
<script>
    let status = false;
    // reject reload
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("submit", (event) => {
        event.preventDefault()
    });

    //valid
    function valid(e, name, text, regex = /.{1}/) {
        if (e.name == name) {
            if (!regex.test(e.value)) {
                if (e.nextSibling.textContent != text) {
                    e.insertAdjacentHTML("afterend", `<p>${text}</p>`)
                    e.style = "border: 1px solid red;"
                    status = false
                }
            } else if (e.nextSibling.textContent == text) {
                e.nextSibling.remove()
                e.style = ""
                status = true
            }

        }
    }
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("keyup", (e) => {
        valid(e.target, "name", "Поле обязательно к заполнению")
        valid(e.target, "phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
        valid(e.target, "mail", "Почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
    });
    // submit
    document.querySelector(".submit").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        let params = new URLSearchParams();
        params.set("TOKEN", "<?= $arParams["TOKEN"] ?>")
        let inputs = document.querySelectorAll("#<?= $arParams["TOKEN"] ?> input");
        for (let i = 0; i < inputs.length; i++) {
            let type = inputs[i].type;
            if (type == "text" || type == "textarea") {
                value = inputs[i].value;
                if (value) params.set(inputs[i].name, value)
            } else if (type == "file") {
                file = inputs[i].files;
                if (file[0]) params.set(inputs[i].name, window.URL.createObjectURL(inputs[i].files[0]))
            }
        }
        let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
        url = "/local/components/form/form.js/send.ajax.php"
        if (status) {
            let result = await fetch(curDir, {
                method: 'POST',
                body: params,
            })
        } else {
            valid(document.querySelector("[name='name']"), "name", "Поле обязательно к заполнению")
            valid(document.querySelector("[name='mail']"), "mail", "Почта введена неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
            valid(document.querySelector("[name='phone']"), "phone", "Номер введен неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
        }
    })
    ;
</script>
