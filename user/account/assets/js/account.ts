import {Request as API} from "../../../../resources/js/Request";
import {User} from "../../../../resources/js/models";
import {validateEmail}  from "../../../../resources/js/Utilities";

let user : User|null = null;

$(document).ready(function() {
    $("[data-option]").click(function (){
        let lastOption = $(".user-options-section-selected").data("option");
        let currentOption = $(this).data("option");
        $(".user-options-section-selected").removeClass("user-options-section-selected");
        $("[data-setting='" + lastOption +"']").addClass("user-options-body-hidden");
        $(this).addClass("user-options-section-selected");
        $("[data-setting='" + currentOption +"']").removeClass("user-options-body-hidden");
    });
    // selected-translation-language
    //selected-display-language
    API.requestService("session", "getSession", {}, []).then((result: any) => {
        if (result.status) {
            if ("data" in result) {
                user = result.data[0];
                $("#selected-email-communication-language").change(function(){
                    API.requestType("User", "update", {
                       "id": user?.id,
                       "email_communication_language": {
                            "id": $(this).data("selected")
                        }
                    }, []).then((result: any) => {
                        if(result.status){

                        }
                    });
                });
                $("#selected-display-language").change(function(){
                    API.requestType("User", "update", {
                        "id": user?.id,
                        "display_language": {
                            "id": $(this).data("selected")
                        }
                    }, []).then((result: any) => {
                        if(result.status){

                        }
                    });
                });
                $("#selected-translation-language").change(function(){
                    API.requestType("User", "update", {
                        "id": user?.id,
                        "translation_language": {
                            "id": $(this).data("selected")
                        }
                    }, []).then((result: any) => {
                        if(result.status){

                        }
                    });
                });

                $("#change-email_new-email").on('input', function (){
                   if($.trim(<string>$(this).val()).length > 0){
                       if($.trim(<string>$("#change-email_current-password").val()).length > 0 && validateEmail($(this).val())){
                           $("#change-email_submit").prop("disabled", false);
                       } else $("#change-email_submit").prop("disabled", true);
                   } else $("#change-email_submit").prop("disabled", true);
                });
                $("#change-email_current-password").on('input', function (){
                    if($.trim(<string>$(this).val()).length > 0){
                        if($.trim(<string>$("#change-email_new-email").val()).length > 0 && validateEmail($("#change-email_new-email").val())){
                            $("#change-email_submit").prop("disabled", false);
                        } else {
                            $("#change-email_submit").prop("disabled", true);
                        }
                    } else {
                        $("#change-email_submit").prop("disabled", true);
                    }
                });


                $("#change-password_current-password").on('input', function (){
                    if($.trim(<string>$(this).val()).length > 0){
                        if($.trim(<string>$("#change-password_new-password").val()).length > 0 &&
                            $.trim(<string>$("#change-password_repeat-password").val()).length > 0
                            && $("#change-password_new-password").val() == $("#change-password_repeat-password").val() && $.trim(<string>$("#change-password_new-password").val()).length >= 8){

                            $("#change-password_submit").prop("disabled", false);
                        } else $("#change-password_submit").prop("disabled", true);
                    } else $("#change-password_submit").prop("disabled", true);
                });

                $("#change-password_new-password").on('input', function (){
                    if($.trim(<string>$(this).val()).length > 0){
                        if($.trim(<string>$("#change-password_current-password").val()).length > 0 &&
                            $.trim(<string>$("#change-password_repeat-password").val()).length > 0
                            && $("#change-password_new-password").val() == $("#change-password_repeat-password").val() && $.trim(<string>$("#change-password_new-password").val()).length >= 8){

                            $("#change-password_submit").prop("disabled", false);
                        } else $("#change-password_submit").prop("disabled", true);
                    } else $("#change-password_submit").prop("disabled", true);
                });

                $("#change-password_repeat-password").on('input', function (){
                    if($.trim(<string>$(this).val()).length > 0){
                        if($.trim(<string>$("#change-password_current-password").val()).length > 0 &&
                            $.trim(<string>$("#change-password_new-password").val()).length > 0
                            && $("#change-password_new-password").val() == $("#change-password_repeat-password").val() && $.trim(<string>$("#change-password_new-password").val()).length >= 8){
                            $("#change-password_submit").prop("disabled", false);
                        } else $("#change-password_submit").prop("disabled", true);
                    } else $("#change-password_submit").prop("disabled", true);
                });

                $("#change-password_submit").click(function(e) {
                    e.preventDefault();
                    if ($(this).prop("disabled")) return;

                    if ($.trim(<string>$("#change-password_repeat-password").val()).length > 0 && $.trim(<string>$("#change-password_current-password").val()).length > 0 &&
                        $.trim(<string>$("#change-email_current-password").val()).length > 0
                        && $("#change-password_new-password").val() == $("#change-password_repeat-password").val() && $.trim(<string>$("#change-password_new-password").val()).length >= 8) {

                        API.requestService("Users", "changePassword", {
                            "currentPassword": $("#change-password_current-password").val(),
                            "newPassword": $("#change-password_new-password").val()
                        }, []).then((result: any) => {
                            if (result.status) {
                                location.reload();
                            }
                        });
                    }

                });


                $("#change-email_submit").click(function(e) {
                    e.preventDefault();
                    if ($(this).prop("disabled")) return;

                    if($.trim(<string>$("#change-email_current-password").val()).length > 0
                        && $.trim(<string>$("#change-email_new-email").val()).length > 0
                        && validateEmail($("#change-email_new-email").val())){

                        API.requestService("Users", "changeEmail", {
                            "currentPassword": $("#change-email_current-password").val(),
                            "newEmail": $("#change-email_new-email").val()
                        }, []).then((result: any) => {
                            if (result.status) {
                                $("#change-email_current-password").attr("value", "");
                                $("#change-email_new-email").attr("value", "");
                                $("#change-email_current-password").val("");
                                $("#change-email_new-email").val("");
                                $("#change-email_current-email").text(result.data[0]?.email);
                            }
                        });
                    }

                });
            }
        }
    });
});
