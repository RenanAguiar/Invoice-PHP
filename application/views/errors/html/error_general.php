<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>
<style>

.error-template {padding: 40px 15px;text-align: center;}
.error-actions {margin-top:15px;margin-bottom:15px;}
.error-actions .btn { margin-right:10px; }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oopss!</h1>
                <h2>
                    <?php echo $heading; ?></h2>
                <div class="error-details">
                    <?php echo $message; ?>
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-primary btn-lg">
                        <span class="glyphicon glyphicon-home"></span>
                        Take Me Home
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
</div>