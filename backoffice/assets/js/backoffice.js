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
import { Availability, Language, UserFlags } from "../../../resources/js/models";
import { cyrusAlert } from "../../../resources/js/cyrus";
let entity;
let rows = [];
let detailsModal;
let updateModal;
$(document).ready(function () {
    return __awaiter(this, void 0, void 0, function* () {
        entity = $("#entity-data").data("entity");
        // @ts-ignore
        detailsModal = new bootstrap.Modal($("#detailsModal"), {});
        // @ts-ignore
        updateModal = new bootstrap.Modal($("#updateModal"), {});
        yield API.requestType(entity, "query", { "available": Availability.BOTH }).then((result) => {
            if (result.status && result.data) {
                for (let i = 0; i < result.data.length; i++) {
                    let tr = $("<tr>");
                    let item = result.data[i];
                    $(tr).data("id", item === null || item === void 0 ? void 0 : item.id);
                    let id = item === null || item === void 0 ? void 0 : item.id;
                    let originalItem = result.original[i];
                    for (const index in originalItem) {
                        let td = $("<td>").attr("class", "cyrus-scrollbar backoffice-td");
                        let value;
                        if (item[index] !== null && typeof item[index] === 'object') {
                            if (item[index] instanceof Language) {
                                value = item[index].original_name + " (" + item[index].code + ")";
                            }
                            else {
                                if ("name" in item[index]) {
                                    value = item[index].name;
                                }
                                if ("path" in item[index]) {
                                    value = item[index].path;
                                }
                            }
                        }
                        else {
                            value = item[index];
                        }
                        if (value === null)
                            value = "(Nenhum)";
                        if (id !== null) {
                            if (rows[id] === undefined)
                                rows[id] = { plainText: [], original: [] };
                            rows[id].plainText[index] = value;
                            rows[id].original[index] = item[index];
                        }
                        $(td).text(value);
                        tr.append(td);
                    }
                    $("#query-body").append(tr);
                }
            }
        });
        $("tr").dblclick(function () {
            // @ts-ignore
            let id = $(this).data("id");
            let modalBody = $("#details-body").html("");
            let item = rows[id].plainText;
            for (const index in item) {
                let field_name = index;
                field_name = field_name.replace("_", " ");
                field_name = field_name.split(" ");
                field_name = field_name.map((word) => {
                    return word[0].toUpperCase() + word.substring(1);
                }).join(" ");
                modalBody.append($("<div>").html("<span><u><b>" + field_name + "</b></u></span>:<span class ='p-2'>" + item[index] + "</span>"));
            }
            $("#btn-details-remove").data("id", id);
            $("#btn-details-edit").data("id", id);
            detailsModal.show();
        });
        $("#btn-details-edit").click(function () {
            // @ts-ignore
            detailsModal.hide();
            $("#btn-update").data("id", $("#btn-details-remove").data("id"));
            let id = $(this).data("id");
            $("#updateModal").find("[data-name]").each(function () {
                let name = $(this).data("name");
                if ($(this).is("input")) {
                    $(this).val(rows[id].original[name]);
                    $(this).attr('value', rows[id].original[name]);
                }
                else if ($(this).data("ismultiple") && rows[id].original[name] !== null) {
                    $(this).find("[data-subitem]").each(function () {
                        if ($(this).is("input")) {
                            let subItemName = $(this).data("subitem");
                            $(this).val(rows[id].original[name][subItemName]);
                            $(this).attr('value', rows[id].original[name][subItemName]);
                        }
                    });
                }
                else if ($(this).data("isdropdown")) {
                    $(this).parent().parent().find(".dropdown-menu li").each(function () {
                        if ("id" in rows[id].original[name] && $(this).data("id") == rows[id].original[name]["id"]) {
                            $(this).trigger("click");
                        }
                        else if ("value" in rows[id].original[name] && $(this).data("id") == rows[id].original[name]["value"]) {
                            $(this).trigger("click");
                        }
                    });
                }
                else if ($(this).data("isdetailed")) {
                    let object = "null";
                    $(this).find(".model-update-details-items").removeClass("cyrus-item-hidden");
                    if (rows[id].original[name] !== null) {
                        object = JSON.stringify(rows[id].original[name]);
                        for (const index in rows[id].original[name]) {
                            $(this).find("[data-item='" + index + "']").html(rows[id].original[name][index]);
                        }
                        $(this).click(function () {
                            $(this).data("object", "null");
                            $(this).find(".model-update-details-items").addClass("cyrus-item-hidden");
                        });
                    }
                    else {
                        $(this).off("click");
                        $(this).find(".model-update-details-items").addClass("cyrus-item-hidden");
                    }
                    $(this).data("object", object);
                }
            });
            updateModal.show();
        });
        $("#btn-details-remove").click(function () {
            API.requestType(entity, "remove", { "id": $(this).data("id") }).then((result) => {
                if (result.status) {
                    cyrusAlert("success", result.description);
                }
                else {
                    cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                    console.error(result);
                }
                detailsModal.hide();
            });
        });
        $("input[type=submit]").click(function () {
            let formName = $(this).data("form");
            let formEntity = $(this).data("entity");
            let formAction = $(this).data("action");
            let formData = {};
            createDataArray(formName, formData, formAction).then(value => {
                formData = value;
                API.requestType(formEntity, formAction, formData, [UserFlags.VIDEOHISTORY.name]).then((result) => {
                    if (result.status) {
                        cyrusAlert("success", result.description);
                    }
                    else {
                        cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                    }
                });
            });
        });
    });
});
function createDataArray(formName, formData, action) {
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
                else if ($(this).data("isdetailed")) {
                    value = $(this).data("object") === "null" ? null : undefined;
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
        if (action === "update") {
            let entityId = $("#btn-update").data("id");
            let item = rows[entityId].original;
            formData = compareRecords(item, formData);
            for (const index in formData) {
                if (formData[index] === undefined)
                    delete formData[index];
            }
            if (formData === undefined) {
                formData = { id: entityId };
            }
            else
                formData["id"] = entityId;
        }
        let ret = {};
        for (const index in formData) {
            // @ts-ignore
            ret[index] = formData[index];
        }
        return ret;
    });
}
// [B]efore
// [N]ew
function compareRecords(b, n) {
    let data;
    for (const index in b) {
        if (b[index] !== null && typeof b[index] === 'object') {
            n[index] = (n === null || n[index] === null) ? null : compareRecords(b[index], n[index]);
        }
        else {
            if (b !== undefined && n !== undefined && b !== null && n !== null && b[index] == n[index]) {
                n[index] = undefined;
            }
        }
        if (data === undefined)
            data = [];
        data[index] = n !== undefined ? (n !== null ? n[index] : null) : undefined;
    }
    let isUndefined = true;
    for (const index in data) {
        if (data[index] !== undefined) {
            isUndefined = false;
        }
    }
    if (isUndefined)
        return undefined;
    return data;
}
