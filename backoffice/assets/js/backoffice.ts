
$(document).ready(function() {
    $("input[type=submit]").click(function(){
        let formName : string = $(this).data("form");
        let formData : any[string] = {};

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
                                    if(valueMultiple !== null && $(this).data("service")){

                                    }
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
        })
        console.log(formData);
    })
    
});
