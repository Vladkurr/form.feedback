<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\UI\Extension::load("ui.vue");
/**
 * @var $APPLICATION
 * @var $templateFolder
 * @var $arParams
 * @var $arResult
 */
?>

<form class="form" action="" id="<?= $arParams["TOKEN"] ?>" v-on:submit.prevent="post()">
    <select v-model="form.vac">
        <option class="option selected" v-for="option in options" v-bind:value="option.value">{{ option.text }}</option>
    </select>
    <input name="name" type="text" data-type="text" class="name valid" v-model="form.name" v-validate="'required|min:3'" v-bind:class="{'is-danger': errors.has('name')}">
    <p class="help is-danger" v-show="errors.has('name')">
        {{ errors.first('name') }}
    </p>
    <input name="phone" type="text" data-type="text" class="phone" v-model="form.phone" v-validate="{regex : phone_regex}" v-bind:class="{'is-danger': errors.has('name')}">
    <p class="help is-danger" v-show="errors.has('phone')">
        {{ errors.first('phone') }}
    </p>
    <input name="mail" type="text" data-type="text" class="mail" v-model="form.mail"  v-validate="{regex : mail_regex}" v-bind:class="{'is-danger': errors.has('name')}">
    <p class="help is-danger" v-show="errors.has('mail')">
        {{ errors.first('mail') }}
    </p>
    <input type="text" data-type="text" class="address" v-model="form.address">
    <textarea type="text" data-type="text" class="message" v-model="form.message"></textarea>
    <input type="file" data-type="file" class="file">
    <button class="submit">Отправить</button>
</form>

<script src="https://unpkg.com/vee-validate@2.0.0-rc.18/dist/vee-validate.js"></script>
<script>
    BX.Vue.use(VeeValidate);
    let app = BX.Vue.create({
        el: '#<?= $arParams["TOKEN"] ?>',
        data: {
            mail_regex : /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu,
            phone_regex : /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/,
            form : {
                name: "",
                phone: "",
                mail: "",
                address: "",
                message: "",
                vac: 0,
            },
            options : [
                {value: 0, text: "Инженер-технолог по полимерам"},
                {value: 1, text: "Инженер-технолог по полимерам"},
                {value: 2, text: "Инженер-технолог по полимерам"},
                {value: 3, text: "Инженер-технолог по полимерам"},
                {value: 5, text: "Инженер-технолог по полимерам"},
                {value: 6, text: "Инженер-технолог по полимерам"},
            ]
        },
        methods:{
            async post() {
                let params = new URLSearchParams();
                params.set("EVENT", "<?= $arParams["MAIL_EVENT"] ?>")
                for (key in this.form){
                    if(this.form[key] != "") params.set(key, this.form[key])
                }

                url = "local/components/form/form.vue/send.ajax.php"
                let result = await fetch(url, {
                    method: 'POST',
                    body: params,
                }).then(response => {
                    return response.text()
                }).then(data => {
                    return data;
                })
                console.log(result)
            }
        }
    })
</script>
