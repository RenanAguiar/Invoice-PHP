<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form class="form-horizontal" action="/client/edit/{client_id}" method="POST">
  <div class="form-group">
    <label class="control-label col-sm-2" for="name">Company Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?php echo set_value('name', '{name}'); ?>">
        <span class="text-danger" data-val-for="name"><?php echo form_error('name'); ?></span>
    </div>
  </div>


    <div class="row">
    <div class="col-md-12">
        <h3>Contacts</h3>
        {contact_entries}
    </div>
    </div>
    
    
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>