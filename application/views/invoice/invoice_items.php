<table class="table table-hover"> 
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
        {invoice_item_entries}
        <tr> 
            <td>{description}</td>
            <td>{quantity}</td> 
            <td>{unit_price}</td> 
            <td>{total_line}</td>
            <td></td> 
        </tr> 
        {/invoice_item_entries}
    </tbody> 
</table>
			
			
