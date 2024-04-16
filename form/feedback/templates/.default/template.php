<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LeklLwpAAAAABMCnqHdw0hBHj8MlivzApw0F857"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('6LeklLwpAAAAABMCnqHdw0hBHj8MlivzApw0F857', {action: 'contact'}).then(function (token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>
<div class="contacts__form">
    <div class="contacts__form-title">Остались вопросы? Будем рады помочь!</div>
    <form id="<?= $arParams["TOKEN"] ?>">
        <div class="form">
            <div class="form__row">
                <div class="form__cell">
                    <div class="form__item">
                        <label>Имя</label>
                        <input type="text" placeholder="" name="NAME">
                    </div>
                </div>
                <div class="form__cell">
                    <div class="form__item">
                        <label>E-mail</label>
                        <input type="email" placeholder="" name="MAIL">
                    </div>
                </div>
            </div>
            <div class="form__row">
                <div class="form__cell w100">
                    <div class="form__item">
                        <label>Сообщение</label>
                        <textarea name="MESSAGE"></textarea>
                    </div>
                </div>
            </div>

            <div class="form__row">
                <div class="form__cell w100">
                    <div class="form__check">
                        <input type="checkbox" id="check" checked>
                        <label for="check">Нажимая кнопку "Оформить заказ", я даю согласие на обработку
                            своих персональных данных в соответствии с <a href="/policy/">Политикой
                                конфиденциальности</a> </label>
                    </div>
                </div>
            </div>
            <div class="form__row">
                <div class="form__cell w100">
                    <button type="button" id="btn_<?= $arParams["TOKEN"] ?>" class="btn">Отправить</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <a id="send-btn" href="#send" class="open-modal"></a>
        <a id="error-btn" href="#nn" class="open-modal"></a>

    </form>
</div>

<script>
    // reject reload
    document.querySelector("#<?= $arParams["TOKEN"] ?>").addEventListener("submit", (event) => {
        event.preventDefault()
    });

    //valid
    function validContacts(e, name, text, regex = /.{1}/) {
        if (e.name == name) {
            if (!regex.test(e.value)) {
                e.style = "border: 1px solid red"
                window.FormStatus = false;
            } else {
                e.style = "";
            }
        }
    }

    //submit
    document.querySelector("#btn_<?= $arParams["TOKEN"] ?>").addEventListener("click", async (event) => {
        // send ajax request with values of inputs
        window.FormStatus = true;
        validContacts(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='NAME']"), "NAME", "Поле обязательно к заполнению")
        validContacts(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='MAIL']"), "NAME", "Поле обязательно к заполнению")
        let curDir = document.location.protocol + '//' + document.location.host + document.location.pathname;
        let data = new FormData(document.querySelector("#<?= $arParams["TOKEN"] ?>"))
        data.append("TOKEN", "<?= $arParams["TOKEN"] ?>")
        data.append("PAGE", curDir)
        if (window.FormStatus) {
            let result = await fetch(curDir, {
                method: 'POST',
                body: data,
            }).then(response => {
                return response.text()
            }).then(data => {
                return data;
            })
            result = JSON.parse(result.trim())

            if(result.STATUS == "success"){
                document.querySelector("#send-btn").click()
            }
            else{
                document.querySelector("#error-btn").click()
            }
        } else {
            window.FormStatus = true;
            validContacts(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='NAME']"), "NAME", "Поле обязательно к заполнению")
            validContacts(document.querySelector("#<?= $arParams["TOKEN"] ?> input[name='MAIL']"), "MAIL", "Поле обязательно к заполнению")
        }

    })
    ;
</script>
