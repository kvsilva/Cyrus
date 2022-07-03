import { Request as API } from "../../../../resources/js/Request";
import { validateEmail } from "../../../../resources/js/Utilities";
import { cyrusAlert } from "../../../../resources/js/cyrus";
$(document).ready(function () {
    $("#username-email").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#password").val()).length > 0) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#password").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#username-email").val()).length > 0) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#execute").click(function () {
        let username_email = $("#username-email").val();
        let password = $("#password").val();
        let formData = {
            "password": password
        };
        if (validateEmail(username_email)) {
            formData["email"] = username_email;
        }
        else {
            formData["username"] = username_email;
        }
        cyrusAlert("warning", "Processando o seu pedido...");
        API.requestService("authentication", "login", formData, []).then((result) => {
            if (result.status) {
                cyrusAlert("success", "Login efetuado com sucesso. Redirecionando...");
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
            else {
                cyrusAlert("danger", "Credenciais incorretas.");
            }
        });
    });
});
