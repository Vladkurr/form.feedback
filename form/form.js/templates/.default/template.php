<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>
<form class="form" action="" id="<?= $arParams["TOKEN"] ?>">
    <input type="text" data-type="text" class="name valid">
    <input type="text" data-type="text" class="phone">
    <input type="text" data-type="text" class="mail">
    <input type="text" data-type="text" class="address">
    <textarea type="text" data-type="text" class="message"></textarea>
    <input type="file" data-type="file" class="file">
    <button class="submit">Отправить</button>
</form>
<script>
    let status = false;
    // reject reload
    document.querySelector(".form").addEventListener("submit", (event) => {
        event.preventDefault()
    });

    //valid
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("keyup", (e) => {
        function valid(name, text, regex=/.{1}/){
            if (e.target.classList.contains(name)) {
                if (!regex.test(e.target.value)) {
                    if (e.target.nextSibling.textContent != text) {
                        e.target.insertAdjacentHTML("afterend", `<p>${text}</p>`)
                        e.target.style = "border: 1px solid red;"
                        status = false
                    }
                } else {
                    if (e.target.nextSibling.textContent == text) e.target.nextSibling.remove()
                    e.target.style = ""
                    status = true
                }
            }
        }
        valid("valid", "Поле обязательно к заполнению")
        valid("phone", "Номер введен неверно", /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/)
        valid("mail", "Почта введена неверно", /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu)
    });

    // submit
    document.querySelector(".submit").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        let params = new URLSearchParams();
        params.set("EVENT", "<?= $arParams["MAIL_EVENT"] ?>")
        let inputs = ["name", "phone", "mail", "address", "message", "file"];
        for (let i = 0; i < inputs.length; i++) {
            let type = document.querySelector("." + inputs[i]).attributes["data-type"].value;
            if (type == "text") {
                value = document.querySelector("." + inputs[i]).value;
                if (value) params.set(inputs[i], document.querySelector("." + inputs[i]).value)
            } else if (type == "file") {
                file = document.querySelector("." + inputs[i]).files;
                if (file[0]) params.set(inputs[i], window.URL.createObjectURL(document.querySelector("." + inputs[i]).files[0]))
            }
        }

        url = "/local/components/form/form.js/send.ajax.php"
        if (status) {
            let result = await fetch(url, {
                method: 'POST',
                body: params,
            }).then(response => {
                return response.text()
            }).then(data => {
                return data;
            })
        } else {
            console.log("error")
            phone = document.querySelector(".phone")
            if (phone != null) {
                phone.insertAdjacentHTML("afterend", "<p>Номер введен неверно</p>")
                phone.style = "border: 1px solid red;"
            }
            mail = document.querySelector(".mail")
            if (mail != null) {
                mail.insertAdjacentHTML("afterend", "<p>Почта введена неверно</p>")
                mail.style = "border: 1px solid red;"
            }
            valid = document.querySelectorAll(".valid")
            if (valid != null) {
                for (let i = 0; i < valid.length; i++) {
                    valid[i].insertAdjacentHTML("afterend", "<p>Поле обязательно к заполнению</p>")
                    valid[i].style = "border: 1px solid red;"
                }
            }
        }
    })
    ;
</script>
