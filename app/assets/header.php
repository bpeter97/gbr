<?php
    
    echo '

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="GBR Management System">
    <meta name="author" content="Brian L. Peter Jr.">

    <title>GBR Management System</title>

   ';

   // CSS
    echo '
        <!-- CSS -->
        <link type="text/css" href="'.HTTP.HTTPURL.PUB.CSS.'/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="'.HTTP.HTTPURL.PUB.CSS.'/bootstrap-multiselect.css" rel="stylesheet">
        <link type="text/css" href="'.HTTP.HTTPURL.PUB.CSS.'/bootstrap-multiselect.less" rel="stylesheet">
        <link type="text/css" href="'.HTTP.HTTPURL.PUB.CSS.'/style.css" rel="stylesheet">
        <link rel="stylesheet" href="'.HTTP.HTTPURL.PUB.CSS.'/fullcalendar.css" />
    ';

    echo '
    <!-- Javascript -->
    <script src="'.HTTP.HTTPURL.PUB.JS.'/moment.min.js"></script>
    <script src="'.HTTP.HTTPURL.PUB.JS.'/jquery.js"></script>
    
    <script src="'.HTTP.HTTPURL.PUB.JS.'/fullcalendar.js"></script>
    <script type="text/javascript" src="'.HTTP.HTTPURL.PUB.JS.'/bootstrap-hover-dropdown.js"></script>
    <script type="text/javascript" src="'.HTTP.HTTPURL.PUB.JS.'/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="../js/alerts.js"></script>
    <script src="'.HTTP.HTTPURL.PUB.JS.'/printmap.js"></script>

    <!-- Date Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script type="text/javascript" src="'.HTTP.HTTPURL.PUB.JS.'/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="'.HTTP.HTTPURL.PUB.CSS.'/bootstrap-datetimepicker.css"/>

    
    <script type="text/javascript">
        $(document).ready(function(){
            var date_input=$(\'input[name="frmquotedate"]\');
            var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
            var options={
                format: \'yyyy-mm-dd\',
                container: container,
                todayHighlight: true,
                autoclose: true,
                orientation: "top",
            };
            date_input.datepicker(options);
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            var date_input=$(\'input[name="frmdatedelivered"]\');
            var container=$(\'.bootstrap-iso form\').length>0 ? $(\'.bootstrap-iso form\').parent() : "body";
            var options={
                format: \'yyyy-mm-dd\',
                container: container,
                todayHighlight: true,
                autoclose: true,
                orientation: "top",
            };
            date_input.datepicker(options);
        })
    </script>
    
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        });
    </script>
';

?>
