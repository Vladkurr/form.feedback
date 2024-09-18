initForm = function (componentName, params) {
    let form = $(`#${params.TOKEN}`);

    form.on("submit", (e) => {
        e.preventDefault()
        form = document.querySelector(`#${params.TOKEN}`);

        let formData = new FormData(form);
        for (var key in params) {
            formData.append(key, params[key])
        }
        let request = BX.ajax.runComponentAction(componentName, "send", {
            mode: "ajax",
            data: formData,
        });
    })
}