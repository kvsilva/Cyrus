

$(document).ready(function() {
    console.log("--Options--")
    $("[data-option]").click(function (){
        let lastOption = $(".user-options-section-selected").data("option");
        let currentOption = $(this).data("option");
        $(".user-options-section-selected").removeClass("user-options-section-selected");
        $("[data-setting='" + lastOption +"']").addClass("user-options-body-hidden");
        $(this).addClass("user-options-section-selected");
        $("[data-setting='" + currentOption +"']").removeClass("user-options-body-hidden");

    })
});
