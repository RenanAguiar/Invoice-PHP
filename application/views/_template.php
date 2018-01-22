<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">

        <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" crossorigin="anonymous">

        <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

        <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>



        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <script type="text/javascript" src="/assets/js/site.js"></script>
        <link rel="stylesheet" href="/assets/css/site.css">
        {extra_js}



        <title>{pagetitle}</title>
    </head>
    <body>

        <div class="container">
            {menu}
            {content}
        </div>

        <script>




            var toaster_message = "{toast_message}";
            var toaster_type = "{toast_type}";
            if (toaster_message.length > 0) {
                show_toaster(toaster_type, toaster_message);
            }




        </script>
    </body>
</html>
