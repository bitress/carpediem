Bitress.Util.hash = function (value) {
    return value.length ? CryptoJS.SHA512(value).toString() : "";
};

Bitress.Http.post = function (data, success, error, complete) {
    $.ajax({
        url: "Ajax.php",
        type: "POST",
        dataType: "json",
        data: data,
        success: success || function () {},
        error: error || function () {},
        complete: complete || function () {}
    })
}
