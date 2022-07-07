import { Request as API } from "../../../../../resources/js/Request.d.ts";
$(document).ready(function () {
    API.requestService("utilities", "getRouting", {}, []).then((result) => {
        if (result.status) {
            if ("data" in result) {
            }
        }
    });
});
