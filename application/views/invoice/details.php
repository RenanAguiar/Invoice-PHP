
<div class="row">
    <div class="col-md-12">

        <div class="page-header">
            <div class="dropdown pull-right">
                <button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">Actions
                    <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/invoice/edit/{invoice_id}">Edit</a></li>
                    <li role="presentation" class="{class_pay}"><a role="menuitem" tabindex="-1" data-invoice-id="{invoice_id}" data-toggle="modal" data-target="#modal_payment" href="#">Make Payment</a></li>
<!--                    <li role="presentation" class="{class_reminder}"><a role="menuitem" tabindex="-1" href="#">Send Reminder</a></li>-->
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/Phpword/download/{invoice_id}">Download DOCX</a></li>  
<!--                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Download PDF</a></li> -->
                    <li role="presentation" class="divider"></li>
                    <li role="presentation" class="{class_void}"><a role="menuitem" tabindex="-1" data-action="void_invoice" data-invoice-id="{invoice_id}" data-toggle="modal" data-target="#modal_payment" href="#">Void</a></li>

                </ul>
            </div>
 
<!--            <a class="btn btn-warning pull-right" role="button" href="/invoice/edit/{invoice_id}">
                <span class="glyphicon glyphicon-edit"></span>
            </a>-->

            <h1 id="download">
                Invoice <small>details ({invoice_number})</small>
            </h1>
        </div>

        <div class="col-md-6">
            <p>Client: 
                {name}   </p>
            <p>
                {address} <br>
                {city} {province}<br>
                {postal_code}   
            </p>
        </div>

        <div class="col-md-6">
            <p>Date of Issue: {date_issue}</p>
            <p>Due Date: {due_date}</p>
        </div>


    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3>
            <small>Items</small>
        </h3>
        {invoice_item_entries}
    </div>
    <p class="pull-right">Total: {total_invoice}</p>
</div>

<div id="modal_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body edit-content"> ... </div>
        </div>
    </div>
</div>


