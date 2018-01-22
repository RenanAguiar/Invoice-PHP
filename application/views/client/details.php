<div class="row">
    <div class="col-md-8">

        <div class="page-header">
            <a class="btn btn-warning pull-right btn-sm" role="button" href="/client/edit/{client_id}">
                <span class="glyphicon glyphicon-edit"></span>
            </a>
            <h1 id="download">
                Client <small>details</small>
            </h1>
        </div>        

        <input type="hidden" id="client_id" name="client_id" value="{client_id}">
        <p><span class="glyphicon glyphicon-user"></span> {name}</p>     
        <p><span class="glyphicon glyphicon-home"></span> {address}</p> 
        <p><span class="glyphicon glyphicon-home"></span> {city}</p> 
        <p><span class="glyphicon glyphicon-home"></span> {province}</p> 
        <p><span class="glyphicon glyphicon-home"></span> {postal_code}</p> 

        <div class="page-header">
            <a class="btn btn-success pull-right" role="button" href="/invoice/create/{client_id}">
                <span class="glyphicon glyphicon-plus"></span>
            </a>
            <h3>
                <small>Invoices</small>
            </h3>
        </div>       

        {invoice_entries}       

    </div>

    <div class="col-md-4" id="contacts">

        <div class="page-header">
            <button type="button" class="btn btn-success pull-right" data-client-id="{client_id}" id="contact_add" data-toggle="modal" data-target="#modal_client"><span class="glyphicon glyphicon-plus"></span></button>
            <h3>
                <small>Contacts</small>
            </h3>
        </div>            
        {contact_entries}
    </div>
</div>


<div id="modal_client" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body edit-content"> ... </div>
        </div>
    </div>
</div>


<div id="list-popover" class="hide">
    <div class="alert small alert-danger" role="alert">Delete this contact?</div>
    <button type="button" data-id="#" class="list-delete btn btn-primary btn-danger">Delete</button>
    <button type="button" class="btn btn-primary btn-primary" data-dismiss="popover">Cancel</button>
</div>