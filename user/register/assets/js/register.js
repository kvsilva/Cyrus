import { Request as API } from "../../../../resources/js/Request";
import { validateEmail } from "../../../../resources/js/Utilities";
import { cyrusAlert } from "../../../../resources/js/cyrus";
$(document).ready(function () {
    $("#username").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#email").val()).length > 0 && validateEmail($("#email").val()) && $.trim($("#password").val()).length > 0 && $.trim($("#repeat-password").val()).length > 0 && $("#password").val() == $("#repeat-password").val()) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#password").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#email").val()).length > 0 && validateEmail($("#email").val()) && $.trim($("#username").val()).length > 0 && $.trim($("#repeat-password").val()).length > 0 && $("#password").val() == $("#repeat-password").val()) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#email").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#username").val()).length > 0 && validateEmail($("#email").val()) && $.trim($("#password").val()).length > 0 && $.trim($("#repeat-password").val()).length > 0 && $("#password").val() == $("#repeat-password").val()) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#repeat-password").on('input', function () {
        if ($.trim($(this).val()).length > 0 && $.trim($("#email").val()).length > 0 && validateEmail($("#email").val()) && $.trim($("#password").val()).length > 0 && $.trim($("#username").val()).length > 0 && $("#password").val() == $("#repeat-password").val()) {
            $("#execute").prop("disabled", false);
        }
        else
            $("#execute").prop("disabled", true);
    });
    $("#execute").click(function () {
        let username = $("#username").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let formData = {
            "username": username,
            "email": email,
            "password": password
        };
        cyrusAlert("warning", "Processando o seu pedido...");
        API.requestService("authentication", "register", formData, []).then((result) => {
            if (result.status) {
                cyrusAlert("success", "Registo efetuado com sucesso. Verifique o seu email para ativar a sua conta.");
            }
            else {
                cyrusAlert("danger", "Credenciais incorretas.");
            }
        });
    });
});
