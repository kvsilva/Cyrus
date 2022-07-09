import {Request as API} from "../../../../../resources/js/Request.js";
import {cyrusAlert} from "../../../../../resources/js/cyrus";
import {Resource, TicketMessageFlags, TicketStatus} from "../../../../../resources/js/models";

$(document).ready(function () {

    $("#form0-subject").on("input", function () {
        if ($.trim(<string>$("#form0-subject").val()).length > 0 && $.trim(<string>$("#form0-description").val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        } else $("#form0-submit").prop("disabled", true);
    });

    $("#form0-description").on("input", function () {
        if ($.trim(<string>$("#form0-subject").val()).length > 0 && $.trim(<string>$("#form0-description").val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        } else $("#form0-submit").prop("disabled", true);
    });

    $("#form0-submit").click(function (e) {
        e.preventDefault();
        if (($.trim(<string>$("#form0-subject").val()).length == 0 || $.trim(<string>$("#form0-description").val()).length == 0)) {
            cyrusAlert("warning", "Há campos por preencher!");
            return;
        }

        API.requestService("session", "getSession", {}, []).then(async (result: any) => {
            if (result.status) {
                if ("data" in result) {

                    let attachments: Resource[] = [];
                    for (let i = 0; i < $("#form0-attachments").prop("files").length; i++) {
                        await API.uploadFile($("#form0-attachments").prop("files")[i]).then(async (result2: any) => {
                            if (result2.status && result2.data) {
                                await API.requestService("Resources", "uploadFile", {
                                    file: result2.data,
                                }).then((result3: any) => {
                                    if (result3.status && result3.data) {
                                        attachments.push(result3.data[0]);
                                    } else {
                                        cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                                        console.error(result3);
                                    }
                                });
                            } else {
                                cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                                console.error(result2);
                            }
                        });
                    }

                    await API.requestType("Ticket", "insert", {
                        user: result.data[0]?.id,
                        subject: $("#form0-subject").val(),
                        status: TicketStatus.OPEN,
                    }).then(async (result2: any) => {
                        if (result2.status && result2.data) {
                            let formData : any[string] = {
                                ticket: result2.data[0]?.id,
                                content: $("#form0-description").val(),
                                author: result.data[0]?.id,
                                relations: {}
                            };

                            formData["relations"][TicketMessageFlags.TICKETMESSAGEATTACHMENTS.name] = attachments;
                            await API.requestType("TicketMessage", "insert", formData, [], null, true).then((result2: any) => {
                                if(result2.status && result2.data) {
                                    cyrusAlert("success", "Ticket criado com sucesso! Redirecionando...");
                                    setTimeout(function () {
                                        window.location.href = new URL("../../../?ticket=" + result2.data[0]?.ticket?.id, import.meta.url).href;
                                    }, 2000)
                                } else {
                                    cyrusAlert("danger", "Ocorreu um erroa ao guardar os detalhes do seu ticket. Consulte a consola para mais informações.");
                                }
                            })
                        } else {
                            cyrusAlert("danger", "Ocorreu um erroa ao criar o seu ticket. Consulte a consola para mais informações.");
                        }
                    })
                }
            } else {
                cyrusAlert("danger", "Ocorreu um erroa ao criar o seu ticket. Inicie sessão.");
            }
        });
    });
});