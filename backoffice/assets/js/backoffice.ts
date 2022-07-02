import {Request as API} from "../../../resources/js/Request";
import {Availability, flags, Language, UserFlags} from "../../../resources/js/models";
import {Modal} from "bootstrap";
import {cyrusAlert} from "../../../resources/js/cyrus";

let entity: string;

let rows: any[number] = [];

let detailsModal: Modal;
let updateModal: Modal;
let relationsModal: Modal | null = null;

$(document).ready(async function () {
    entity = $("#entity-data").data("entity");
    // @ts-ignore
    detailsModal = new bootstrap.Modal($("#detailsModal"), {});
    // @ts-ignore
    updateModal = new bootstrap.Modal($("#updateModal"), {});
    // @ts-ignore
    if ($("#relationsModal").length > 0) relationsModal = new bootstrap.Modal($("#relationsModal"), {});
    await API.requestType(entity, "query", {"available": Availability.BOTH}).then((result: any) => {
        if (result.status && result.data) {
            for (let i = 0; i < result.data.length; i++) {
                let tr = $("<tr>");
                let item = result.data[i];
                $(tr).data("id", item?.id);
                let id = item?.id;
                let originalItem = result.original[i];

                for (const index in originalItem) {
                    if(flags[entity + "Flags"] !== undefined && (index.toUpperCase() in flags[entity + "Flags"] || index.replace("_","").toUpperCase() in flags[entity + "Flags"])) continue;
                    let td = $("<td>").attr("class", "cyrus-scrollbar backoffice-td");
                    let value;

                    if (item[index] !== null && typeof item[index] === 'object' && !Array.isArray(item[index])) {
                        if (item[index] instanceof Language) {
                            value = item[index].original_name + " (" + item[index].code + ")";
                        } else {
                            if ("name" in item[index]) {
                                value = item[index].name;
                            }
                            if ("path" in item[index]) {
                                value = item[index].path;
                            }
                        }
                    } else {
                        value = item[index];
                    }
                    if (value === null) value = "(Nenhum)";
                    if (id !== null) {
                        if (rows[id] === undefined) rows[id] = {plainText: [], original: []};
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
            let field_name: String | String[] = index;
            field_name = field_name.replace("_", " ");

            field_name = field_name.split(" ");


            field_name = field_name.map((word) => {
                return word[0].toUpperCase() + word.substring(1);
            }).join(" ");

            modalBody.append($("<div>").html(
                "<span><u><b>" + field_name + "</b></u></span>:<span class ='p-2'>" + item[index] + "</span>")
            );
        }

        $("#btn-details-remove").data("id", id);
        $("#btn-details-edit").data("id", id);
        $("#btn-details-relations").data("id", id);

        detailsModal.show();
    });
    $("#btn-details-relations").each(function () {
        $(this).click(function () {
            let id = $("#btn-details-relations").data("id");
            $(this).data("id", id);

            API.requestType(entity, "query", {
                "id": id,
                "available": Availability.BOTH
            }, [UserFlags.ALL.name]).then((result: any) => {
                if (result.status && result.data) {
                    for (let i = 0; i < result.data.length; i++) {
                        let item = result.data[i];
                        for (const index in item) {
                            if (item[index] !== null && Array.isArray(item[index])) {
                                let relationItems = $(".model-update-details-items[data-childentity='" + index + "']");
                                for (let i = 0; i < item[index].length; i++) {
                                    let element = $("<div>").attr("class", "model-update-details")
                                    for (const index3 in item[index][i]) {
                                        if (item[index][i][index3] !== null) {
                                            let item3 = item[index][i][index3];
                                            let value;
                                            if (item3 !== null && item3 !== undefined && typeof item3 === 'object') {
                                                if ("name" in item3) {
                                                    value = item3.name;
                                                }
                                                if ("path" in item3) {
                                                    value = item3.path;
                                                }
                                            } else value = item3;
                                            let name: String | String[] = index3;

                                            name = name.replace("_", " ");

                                            name = name.split(" ");


                                            name = name.map((word) => {
                                                return word[0].toUpperCase() + word.substring(1);
                                            }).join(" ");
                                            if (value === undefined || value === null) value = "(Nenhum)";
                                            element.append(`<b>${name}</b>: ${value}&nbsp;&nbsp;&nbsp;`);
                                        }
                                    }
                                    element.data("childentity", index);
                                    element.data("id", item?.id);
                                    element.data("relationid", item[index][i]?.id);
                                    element.click(function () {
                                        let childEntity : string = $(this).data("childentity");
                                        let entityID : string = $(this).data("id");
                                        let relationID : string = $(this).data("relationid");


                                        let formData : any[string]= {
                                            "id": entityID,
                                            "relations": {}
                                        };
                                        formData["relations"][childEntity] = [{
                                            "id": relationID
                                        }];

                                        API.requestType(entity, "remove", formData).then((result: any) => {
                                            if (result.status) {
                                                cyrusAlert("success", result.description);
                                            } else {
                                                cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                                                console.error(result);
                                            }
                                            detailsModal.hide();
                                        });

                                    })
                                    relationItems.append(element);
                                }

                            }
                        }
                    }
                }
            });

            detailsModal.hide();
            relationsModal?.show();
        });
    });

    $("#btn-details-edit").click(function () {
        // @ts-ignore
        detailsModal.hide();
        $("#btn-update").data("id", $("#btn-details-remove").data("id"));

        let id = $(this).data("id");
        $("#updateModal").find("[data-name]").each(function () {
            let name = $(this).data("name");
            if ($(this).is("input") || $(this).is("textarea")) {
                $(this).val(rows[id].original[name]);
                $(this).attr('value', rows[id].original[name]);
            } else if ($(this).data("ismultiple") && rows[id].original[name] !== null) {
                $(this).find("[data-subitem]").each(function () {
                    if ($(this).is("input")) {
                        let subItemName = $(this).data("subitem");
                        $(this).val(rows[id].original[name][subItemName]);
                        $(this).attr('value', rows[id].original[name][subItemName]);
                    }
                });
            } else if ($(this).data("isdropdown")) {
                $(this).parent().parent().find(".dropdown-menu li").each(function () {
                    if ("id" in rows[id].original[name] && $(this).data("id") == rows[id].original[name]["id"]) {
                        $(this).trigger("click");
                    } else if ("value" in rows[id].original[name] && $(this).data("id") == rows[id].original[name]["value"]) {
                        $(this).trigger("click");
                    }
                });
            } else if ($(this).data("isdetailed")) {
                let object = "null";
                $(this).find(".model-update-details-items").removeClass("cyrus-item-hidden");
                //$(".model-update-details-removed").addClass("cyrus-item-hidden");
                if (rows[id].original[name] !== null) {
                    object = JSON.stringify(rows[id].original[name]);
                    for (const index in rows[id].original[name]) {
                        $(this).find("[data-item='" + index + "']").html(rows[id].original[name][index]);
                    }
                    $(this).click(function () {
                        $(this).data("object", "null");
                        $(this).find(".model-update-details-removed").find("div").text("(Removido)");
                        $(this).find(".model-update-details-removed").removeClass("cyrus-item-hidden");
                        $(this).find(".model-update-details-items").addClass("cyrus-item-hidden");
                    });
                } else {
                    $(this).find(".model-update-details-removed").find("div").text("(Nenhum)");
                    $(this).find(".model-update-details-removed").removeClass("cyrus-item-hidden");
                    $(this).off("click");
                    $(this).find(".model-update-details-items").addClass("cyrus-item-hidden");
                }
                $(this).data("object", object);
            }
        })
        updateModal.show();
    });

    $("#btn-details-remove").click(function () {
        API.requestType(entity, "remove", {"id": $(this).data("id")}).then((result: any) => {
            if (result.status) {
                cyrusAlert("success", result.description);
            } else {
                cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                console.error(result);
            }
            detailsModal.hide();
        });
    });

    $("button[data-relation]").click(function () {
        let entityID: any = $("#btn-details-relations").data("id");
        let formName: string = $(this).data("form");
        //let entityChild: string = $(this).data("entitychild");
        let relation: string = $(this).data("relation");
        let entity: string = $(this).data("entity");
        let formData: any[string] = {};
        createDataArray(formName, formData, "").then(value => {
            formData = {
                "id": entityID,
                "relations": {}
            };
            formData["relations"][relation] = [value];
            console.log(formData);
            API.requestType(entity, "update", formData).then((result: any) => {
                if (result.status) {
                    cyrusAlert("success", result.description);
                } else {
                    cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                }
            });
        });
    });

    $("input[type=submit]").click(function () {
        let formName: string = $(this).data("form");
        let formEntity: string = $(this).data("entity");
        let formAction: string = $(this).data("action");
        let formData: any[string] = {};

        createDataArray(formName, formData, formAction).then(value => {
            formData = value;
            API.requestType(formEntity, formAction, formData).then((result: any) => {
                if (result.status) {
                    cyrusAlert("success", result.description);
                } else {
                    cyrusAlert("danger", result.description + " Consulte a consola para mais detalhes.");
                }
            });
        })

    })

});

async function createDataArray(formName: string, formData: any[string], action: string) {
    let files: File[] = [];
    $("[data-form='" + formName + "'").each(function () {
            let name: string = $(this).data("name");
            let value: any = null;
            if ($(this).attr("type") != "submit" && $(this).attr("type") != "reset" && !$(this).is("button")) {
                if ($(this).is("input") || $(this).is("textarea")) {
                    value = $(this).val();
                } else if ($(this).data("ismultiple")) {
                    if ($(this).data("selectedsection")) {
                        let selectedSection: string = $(this).data("selectedsection");
                        $(this).find("[data-section='" + selectedSection + "']").each(function () {
                            let subItemData: any[string] = {};
                            $(this).find("[data-subitem]").each(function () {
                                let nameMultiple: any = $(this).data("subitem");
                                let valueMultiple: any = null;
                                if ($(this).attr("type") != "submit" && $(this).attr("type") != "reset") {
                                    if ($(this).is("input") || $(this).is("textarea")) {
                                        if ($(this).attr("type") == "file") {
                                            if ($(this).prop("files").length > 0) {
                                                files.push($(this).prop("files")[0]);
                                            } else {
                                                console.log("Nenhum ficheiro detectado.");
                                            }
                                        }
                                        valueMultiple = $(this).val();
                                    } else if ($(this).data("ismultiple")) {
                                        console.error("Multiple is not allowed inside another multiple element.");
                                    } else if ($(this).data("isdropdown")) {
                                        valueMultiple = $(this).data("selected");
                                    } else {
                                        console.log("Other-Section: ");
                                        console.log($(this));
                                    }
                                    if (valueMultiple !== null && $.trim(<string>valueMultiple).length == 0) valueMultiple = null;

                                    subItemData[nameMultiple] = valueMultiple;
                                }
                            });
                            // colocar para converter para nulo se n existir;
                            let isNull: boolean = true;
                            for (const item in subItemData) {
                                if (subItemData[item] !== null) {
                                    isNull = false;
                                    break;
                                }
                            }
                            if (isNull) subItemData = null;
                            if (subItemData !== null && $(this).data("service") && $(this).data("action")) {
                                let service: string = $(this).data("service");
                                let action: string = $(this).data("action");
                                subItemData["process"] = {
                                    "service": service,
                                    "action": action
                                };
                            }
                            value = subItemData;
                        });
                    }
                } else if ($(this).data("isdropdown")) {
                    value = $(this).data("selected");
                } else if ($(this).data("isdetailed")) {
                    if (action === "update") {
                        let entityId = $("#btn-update").data("id");
                        let name = $(this).data("name");
                        let item = rows[entityId].original;
                        value = (item[name] === null || $(this).data("object") !== "null") ? undefined : null;
                    } else {
                        value = $(this).data("object") === "null" ? null : undefined;
                    }

                } else {
                    console.log("Other: ");
                    console.log($(this));
                }
                if (value !== null && value !== undefined && $.trim(<string>value).length == 0) value = null;
                formData[name] = value;
            }
        }
    );
    let i = -1;
    for (const item in formData) {
        if (formData[item] !== null && typeof formData[item] === "object" && "process" in formData[item]) {
            let service: string = formData[item]["process"].service;
            let action: string = formData[item]["process"].action;
            delete formData[item].process;
            if (service === "Resources" && action !== "registerFile") {
                i++;
                await API.uploadFile(files[i]).then(async (result: any) => {
                    if (result.status) {
                        if ("data" in result) {
                            let resourceRequest: any[string] = {};
                            for (const n in formData[item]) {
                                resourceRequest[n] = formData[item][n];
                            }
                            resourceRequest["file"] = result.data;
                            await API.requestService("Resources", action, resourceRequest, []).then(async (result: any) => {
                                if (result.status) {
                                    if ("data" in result) {
                                        formData[item] = result.data[0];
                                    }
                                }
                            });
                        }
                    }
                });
            } else if (service === "Resources" && action === "registerFile") {
                i++;
                let resourceRequest: any[string] = {};
                for (const n in formData[item]) {
                    resourceRequest[n] = formData[item][n];
                }
                await API.requestService("Resources", "registerFile", resourceRequest, []).then((result: any) => {
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
            if (formData[index] === undefined) delete formData[index];
        }
        if (formData === undefined) {
            formData = {id: entityId};
        } else formData["id"] = entityId;
    }
    let ret: {} = {};
    for (const index in formData) {
        // @ts-ignore
        ret[index] = formData[index];
    }

    return ret;
}

// [B]efore
// [N]ew
function compareRecords(b: any, n: any) {
    let data: any[string];
    for (const index in b) {
        if (n !== undefined && b[index] !== null && typeof b[index] === 'object') {
            n[index] = (n === null || n[index] === null) ? null : compareRecords(b[index], n[index]);
        } else {
            if (b !== undefined && n !== undefined && b !== null && n !== null && b[index] == n[index]) {
                n[index] = undefined;
            }
        }
        if (data === undefined) data = [];
        data[index] = n !== undefined ? (n !== null ? n[index] : null) : undefined;
    }
    let isUndefined = true;
    for (const index in data) {
        if (data[index] !== undefined) {
            isUndefined = false;
        }
    }
    if (isUndefined) return undefined;
    return data;
}
