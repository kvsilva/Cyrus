$(document).ready(function() {
    if ($("#user-menu-btn")) {
        $("#user-menu-btn").click(function () {
            $("#user-menu-list").toggleClass("state-open");
            $("#user-menu-btn").toggleClass("header-btn-hovered");
        });

    }
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});