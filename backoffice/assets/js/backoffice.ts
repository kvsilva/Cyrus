import {Request as API} from "../../../resources/js/Request";
import {UserFlags} from "../../../resources/js/models";

$(document).ready(function() {
    $("input[type=submit]").click(function(){
        let formName : string = $(this).data("form");
        let formEntity : string = $(this).data("entity");
        let formData : any[string] = {};

        createDataArray(formName, formData).then(value => {
            formData = value;

            API.requestType(formEntity, "insert", formData, [UserFlags.VIDEOHISTORY.name]).then((result: any) => {
                if (result.status) {
                    if ("data" in result) {
                    }
                }
            });


        })

    })
    
});

async function createDataArray(formName : string, formData : any[string]) {
    let files : File[] = [];
    $("[data-form='" + formName + "'").each(function(){
        let name : string = $(this).data("name");
        let value : any = null;
        if($(this).attr("type") != "submit" && $(this).attr("type") != "reset") {
            if ($(this).is("input") || $(this).is("textarea")) {
                value = $(this).val();
            } else if ($(this).data("ismultiple")) {
                if($(this).data("selectedsection")){
                    let selectedSection : string = $(this).data("selectedsection");
                    $(this).find("[data-section='" + selectedSection + "']").each(function () {
                        let subItemData : any[string] = {};
                        $(this).find("[data-subitem]").each(function () {
                            let nameMultiple : any = $(this).data("subitem");
                            let valueMultiple : any = null;
                            if ($(this).attr("type") != "submit" && $(this).attr("type") != "reset") {
                                if ($(this).is("input") || $(this).is("textarea")) {
                                    if($(this).attr("type") == "file"){
                                        if($(this).prop("files").length > 0) {
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
                        let isNull : boolean = true;
                        for (const item in subItemData) {
                            if(subItemData[item] !== null) {
                                isNull = false;
                                break;
                            }
                        }
                        if(isNull) subItemData = null;
                        if(subItemData !== null && $(this).data("service") && $(this).data("action")){
                            let service : string = $(this).data("service");
                            let action : string = $(this).data("action");
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
            } else {
                console.log("Other: ");
                console.log($(this));
            }
            if (value !== null && $.trim(<string>value).length == 0) value = null;
            formData[name] = value;
        }

    });
    let i = -1;
    for(const item in formData){
        if(formData[item] !== null && typeof formData[item] === "object" && "process" in formData[item]) {
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
    return formData;
}
