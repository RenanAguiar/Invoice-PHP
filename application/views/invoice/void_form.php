<div class="alert alert-warning">
  Are you sure you want to void this invoice?
</div>
<div class="form-horizontal">
    <input type="hidden" name="invoice_id" id="invoice_id" value="{invoice_id}">
 
    <div class="form-group">
        <label class="control-label col-sm-4" for="amount_paid">Amount:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="amount_paid" name="amount_paid" disabled="" placeholder="Enter Amount" value="{amount_paid}">
            <span class="text-danger" data-val-for="amount_paid"></span>
        </div>
    </div>


 
    <div class="form-group">
        <label class="control-label col-sm-4" for="note">Notes:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="note" name="note" placeholder="Enter Reason" value="{note}">
            <span class="text-danger" data-val-for="note"></span>
        </div>
    </div>
  

</div>
