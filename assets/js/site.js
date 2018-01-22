


function displayValidationErrors(errors) {
    var myArr = $.parseJSON(errors);
    $.each(myArr, function (idx, item) {
        $('*[data-val-for="' + idx + '"]').html(item);
    });
}

function clearValidationErrors() {
    $('span[data-val-for]').each(function (index) {
        $(this).text("");
    });
}

function show_toaster(type, message) {

    toastr.options.positionClass = 'toast-top-center';
    toastr.options.closeButton = true;
    toastr.options.preventDuplicates = true;

    if (type === "error") {
        toastr.error(message);
    }

    if (type === "success") {
        toastr.success(message);
    }


} 