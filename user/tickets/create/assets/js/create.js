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
    $("#form0-subject").on("input", function () {
        if ($.trim($("#form0-subject").val()).length > 0 && $.trim($("#form0-description").val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        }
        else
            $("#form0-submit").prop("disabled", true);
    });
    $("#form0-description").on("input", function () {
        if ($.trim($("#form0-subject").val()).length > 0 && $.trim($("#form0-description").val()).length > 0) {
            $("#form0-submit").prop("disabled", false);
        }
        else
            $("#form0-submit").prop("disabled", true);
    });
    $("#form0-submit").click(function (e) {
        e.preventDefault();
        if (($.trim($("#form0-subject").val()).length == 0 || $.trim($("#form0-description").val()).length == 0)) {
            //@ts-ignore
            cyrusAlert("warning", "Há campos por preencher!");
            return;
        }
        API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
            var _a;
            if (result.status) {
                if ("data" in result) {
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
                                        //@ts-ignore
                                        cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                                        console.error(result3);
                                    }
                                });
                            }
                            else {
                                //@ts-ignore
                                cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                                console.error(result2);
                            }
                        }));
                    }
                    yield API.requestType("Ticket", "insert", {
                        user: (_a = result.data[0]) === null || _a === void 0 ? void 0 : _a.id,
                        subject: $("#form0-subject").val(),
                        status: TicketStatus.OPEN.value,
                    }).then((result2) => __awaiter(this, void 0, void 0, function* () {
                        var _b, _c;
                        if (result2.status && result2.data) {
                            let formData = {
                                ticket: (_b = result2.data[0]) === null || _b === void 0 ? void 0 : _b.id,
                                content: $("#form0-description").val(),
                                author: (_c = result.data[0]) === null || _c === void 0 ? void 0 : _c.id,
                                relations: {}
                            };
                            formData["relations"][TicketMessageFlags.TICKETMESSAGEATTACHMENTS.name] = attachments;
                            yield API.requestType("TicketMessage", "insert", formData, [], null, true).then((result2) => {
                                if (result2.status && result2.data) {
                                    //@ts-ignore
                                    cyrusAlert("success", "Ticket criado com sucesso! Redirecionando...");
                                    setTimeout(function () {
                                        var _a, _b, _c, _d;
                                        return __awaiter(this, void 0, void 0, function* () {
                                            yield API.requestService("Tickets", "ticketCreated", {
                                                id: (_b = (_a = result2.data[0]) === null || _a === void 0 ? void 0 : _a.ticket) === null || _b === void 0 ? void 0 : _b.id
                                            });
                                            window.location.href = new URL("../../../?ticket=" + ((_d = (_c = result2.data[0]) === null || _c === void 0 ? void 0 : _c.ticket) === null || _d === void 0 ? void 0 : _d.id), import.meta.url).href;
                                        });
                                    }, 2000);
                                }
                                else {
                                    //@ts-ignore
                                    cyrusAlert("danger", "Ocorreu um erroa ao guardar os detalhes do seu ticket. Consulte a consola para mais informações.");
                                }
                            });
                        }
                        else {
                            //@ts-ignore
                            cyrusAlert("danger", "Ocorreu um erroa ao criar o seu ticket. Consulte a consola para mais informações.");
                        }
                    }));
                }
            }
            else {
                //@ts-ignore
                cyrusAlert("danger", "Ocorreu um erroa ao criar o seu ticket. Inicie sessão.");
            }
        }));
    });
});
