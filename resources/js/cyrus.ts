$(document).ready(function() {
    if ($("#user-menu-btn")) {
        $("#user-menu-btn").click(function () {
            $("#user-menu-list").toggleClass("state-open");
            $("#user-menu-btn").toggleClass("header-btn-hovered");
        });

    }
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    /*setTimeout(function()
    {
        var max = 360;
        var tot, str;
        $('.text span').each(function() {
            str = String($(this).html());
            tot = str.length;
            str = (tot <= max)
                ? str
                : str.substring(0,(max - 3))+"...";
            $(this).html(str);
        });
    },500);*/
});