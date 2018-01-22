

var modal_client;

$(document).on("click", "#modal_client button[data-action='create']", function (event) {
    var formData = set_contact();
    $.ajax({
        cache: false,
        type: 'POST',
        url: '/contact/create_contact_post',
        data: formData,
        beforeSend: function () {
            // clearValidationErrors();
        },
        success: function (data)
        {
            get_client_contact(data.client_contact_id);
        },
        error: function (jqXhr)
        {
            clearValidationErrors();
            if (jqXhr.status === 400)
            { //Validation error or other reason for Bad Request 400
                displayValidationErrors(jqXhr.responseText);
            }
        }
    });
});





function set_contact() {
   return {
        'client_contact_id': $("#client_contact_id").val(),
        'client_id': $("#client_id").val(),
        'first_name': $("#first_name").val(),
        'last_name': $("#last_name").val(),
        'email': $("#email").val(),
        'phone': $("#phone").val()
    };

}
//edit post
$(document).on("click", "#modal_client button[data-action='edit']", function (event) {
    var formData = set_contact();
    $.ajax({
        cache: false,
        type: 'POST',
        url: '/contact/edit_contact_post',
        data: formData,
        success: function (data)
        {
            get_client_contact(data.client_contact_id, true);
        },
        error: function (jqXhr) {
clearValidationErrors();
            if (jqXhr.status === 400) { //Validation error or other reason for Bad Request 400
                displayValidationErrors(jqXhr.responseText);
            }
        }
    });
});




function get_client_contact(client_contact_id, replace)
{
    replace = typeof replace !== 'undefined' ? replace : false;
    client_contact_id = typeof client_contact_id !== 'undefined' ? client_contact_id : false;

    var formData = {
        'client_contact_id': client_contact_id
    };
    $.ajax({
        cache: false,
        type: 'GET',
        url: '/contact/get_client_contact/',
        data: formData,
        success: function (data)
        {
            if (replace)
            {
                $("#contact_" + client_contact_id).replaceWith(data.html);

            } else
            {
                $("#contacts").append(data.html);
            }

            modal_client.modal("hide");
        }
    });
}


$(document).ready(function () {



    $('table#tbl_client').dataTable({
        'bFilter': true,
        'bInfo': false,
        'bPaginate': false,
        'bSort': true,
        "columns": [
            {"width": "45%"},
            {"width": "15%"},
            {"width": "10%"},
            {"width": "20%"},
            {"width": "10%"}
        ]
    });


    $('[data-toggle="popover"]').popover({
        container: 'body',
        html: true,
        content: function () {
            var clone = $($(this).data('popover-content')).clone(true).removeClass('hide');
            var id = $(this).data('contact-id');           
            clone.find('.list-delete').data("contact-id", id);
            return clone;
        }
    }).click(function (e) {
        e.preventDefault();
    });

    $('[data-toggle="popover"]').each(function () {
        var button = $(this);
        button.popover().on('shown.bs.popover', function () {
            button.data('bs.popover').tip().find('[data-dismiss="popover"]').on('click', function () {
                button.popover('toggle');
            });

            button.data('bs.popover').tip().find('.list-delete').on('click', function () {
                var client_contact_id = $(this).data('contact-id');
                deleteContact(client_contact_id);
                button.popover('toggle');
            });

        });
    });



    function deleteContact(client_contact_id) {
        // var client_contact_id = $(this).attr('data-id');

        var formData = {
            'client_contact_id': client_contact_id
        };

        $.ajax({
            cache: false,
            type: 'DELETE',
            url: '/contact/delete_contact/',
            data: formData,
            beforeSend: function () {
                //clearValidationErrors();
            },
            success: function (data)
            {
                //get_client_contact(data.client_contact_id);
                $("#contact_" + client_contact_id).remove();
               // alert(client_contact_id);
            },
            error: function (jqXhr)
            {
                if (jqXhr.status === 400)
                { //Validation error or other reason for Bad Request 400
                    displayValidationErrors(jqXhr.responseText);
                }
            }
        });
    }





    modal_client = $('#modal_client').on('show.bs.modal', function (e) {
        var client_id = e.relatedTarget.getAttribute('data-client-id');
        var client_contact_id = e.relatedTarget.getAttribute('data-contact-id');
        var action = e.relatedTarget.getAttribute('data-action');
        url = '/contact/create_contact_get';
        if (action === 'edit')
        {
            url = '/contact/edit_contact_get';
        }
        var formData =
                {
                    'client_id': client_id, 'client_contact_id': client_contact_id
                };
        var modal = $(this);
        $.ajax({
            cache: false,
            type: 'GET',
            url: url,
            //url: '/client/create_contact_get',
            data: formData,
            success: function (data)
            {
                modal.find('.modal-content').html(data);
            }
        });
    });

}); //end ready