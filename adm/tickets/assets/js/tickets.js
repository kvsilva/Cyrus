var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../../resources/js/Request.js";
import { TicketFlags } from "../../../../resources/js/models";
let Routing;
$(document).ready(function () {
    return __awaiter(this, void 0, void 0, function* () {
        yield API.requestService("Utilities", "getRouting", {}, []).then((result) => {
            Routing = result.data;
        });
        query();
        $("#search").on("input", function () {
            // @ts-ignore
            let search = $("#search").val();
            let status = $("#selected-status").data("selected");
            let user = $("#selected-user").data("selected");
            query(search, status, user);
        });
        $("#selected-status").on("change", function () {
            // @ts-ignore
            let search = $("#search").val();
            let status = $("#selected-status").data("selected");
            let user = $("#selected-user").data("selected");
            query(search, status, user);
        });
        $("#selected-user").on("change", function () {
            // @ts-ignore
            let search = $("#search").val();
            let status = $("#selected-status").data("selected");
            let user = $("#selected-user").data("selected");
            query(search, status, user);
        });
    });
});
//@ts-ignore
window.query = (search, status = null, user = null) => query(search, status, user);
function query(search = "", status = null, user = null) {
    if (search.length > 0)
        search = "%" + search + "%";
    let formData = {
        subject: search,
    };
    if (status !== null)
        formData["status"] = status;
    if (user !== null)
        formData["user"] = user;
    API.requestType("ticket", "query", formData, [TicketFlags.ALL.name], false, true, "like").then((result) => {
        var _a, _b, _c;
        $("#tickets-table").html("");
        if (result.data) {
            for (let i = 0; i < result.data.length; i++) {
                let item = result.data[i];
                let messages = result.data[i].messages;
                let lastUpdate = messages[messages.length - 1];
                let ye = new Intl.DateTimeFormat('pt', { year: 'numeric' }).format(lastUpdate === null || lastUpdate === void 0 ? void 0 : lastUpdate.sent_at);
                let mo = new Intl.DateTimeFormat('pt', { month: '2-digit' }).format(lastUpdate === null || lastUpdate === void 0 ? void 0 : lastUpdate.sent_at);
                let da = new Intl.DateTimeFormat('pt', { day: '2-digit' }).format(lastUpdate === null || lastUpdate === void 0 ? void 0 : lastUpdate.sent_at);
                let hour = new Intl.DateTimeFormat('pt', { hour: '2-digit' }).format(lastUpdate === null || lastUpdate === void 0 ? void 0 : lastUpdate.sent_at);
                let min = new Intl.DateTimeFormat('pt', { minute: '2-digit' }).format(lastUpdate === null || lastUpdate === void 0 ? void 0 : lastUpdate.sent_at);
                let date = `${da}/` + mo[0].toUpperCase() + mo.substring(1) + `/${ye} ${hour}:${min}`;
                $("#tickets-table").append($("<tr>").append($("<td>").append($("<a>").attr("class", "cyrus-feed-view-link").attr("href", Routing.tickets + "?ticket=" + (item === null || item === void 0 ? void 0 : item.id)).html(item === null || item === void 0 ? void 0 : item.subject))).append($("<td>").html(item === null || item === void 0 ? void 0 : item.id)).append($("<td>").html((_a = item === null || item === void 0 ? void 0 : item.user) === null || _a === void 0 ? void 0 : _a.username)).append($("<td>").html((item === null || item === void 0 ? void 0 : item.responsible) !== null ? (_b = item === null || item === void 0 ? void 0 : item.responsible) === null || _b === void 0 ? void 0 : _b.username : "Nenhum")).append($("<td>").html(date)).append($("<td>").html((_c = item === null || item === void 0 ? void 0 : item.status) === null || _c === void 0 ? void 0 : _c.name)));
            }
        }
    });
}
