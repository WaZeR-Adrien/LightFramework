/**
 * View new alert with infos...
 * viewAlert is a global function
 * @param title
 * @param msg
 * @param time
 * @param type
 */
viewAlert = function (title, msg, time, type) {
    var title = title;
    var msg = msg;
    var options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": time,
        "positionClass": "toast-top-center"
    };
    if (type === 'err') {
        toastr.error(msg, title, options)
    }
    else if (type === 'suc') {
        toastr.success(msg, title, options)
    }
    else if (type === 'warn') {
        toastr.warning(msg, title, options)
    }
}