
<div class="form-horizontal">
    <input type="hidden" name="invoice_id" id="invoice_id" value="{invoice_id}">
 
    <div class="form-group">
        <label class="control-label col-sm-4" for="amount_paid">Amount Paid:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="amount_paid" name="amount_paid" placeholder="Enter Amount" value="{amount_paid}">
            <span class="text-danger" data-val-for="amount_paid"></span>
        </div>
    </div>


 
        <label class="control-label col-sm-4" for="date_paid">Date of Payment:</label>
        <div class="form-group col-sm-8">
            <div class="col-sm-12">
            <div class='input-group date' id='datetimepicker_date_paid'>
                <input type='text' class="form-control" name='date_paid' id='date_paid' value="" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="text-danger pull-right" data-val-for="date_paid"></span>
            </div>
        </div>
  

</div>
