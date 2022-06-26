import { Request as API } from "../../../resources/js/Request.js";
let Routing = {};
$(document).ready(function () {
    API.requestService("utilities", "getRouting", {}, []).then((result) => {
        if (result.status) {
            if ("data" in result) {
                Routing = result.data;
            }
        }
        console.log(Routing);
    });
});
