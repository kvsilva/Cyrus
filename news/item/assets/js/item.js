var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../../resources/js/Request";
//import {cyrusAlert} from "../../../../resources/js/cyrus";
import { NewsFlags } from "../../../../resources/js/models";
$(document).ready(function () {
    return __awaiter(this, void 0, void 0, function* () {
        $("#form0-description").on("input", function () {
            // @ts-ignore
            $(this).parent().nextAll(".reviews-self-char-notification").html($(this).val().length + "/" + $(this).attr("maxlength") + " caracteres.");
        });
        $("#form0-description").on("input", function () {
            if ($.trim($("#form0-description").val()).length > 0) {
                $("#form0-submit").prop("disabled", false);
            }
            else {
                $("#form0-submit").prop("disabled", true);
            }
        });
        $("#form0").submit(function (e) {
            e.preventDefault();
        });
        $("#form0-submit").click(function (e) {
            return __awaiter(this, void 0, void 0, function* () {
                e.preventDefault();
                let description = $("#form0-description").val();
                yield API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
                    if (result.status) {
                        if ("data" in result) {
                            let user = result.data[0];
                            // @ts-ignore
                            cyrusAlert("warning", "Processando o seu pedido...");
                            API.requestType("News", "update", {
                                "id": getParameter("news"),
                                "relations": {
                                    "COMMENTNEWS": [
                                        {
                                            "user": user === null || user === void 0 ? void 0 : user.id,
                                            "post_date": null,
                                            "description": description
                                        }
                                    ]
                                }
                            }, [], false).then((result) => {
                                if (result.status) {
                                    // @ts-ignore
                                    cyrusAlert("success", result.description);
                                    $("#form0").trigger("reset");
                                    $("#form0-description").parent().nextAll(".reviews-self-char-notification").html("0/" + $("#form0-description").attr("maxlength") + " caracteres.");
                                    dataQuery();
                                }
                                else {
                                    // @ts-ignore
                                    cyrusAlert("danger", "Ocorreu um erro ao processar o seu pedido!");
                                }
                            });
                        }
                    }
                }));
            });
        });
        dataQuery();
    });
});
// @ts-ignore
window.dataQueryPeronal = () => dataQuery();
function dataQuery() {
    return __awaiter(this, void 0, void 0, function* () {
        $("#reviews-list").html("");
        let formData = {
            "id": getParameter("news"),
        };
        yield API.requestType("News", "query", formData, [NewsFlags.COMMENTNEWS.name], false, true).then((result) => {
            var _a, _b, _c;
            if (result.status) {
                if (result.data) {
                    let results = result.data[0];
                    for (let i = results.comments.length - 1; i >= 0; i--) {
                        let item = results.comments[i];
                        let ye = new Intl.DateTimeFormat('pt', { year: 'numeric' }).format(item === null || item === void 0 ? void 0 : item.date);
                        let mo = new Intl.DateTimeFormat('pt', { month: 'long' }).format(item === null || item === void 0 ? void 0 : item.date);
                        let da = new Intl.DateTimeFormat('pt', { day: '2-digit' }).format(item === null || item === void 0 ? void 0 : item.date);
                        let date = `${da} de ` + mo[0].toUpperCase() + mo.substring(1) + ` de ${ye}`;
                        $("#reviews-list").append($("<div>").attr("class", "review").append($("<div>").attr("class", "row").append($("<div>").attr("class", "col-2 review-post-user no-select").append($("<img>").prop("draggable", false).attr("class", "mx-auto").attr("src", (_b = (_a = item === null || item === void 0 ? void 0 : item.user) === null || _a === void 0 ? void 0 : _a.profile_image) === null || _b === void 0 ? void 0 : _b.path))).append($("<div>").attr("class", "col-9").append($("<span>").append($("<span>").attr("class", "review-username me-2").html((_c = item === null || item === void 0 ? void 0 : item.user) === null || _c === void 0 ? void 0 : _c.username)).append($("<span>").attr("class", "review-date float-right").html(date))).append($("<div>").attr("class", "mt-3").append($("<div>").attr("class", "review-description").attr("data-collapsible", "true").append($("<p>").html(item === null || item === void 0 ? void 0 : item.description)))).append($("<div>").append(
                        //<button data-collapse = "true" class = "cyrus-btn cyrus-btn-simple">MOSTRAR MAIS</button>
                        $("<button>").attr("class", "cyrus-btn cyrus-btn-simple").text("MOSTRAR MAIS").attr("data-collapse", "true").click(function () {
                            if ($(this).data("collapse") === true) {
                                $(this).parent().parent().find("[data-collapsible]").addClass("expanded");
                                $(this).text("MOSTRAR MENOS");
                                $(this).data("collapse", false);
                            }
                            else {
                                $(this).data("collapse", true);
                                $(this).parent().parent().find("[data-collapsible]").removeClass("expanded");
                                $(this).text("MOSTRAR MAIS");
                            }
                        }))))));
                    }
                }
            }
            else {
                // @ts-ignore
                cyrusAlert("danger", "Ocorreu um erro ao processar o seu pedido!");
                console.error(result);
            }
        });
    });
}
function getParameter(parameter) {
    // Address of the current window
    let address = window.location.search;
    // Returns a URLSearchParams object instance
    let parameterList = new URLSearchParams(address);
    // Returning the respected value associated
    // with the provided key
    return parameterList.get(parameter);
}
