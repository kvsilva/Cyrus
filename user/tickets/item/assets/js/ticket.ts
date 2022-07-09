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
        $("#form0-submit").prop("disabled", true);
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
                                        $("#form0-submit").prop("disabled", false);
                                    }
                                });
                            } else {
                                // @ts-ignore
                                cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                                console.error(result2);
                                $("#form0-submit").prop("disabled", false);
                            }
                        });
                    }
                    formData["relations"][TicketMessageFlags.TICKETMESSAGEATTACHMENTS.name] = attachments;
                    await API.requestType("TicketMessage", "insert", formData, [], null, true).then(async (result2: any) => {
                        if (result2.status && result2.data) {
                            // @ts-ignore
                            cyrusAlert("success", "Atualizando o ticket...");
                            await API.requestService("Tickets", "ticketUpdated", {
                                id: result2.data[0]?.ticket?.id
                            });
                            $("#form0-submit").prop("disabled", false);
                            location.reload();
                        } else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erro ao guardar os detalhes do seu ticket. Consulte a consola para mais informações.");
                            $("#form0-submit").prop("disabled", false);
                        }
                    });
                } else {
                    $("#form0-submit").prop("disabled", false);
                }
            } else {
                $("#form0-submit").prop("disabled", false);
            }
        });
    });

    $("#selected-status").on("change", async function () {
        let status = $("#selected-status").data("selected");

        let formData: any[string] = {
            // @ts-ignore
            id: getParameter("ticket"),
            status: status
        };

        if (status == TicketStatus.CLOSED.value) {
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

        await API.requestType("Ticket", "update", formData, [], null, true).then(async (result2: any) => {
            if (result2.status && result2.data) {
                //@ts-ignore
                cyrusAlert("success", "Alterando o estado do ticket...");
                await API.requestService("Tickets", "ticketStatusUpdated", {
                    //@ts-ignore
                    id: getParameter("ticket")
                });
                location.reload();
            } else {
                // @ts-ignore
                cyrusAlert("danger", "Ocorreu um erroa ao mudar o estado do ticket. Consulte a consola para mais informações.");
            }
        });
    });

    $("#responsible").on("click", async function () {

        let formData: any[string] = {
            // @ts-ignore
            id: getParameter("ticket"),
            responsible: null
        };

        await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
            if (result.status) {
                if ("data" in result) {
                    formData.responsible = result.data[0]?.id;
                }
            }
        });

        await API.requestType("Ticket", "update", formData, [], null, true).then(async (result2: any) => {
            if (result2.status && result2.data) {
                //@ts-ignore
                cyrusAlert("success", "Assumindo o ticket...");
                await API.requestService("Tickets", "ticketAssumed", {
                    //@ts-ignore
                    id: getParameter("ticket")
                });
                location.reload();
            } else {
                // @ts-ignore
                cyrusAlert("danger", "Ocorreu um erroa ao assumir o ticket. Consulte a consola para mais informações.");
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
