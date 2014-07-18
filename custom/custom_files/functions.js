function zoomScreen() {
    var screenAvailWidth = window.screen.availWidth;
    var widthBase = 960;
    if (screenAvailWidth > (widthBase + 10)) {
        var widthRatio = screenAvailWidth / widthBase;
        widthRatio = widthRatio * 100;
        widthRatio = Math.floor(widthRatio) - 10;
    }
    document.body.style.zoom = widthRatio + "%";
}

function jsView() {
    $(".jsHide").addClass("dn");
    $(".jsShow").removeClass("dn");
}

function accordion() {
    $(".accordion").accordion();
}

window.onload = function() {
    //zoomScreen();
    jsView();
    accordion();
};