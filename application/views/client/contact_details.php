{contact_entries}
<div class="col-sm-12" id="contact_{client_contact_id}">
    <div class="panel panel-info">
        <div class="panel-body">
            <p class=""><strong>{first_name} {last_name}</strong></p>
            <span class="glyphicon glyphicon-phone-alt"></span> {phone}<br>
            <span class="glyphicon glyphicon-envelope"></span> {email}<br>
        </div>
        <div class="panel-footer panel-info">
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-warning contact_edit" data-client-id="{client_id}" data-action="edit" data-contact-id="{client_contact_id}" data-toggle="modal" data-target="#modal_client">
                    <span class="glyphicon glyphicon-edit"></span>
                </button>

                <button type="button" class="btn btn-danger btn-xs" data-action="delete" data-contact-id="{client_contact_id}" data-toggle="popover" data-placement="top" data-popover-content="#list-popover">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>


            </div>
            <div class="clearfix"></div></div>
    </div>
</div>
{/contact_entries}
