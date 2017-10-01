$(document).ready(function () {
    var image = $('.page-title-bar').attr('img');
    $('.page-title-bar').css('background-image', 'url(/app/uploads/img/wallpaper/'+ image +')');

    $("[data-toggle=tooltip]").tooltip();
});