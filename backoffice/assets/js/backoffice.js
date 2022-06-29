var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../resources/js/Request";
import { UserFlags } from "../../../resources/js/models";
$(document).ready(function () {
    $("input[type=submit]").click(function () {
        let formName = $(this).data("form");
        let formEntity = $(this).data("entity");
        let formData = {};
        createDataArray(formName, formData).then(value => {
            formData = value;
            API.requestType(formEntity, "insert", formData, [UserFlags.VIDEOHISTORY.name]).then((result) => {
                if (result.status) {
                    if ("data" in result) {
                    }
                }
            });
        });
    });
});
function createDataArray(formName, formData) {
    return __awaiter(this, void 0, void 0, function* () {
        let files = [];
        $("[data-form='" + formName + "'").each(function () {
            let name = $(this).data("name");
            let value = null;
            if ($(this).attr("type") != "submit" && $(this).attr("type") != "reset") {
                if ($(this).is("input") || $(this).is("textarea")) {
                    value = $(this).val();
                }
                else if ($(this).data("ismultiple")) {
                    if ($(this).data("selectedsection")) {
                        let selectedSection = $(this).data("selectedsection");
                        $(this).find("[data-section='" + selectedSection + "']").each(function () {
                            let subItemData = {};
                            $(this).find("[data-subitem]").each(function () {
                                let nameMultiple = $(this).data("subitem");
                                let valueMultiple = null;
                                if ($(this).attr("type") != "submit" && $(this).attr("type") != "reset") {
                                    if ($(this).is("input") || $(this).is("textarea")) {
                                        if ($(this).attr("type") == "file") {
                                            if ($(this).prop("files").length > 0) {
                                                files.push($(this).prop("files")[0]);
                                            }
                                            else {
                                                console.log("Nenhum ficheiro detectado.");
                                            }
                                        }
                                        valueMultiple = $(this).val();
                                    }
                                    else if ($(this).data("ismultiple")) {
                                        console.error("Multiple is not allowed inside another multiple element.");
                                    }
                                    else if ($(this).data("isdropdown")) {
                                        valueMultiple = $(this).data("selected");
                                    }
                                    else {
                                        console.log("Other-Section: ");
                                        console.log($(this));
                                    }
                                    if (valueMultiple !== null && $.trim(valueMultiple).length == 0)
                                        valueMultiple = null;
                                    subItemData[nameMultiple] = valueMultiple;
                                }
                            });
                            // colocar para converter para nulo se n existir;
                            let isNull = true;
                            for (const item in subItemData) {
                                if (subItemData[item] !== null) {
                                    isNull = false;
                                    break;
                                }
                            }
                            if (isNull)
                                subItemData = null;
                            if (subItemData !== null && $(this).data("service") && $(this).data("action")) {
                                let service = $(this).data("service");
                                let action = $(this).data("action");
                                subItemData["process"] = {
                                    "service": service,
                                    "action": action
                                };
                            }
                            value = subItemData;
                        });
                    }
                }
                else if ($(this).data("isdropdown")) {
                    value = $(this).data("selected");
                }
                else {
                    console.log("Other: ");
                    console.log($(this));
                }
                if (value !== null && $.trim(value).length == 0)
                    value = null;
                formData[name] = value;
            }
        });
        let i = -1;
        for (const item in formData) {
            if (formData[item] !== null && typeof formData[item] === "object" && "process" in formData[item]) {
                let service = formData[item]["process"].service;
                let action = formData[item]["process"].action;
                delete formData[item].process;
                if (service === "Resources" && action !== "registerFile") {
                    i++;
                    yield API.uploadFile(files[i]).then((result) => __awaiter(this, void 0, void 0, function* () {
                        if (result.status) {
                            if ("data" in result) {
                                let resourceRequest = {};
                                for (const n in formData[item]) {
                                    resourceRequest[n] = formData[item][n];
                                }
                                resourceRequest["file"] = result.data;
                                yield API.requestService("Resources", action, resourceRequest, []).then((result) => __awaiter(this, void 0, void 0, function* () {
                                    if (result.status) {
                                        if ("data" in result) {
                                            formData[item] = result.data[0];
                                        }
                                    }
                                }));
                            }
                        }
                    }));
                }
                else if (service === "Resources" && action === "registerFile") {
                    i++;
                    let resourceRequest = {};
                    for (const n in formData[item]) {
                        resourceRequest[n] = formData[item][n];
                    }
                    yield API.requestService("Resources", "registerFile", resourceRequest, []).then((result) => {
                        if (result.status) {
                            if ("data" in result) {
                                formData[item] = result.data[0];
                            }
                        }
                    });
                }
            }
        }
        return formData;
    });
}
