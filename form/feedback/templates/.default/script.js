/**
 * @param componentName
 * @param params
 */
initForm = function (componentName, params) {
    let form = $(`#${params.TOKEN}`);
    let inputs = $(`#${params.TOKEN} input`);

    inputs.on('input', function () {
        let regex;
        switch ($(this).attr('type')) {
            case "email" :
                regex = /([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/;
                break;
            case "tel" :
                regex = /((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}/;
                break;
            default:
                regex = /.+/;
                break;
        }


        if (regex.test($(this).val())) {
            $(this).addClass("mform__input__valid");
            $(this).removeClass("mform__input__error");
            $(this).next().css("display", "none")

        } else {
            $(this).addClass("mform__input__error");
            $(this).removeClass("mform__input__valid");

        }
    });

    form.on("submit", (e) => {

        e.preventDefault()
        form = document.querySelector(`#${params.TOKEN}`);
        if ($(`#${params.TOKEN} .mform__input__valid`).length === 0 || $(`#${params.TOKEN} .mform__input__error`).length > 0) {
            $(`#${params.TOKEN} .mform__input__error`).next().css("display", "block");
            return;
        }

        let formData = new FormData(form);
        for (var key in params) {
            formData.append(key, params[key])
        }
        let request = BX.ajax.runComponentAction(componentName, "send", {
            mode: "ajax",
            data: formData,
        });

        $(`#${params.TOKEN} input`).each(function () {
            $(this).val("");
            $(this).removeClass("mform__input__valid");
            $(this).removeClass("mform__input__error");
        });
        $('#js--modal-thanks').modal('show');
        $('#js--modal-oreder').modal('hide');
        return request;
    })
}