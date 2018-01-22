<form class="form-horizontal" action="/client/create" method="POST">
    <input type="hidden" name="{csrf_name}" value="{csrf_value}">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                Client <small>new</small>
            </h1>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-2" for="name">Company Name:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?php echo set_value('name', '{name}'); ?>">
                <span class="text-danger" data-val-for="name"><?php echo form_error('name'); ?></span>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="address">Address:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="<?php echo set_value('address', '{address}'); ?>">
                <span class="text-danger" data-val-for="address"><?php echo form_error('address'); ?></span>
            </div>
        </div>   

        <div class="form-group">
            <label class="control-label col-sm-2" for="province">Province:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="province" name="province" placeholder="Enter province" value="<?php echo set_value('province', '{province}'); ?>">
                <span class="text-danger" data-val-for="province"><?php echo form_error('province'); ?></span>
            </div>
        </div>   

        <div class="form-group">
            <label class="control-label col-sm-2" for="city">City:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="<?php echo set_value('city', '{city}'); ?>">
                <span class="text-danger" data-val-for="city"><?php echo form_error('city'); ?></span>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="postal_code">Postal Code:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Enter postal code" value="<?php echo set_value('postal_code', '{postal_code}'); ?>">
                <span class="text-danger" data-val-for="postal_code"><?php echo form_error('postal_code'); ?></span>
            </div>
        </div>

        <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</form>
