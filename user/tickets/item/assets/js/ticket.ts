import {Request as API} from "../../../../../resources/js/Request.js";
import {Resource, TicketMessageFlags, TicketStatus} from "../../../../../resources/js/models";

$(document).ready(function () {
    // Change to Cyrus.ts

    // END

    $("#form0-textarea").on("input", function () {
        if ($.trim(<string>$(this).val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        } else {
            $("#form0-submit").prop("disabled", true);
        }
    });

    $("#form0-submit").click(function () {
        API.requestService("session", "getSession", {}, []).then(async (result: any) => {
            if (result.status) {
                if ("data" in result) {
                    let formData: any[string] = {
                        // @ts-ignore
                        ticket: getParameter("ticket"),
                        content: $("#form0-textarea").val(),
                        author: result.data[0]?.id,
                        relations: {}
                    };

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
                                        // @ts-ignore
                                        cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                                        console.error(result3);
                                    }
                                });
                            } else {
                                // @ts-ignore
                                cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                                console.error(result2);
                            }
                        });
                    }
                    formData["relations"][TicketMessageFlags.TICKETMESSAGEATTACHMENTS.name] = attachments;
                    await API.requestType("TicketMessage", "insert", formData, [], null, true).then((result2: any) => {
                        if (result2.status && result2.data) {
                            location.reload();
                        } else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erroa ao guardar os detalhes do seu ticket. Consulte a consola para mais informações.");
                        }
                    });
                }
            }
        });
    });

    $("#selected-status").on("change", async function () {
        let status = $("#selected-status").data("selected");

        let formData : any[string] = {
            // @ts-ignore
            id: getParameter("ticket"),
            status: status
        };

        if(status == TicketStatus.CLOSED) {
            await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
                if (result.status) {
                    if ("data" in result) {
                        const today = new Date();
                        const dd = today.getDate();
                        const mm = today.getMonth() + 1;
                        const yyyy = today.getFullYear();
                        const hour = today.getHours();
                        const minutes = today.getMinutes();
                        const seconds = today.getSeconds();

                        formData["closed_at"] = yyyy + "-" + ((mm + "").length === 1 ? "0" + mm : mm) + "-" + ((dd + "").length === 1 ? "0" + dd : dd) + " " + ((hour + "").length === 1 ? "0" + hour : hour) + ":" + ((minutes + "").length === 1 ? "0" + minutes : minutes) + ":" + ((seconds + "").length === 1 ? "0" + seconds : seconds);
                        formData["closed_by"] = result.data[0]?.id;
                    }
                }
            });
        } else {
            formData["closed_at"] = null;
            formData["closed_by"] = null;
        }

        await API.requestType("Ticket", "update", formData, [], null, true).then((result2: any) => {
            if (result2.status && result2.data) {
                location.reload();
            } else {
                // @ts-ignore
                cyrusAlert("danger", "Ocorreu um erroa ao mudar o estado do ticket. Consulte a consola para mais informações.");
            }
        });
    });

    API.requestService("utilities", "getRouting", {}, []).then((result: any) => {
        if (result.status) {
            if ("data" in result) {

            }
        }

    });
});
