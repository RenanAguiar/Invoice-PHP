<table class="table table-hover"> 
    <thead> 
        <tr> 
            <th>Invoice</th> 
            <th>Date Issue</th> 
            <th>Due Data</th>
            <th>Amount Paid</th> 
            <th>Date Paid</th> 
            <th></th>
        </tr> 
    </thead> 
    <tbody> 
        {invoice_entries}
        <tr> 
            <td>{invoice_number}</td>
            <td>{date_issue}</td> 
            <td>{due_date}</td> 
            <td>{amount_paid}</td>
            <td>{date_paid}</td> 
            <td><a href="/invoice/details/{invoice_id}" class="btn btn-info btn-xs pull-right" role="button"><span class="glyphicon glyphicon-open"></span> Open</a></td>
        </tr> 
        {/invoice_entries}
    </tbody> 
</table>