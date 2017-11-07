<?php
    $main_website = Config::get('site/http').Config::get('site/httpurl');
    $css = $main_website .  Config::get('site/resources/css');
    $js = $main_website .  Config::get('site/resources/js');
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="GBR Management System">
    <meta name="author" content="Brian L. Peter Jr.">

    <title>GBR Management System</title>

        <!-- CSS -->
        <link type="text/css" href="<?= $css . '/bootstrap.min.css'; ?>" rel="stylesheet">
        <link type="text/css" href="<?= $css . '/bootstrap-multiselect.css'; ?>" rel="stylesheet">
        <link type="text/css" href="<?= $css . '/bootstrap-multiselect.less'; ?>" rel="stylesheet">
        <link type="text/css" href="<?= $css . '/style.css'; ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?= $css . '/fullcalendar.css'; ?>" />
        <link rel="stylesheet" href="<?= $css . '/font-awesome.css'; ?>" />

    <!-- Javascript -->
    <script src="<?= $js . '/moment.min.js'; ?>"></script>
    <script src="<?= $js . '/jquery.js'; ?>"></script>
    
    <script src="<?= $js . '/fullcalendar.js'; ?>"></script>
    <script type="text/javascript" src="<?= $js . '/bootstrap-hover-dropdown.js'; ?>"></script>
    <script type="text/javascript" src="<?= $js . '/bootstrap-multiselect.js'; ?>"></script>
    <script type="text/javascript" src="<?= $js . '/alerts.js'; ?>"></script>
    <script src="<?= $js . '/printmap.js'; ?>"></script>

    <!-- Date Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script type="text/javascript" src="<?= $js . '/bootstrap-datetimepicker.min.js'; ?>"></script>
    <link rel="stylesheet" href="<?= $css . '/bootstrap-datetimepicker.css'; ?>"/>

    <script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            html : true,
            content: function() {
                var content = $(this).attr("data-popover-content");
                return $(content).children(".popover-body").html();
            }
        }); 
    });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var date_input=$('input[name="frmquotedate"]');
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            var options={
                format: 'yyyy-mm-dd',
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
            var date_input=$('input[name="frmdatedelivered"]');
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            var options={
                format: 'yyyy-mm-dd',
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