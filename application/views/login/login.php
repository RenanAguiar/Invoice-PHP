 
<script>

    function log_in() {
        var redirect = "/client";
        var data = {
            "login_email": $("#login_email").val(),
            "login_password": $("#login_password").val(),
            "csrf_test_name": '{csrf_value}' 
        };

        $.ajax({
            url: 'login/do_login',
            type: 'POST',
            data: data,
            success: function (data) {
                if ($.trim($("#previous_page").val()).length > 0) {
                    redirect = decodeURIComponent($("#previous_page").val());
                }
                window.location.replace(redirect);
            },
            error: function (jqXhr) {
                if (jqXhr.status === 400) { //Validation error or other reason for Bad Request 400
                    displayValidationErrors(jqXhr.responseText);
                    var myArr = $.parseJSON(jqXhr.responseText);
                    show_toaster("error", myArr.error);
                }
            }
        });


    }

    $(document).on("click", "#btn_signup", function (event) {

        var str = $("#frm_signup").serialize();

        $.ajax({
            cache: false,
            type: 'POST',
            url: '/login/sign_up',
            //url: '/client/create_contact_get',
            data: str,
            success: function (data)
            {
                //get_client_contact(data.client_contact_id);
                 $("#login_email").val($("#email").val());
                 $("#login_password").val($("#password").val());
                 log_in();
            },
            error: function (jqXhr)
            {
                clearValidationErrors();
                if (jqXhr.status === 400)
                { //Validation error or other reason for Bad Request 400
                    displayValidationErrors(jqXhr.responseText);
                    var myArr = $.parseJSON(jqXhr.responseText);
                    show_toaster("error", myArr.error);
                }
            }
        });
    });
</script>



<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">Sign In</div>
            <!-- <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>-->
        </div>     

        <div style="padding-top:30px" class="panel-body" >

            <form id="loginform" class="form-horizontal" role="form">
                <input type="hidden" name="{csrf_name}" value="{csrf_value}">

                <input type="hidden" id="previous_page" name="previous_page" value="{previous_page}">
                <div style="" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="login_email" type="text" class="form-control" name="login_email" value="" placeholder="email">
                </div>
                <span class="text-danger" data-val-for="login_email"></span>


                <div style="margin-top: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input id="login_password" type="password" class="form-control" name="login_password" placeholder="password">
                </div>
                <span class="text-danger" data-val-for="login_password"></span>

                <div style="margin-top:10px" class="form-group">
                    <div class="col-sm-12 controls">
                        <a id="btn-login" href="#" class="btn btn-success" onclick="log_in();
                                return false;">Login  </a>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-12 control">
                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                            Don't have an account! 
                            <a href="#" onClick="$('#loginbox').hide();
                                    $('#signupbox').show()">
                                Sign Up Here
                            </a>
                        </div>
                    </div>
                </div>    
            </form>     



        </div>                     
    </div>  
</div>
<div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign Up</div>
            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
        </div>  
        <div class="panel-body" >
            <form id="frm_signup" class="form-horizontal" role="form">
<input type="hidden" name="{csrf_name}" value="{csrf_value}">
                <div class="form-group">
                    <label for="email" class="col-md-4 control-label">Email</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address">
                        <span class="text-danger" data-val-for="email"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="firstname" class="col-md-4 control-label">Business Name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" placeholder="Business Name">
                        <span class="text-danger" data-val-for="name"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-md-4 control-label">Password</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <span class="text-danger" data-val-for="password"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_c" class="col-md-4 control-label">Password Confirmation</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control" name="password_c" placeholder="Password Confirmation">
                        <span class="text-danger" data-val-for="password_c"></span>
                    </div>
                </div>

                <div class="form-group">                                    
                    <div class="col-md-offset-4 col-md-8">
                        <button id="btn_signup" type="button" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Sign Up</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>