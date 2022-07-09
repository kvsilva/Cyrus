import {Request as API} from "../../../../../resources/js/Request.js";
import {TicketFlags, TicketMessage} from "../../../../../resources/js/models";

let Routing: any[string];

$(document).ready(async function () {
    await API.requestService("Utilities", "getRouting", {}, []).then((result: any) => {
        Routing = result.data;
    });
    query();
    $("#search").on("input", function () {
        // @ts-ignore
        let search : string = $("#search").val();
        let status : number = $("#selected-status").data("selected");
        query(search,status);
    });
    $("#selected-status").on("change", function () {
        // @ts-ignore
        let search : string = $("#search").val();
        let status : number = $("#selected-status").data("selected");
        query(search,status);
    });
});

//@ts-ignore
window.query = (search: string, status: null|number = null) => query(search, status);

function query(search: string = "", status: null|number = null){
    if(search.length > 0) search = "%" + search + "%";
    API.requestService("Session", "getSession", {}, []).then((result: any) => {
        if (result.status) {
            if ("data" in result) {
                    let user = result.data[0]?.id;

                    let formData : any[string] = {
                        subject: search,
                        user: user
                    }

                    if(status !== null) formData["status"] = status;

                    API.requestType("ticket", "query", formData, [TicketFlags.ALL.name], false, true, "like").then((result: any) => {
                        $("#tickets-table").html("");
                        if(result.data) {
                            for (let i = 0; i < result.data.length; i++) {
                                let item = result.data[i];
                                let messages: TicketMessage[] = result.data[i].messages;
                                let lastUpdate = messages[messages.length - 1];
                                let ye = new Intl.DateTimeFormat('pt', {year: 'numeric'}).format(lastUpdate?.sent_at);
                                let mo = new Intl.DateTimeFormat('pt', {month: '2-digit'}).format(lastUpdate?.sent_at);
                                let da = new Intl.DateTimeFormat('pt', {day: '2-digit'}).format(lastUpdate?.sent_at);
                                let hour = new Intl.DateTimeFormat('pt', {hour: '2-digit'}).format(lastUpdate?.sent_at);
                                let min = new Intl.DateTimeFormat('pt', {minute: '2-digit'}).format(lastUpdate?.sent_at);
                                let date: string = `${da}/` + mo[0].toUpperCase() + mo.substring(1) + `/${ye} ${hour}:${min}`;
                                $("#tickets-table").append($("<tr>").append(
                                        $("<td>").append(
                                            $("<a>").attr("class", "cyrus-feed-view-link").attr("href", Routing.tickets + "?ticket=" + item?.id).html(item?.subject)
                                        )
                                    ).append(
                                        $("<td>").html(item?.id)
                                    ).append(
                                        $("<td>").html(date)
                                    ).append(
                                        $("<td>").html(item?.status?.name)
                                    )
                                );
                            }
                        }
                    });
            }
        }
    });
}