var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../../../resources/js/Request.js";
import { TicketMessageFlags, TicketStatus } from "../../../../../resources/js/models";
$(document).ready(function () {
    // Change to Cyrus.ts
    // END
    $("#form0-textarea").on("input", function () {
        if ($.trim($(this).val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        }
        else {
            $("#form0-submit").prop("disabled", true);
        }
    });
    $("#form0-submit").click(function () {
        $("#form0-submit").prop("disabled", true);
        API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
            var _a;
            if (result.status) {
                if ("data" in result) {
                    let formData = {
                        // @ts-ignore
                        ticket: getParameter("ticket"),
                        content: $("#form0-textarea").val(),
                        author: (_a = result.data[0]) === null || _a === void 0 ? void 0 : _a.id,
                        relations: {}
                    };
                    let attachments = [];
                    for (let i = 0; i < $("#form0-attachments").prop("files").length; i++) {
                        yield API.uploadFile($("#form0-attachments").prop("files")[i]).then((result2) => __awaiter(this, void 0, void 0, function* () {
                            if (result2.status && result2.data) {
                                yield API.requestService("Resources", "uploadFile", {
                                    file: result2.data,
                                }).then((result3) => {
                                    if (result3.status && result3.data) {
                                        attachments.push(result3.data[0]);
                                    }
                                    else {
                                        // @ts-ignore
                                        cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                                        console.error(result3);
                                        $("#form0-submit").prop("disabled", false);
                                    }
                                });
                            }
                            else {
                                // @ts-ignore
                                cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                                console.error(result2);
                                $("#form0-submit").prop("disabled", false);
                            }
                        }));
                    }
                    formData["relations"][TicketMessageFlags.TICKETMESSAGEATTACHMENTS.name] = attachments;
                    yield API.requestType("TicketMessage", "insert", formData, [], null, true).then((result2) => __awaiter(this, void 0, void 0, function* () {
                        var _b, _c;
                        if (result2.status && result2.data) {
                            // @ts-ignore
                            cyrusAlert("success", "Atualizando o ticket...");
                            yield API.requestService("Tickets", "ticketUpdated", {
                                id: (_c = (_b = result2.data[0]) === null || _b === void 0 ? void 0 : _b.ticket) === null || _c === void 0 ? void 0 : _c.id
                            });
                            $("#form0-submit").prop("disabled", false);
                            location.reload();
                        }
                        else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erro ao guardar os detalhes do seu ticket. Consulte a consola para mais informações.");
                            $("#form0-submit").prop("disabled", false);
                        }
                    }));
                }
                else {
                    $("#form0-submit").prop("disabled", false);
                }
            }
            else {
                $("#form0-submit").prop("disabled", false);
            }
        }));
    });
    $("#selected-status").on("change", function () {
        return __awaiter(this, void 0, void 0, function* () {
            let status = $("#selected-status").data("selected");
            let formData = {
                // @ts-ignore
                id: getParameter("ticket"),
                status: status
            };
            if (status == TicketStatus.CLOSED.value) {
                yield API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
                    var _a;
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
                            formData["closed_by"] = (_a = result.data[0]) === null || _a === void 0 ? void 0 : _a.id;
                        }
                    }
                }));
            }
            else {
                formData["closed_at"] = null;
                formData["closed_by"] = null;
            }
            yield API.requestType("Ticket", "update", formData, [], null, true).then((result2) => __awaiter(this, void 0, void 0, function* () {
                if (result2.status && result2.data) {
                    //@ts-ignore
                    cyrusAlert("success", "Alterando o estado do ticket...");
                    yield API.requestService("Tickets", "ticketStatusUpdated", {
                        //@ts-ignore
                        id: getParameter("ticket")
                    });
                    location.reload();
                }
                else {
                    // @ts-ignore
                    cyrusAlert("danger", "Ocorreu um erroa ao mudar o estado do ticket. Consulte a consola para mais informações.");
                }
            }));
        });
    });
    $("#responsible").on("click", function () {
        return __awaiter(this, void 0, void 0, function* () {
            let formData = {
                // @ts-ignore
                id: getParameter("ticket"),
                responsible: null
            };
            yield API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
                var _a;
                if (result.status) {
                    if ("data" in result) {
                        formData.responsible = (_a = result.data[0]) === null || _a === void 0 ? void 0 : _a.id;
                    }
                }
            }));
            yield API.requestType("Ticket", "update", formData, [], null, true).then((result2) => __awaiter(this, void 0, void 0, function* () {
                if (result2.status && result2.data) {
                    //@ts-ignore
                    cyrusAlert("success", "Assumindo o ticket...");
                    yield API.requestService("Tickets", "ticketAssumed", {
                        //@ts-ignore
                        id: getParameter("ticket")
                    });
                    location.reload();
                }
                else {
                    // @ts-ignore
                    cyrusAlert("danger", "Ocorreu um erroa ao assumir o ticket. Consulte a consola para mais informações.");
                }
            }));
        });
    });
    API.requestService("utilities", "getRouting", {}, []).then((result) => {
        if (result.status) {
            if ("data" in result) {
            }
        }
    });
});
