<form class="form-horizontal" action="/invoice/create/{client_id}" method="POST">
    <input type="hidden" name='client_id' value='{client_id}'>
    <div class="page-header">
        <h1>
            Invoice <small>new</small>
        </h1>
    </div>

    <div class='col-md-4'>
        <address>
            <strong>From</strong><br>
            {profile_name}<br>
            {profile_address}<br>
            {profile_city}, {profile_province}<br>
            {profile_postal_code}
        </address>
    </div>

    <div class='col-md-4'>
        <address>
            <strong>To</strong><br>
            {name}<br>
            {address}<br>
            {city}, {province}<br>
            {postal_code}
        </address>
    </div>


    <div class='col-md-4'>
        <label class="control-label col-sm-5" for="postal_date_issuecode">Date of Issue:</label>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker_date_issue'>
                <input type='text' class="form-control" name='date_issue' value="<?php echo set_value('date_issue', '{date_issue}'); ?>" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="text-danger pull-right" data-val-for="name"><?php echo form_error('date_issue'); ?></span>
        </div>

        <label class="control-label col-sm-5" for="due_date">Due Date:</label>
        <div class="form-group">
            <div class='input-group date' id='datetimepicker_due_date'>
                <input type='text' class="form-control" name='due_date' value="<?php echo set_value('due_date', '{due_date}'); ?>" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>

            </div>
            <span class="text-danger pull-right" data-val-for="name"><?php echo form_error('due_date'); ?></span>
        </div>

    </div>







    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h3>
                    Items
                </h3>
            </div>
            <div id="contact_entries">
                <table class="table table-hover table-striped" id="invoice_items">
                    <thead>
                        <tr>

                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total (line)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {invoice_entries}
                        <tr>

                            <td class="col-md-5">
                                <input type="text" class="form-control" name="description[]" value="{description}">
                                <span class="text-danger" data-val-for="description">{description_error}</span>
                            </td>

                            <td class="col-md-1"><input type="text" class='form-control quantity' name="quantity[]" value="{quantity}"></td>

                            <td class="col-md-2">
                                <div class="input-group col-xs-12">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{unit_price}">

                                </div>      
                                <span class="text-danger pull-right" data-val-for="unit_price"><?php echo form_error('unit_price[]'); ?></span>
                                
                            </td>

                            <td class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control total_line" name="{total_line}[]" value="{total_line}" disabled=""> 
                                </div>
                            </td>

                            <td class="col-md-1"><button type="button" class="btn btn-danger btn-xs pull-d remove_line"><span class="glyphicon glyphicon-trash"></span></button></td>
                        </tr>

                        {/invoice_entries}

                    </tbody>
                </table>
                <button type="button" class="btn btn-success pull-left" id="addRow"><span class="glyphicon glyphicon-plus"></span></button>

                <div class="row text-right">
                    <div class="col-xs-2 col-xs-offset-7">
                        <p>
                            <strong>
                                Subtotal: <br>
                                Tax @ <span id="tax">{profile_gst}</span>%: <br>
                                Total: <br>
                            </strong>
                        </p>
                    </div>
                    <div class="col-xs-2">
                        <strong>
                            $<span id="subtotal">0.00</span><br>
                            $<span id="tax_total">0.00</span><br>
                            $<span id=total>0.00</span> <br>
                        </strong>
                    </div>
                </div>





            </div>

        </div>

    </div>

    <div class="row"> 
        <div class='col-md-12'>

            <button type="submit" class="btn btn-success pull-right">Create</button>
        </div>
    </div>
</form>
