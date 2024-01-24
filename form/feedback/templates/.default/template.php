<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<form id="<?= $arParams['TOKEN'] ?>">
    <input class="input" type="text" name="NAME" placeholder="Имя" required>
    <input class="input" type="text" name="PHONE" placeholder="Контактный телефон" required>
    <input class="input" type="text" name="MAIL" placeholder="Электронная почта" required>
    <button id="btn_<?= $arParams['TOKEN'] ?>" type="button">Отправить</button>
</form>
<script>
    // reject reload
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("submit", (event) => {
        event.preventDefault()
    });

    function validQuest(e, name, text, regex = /.{1}/) {
        if (e.name == name) {
            if (!regex.test(e.value)) {
                if(!e.parentElement.classList.contains("form__cell_er")){
                    e.parentElement.classList.add("form__cell_er");
                    if(name != "PHONE"){
                        e.parentElement.innerHTML += `<span style="color:#000">${text}</span>`;
                    }
                }
                window.FormStatus = false;
            } else {
                if(e.parentElement.classList.contains("form__cell_er")){
                    if(e.parentElement.querySelector("span")){
                        e.parentElement.querySelector("span").remove();
                    }
                    e.parentElement.classList.remove("form__cell_er");
                }
            }
        }
    }

    //submit
    document.querySelector("#btn_<?= $arParams["TOKEN"] ?>").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        window.FormStatus = true;
        validQuest(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='NAME']"), "NAME", "Заполните поле")
        validQuest(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='MAIL']"), "MAIL", "почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
        validQuest(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='PHONE']"), "PHONE", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
        if (window.FormStatus) {
            let data = new FormData(document.querySelector("#<?= $arParams["TOKEN"] ?>"))
            data.append("TOKEN", "<?= $arParams["TOKEN"] ?>")
            let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
            let result = await fetch(curDir, {
                method: 'POST',
                body: data,
            }).then(response => {
                return response.text()
            }).then(data => {
                return data;
            })
            // document.querySelector("#open-modal").click()
        }

    });


</script>


