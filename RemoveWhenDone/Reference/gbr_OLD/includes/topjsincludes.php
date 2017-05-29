<?php

echo '
    <!-- Javascript -->
    <script src="'.HTTP.HTTPURL.JS.'/moment.min.js"></script>
    <script src="'.HTTP.HTTPURL.JS.'/jquery.js"></script>
    
    <script src="'.HTTP.HTTPURL.JS.'/fullcalendar.js"></script>
    <script type="text/javascript" src="'.HTTP.HTTPURL.JS.'/bootstrap-hover-dropdown.js"></script>
    <script type="text/javascript" src="'.HTTP.HTTPURL.JS.'/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="../js/alerts.js"></script>
    <script src="'.HTTP.HTTPURL.JS.'/printmap.js"></script>

    <!-- Date Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.css"/>

    
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