<?php
    $type = $data['type'];
    $customer = $data['customer_name'];
    $attn = $data['attn'];
    $jobName = $data['job_name'];
    $customerPhone = $data['customer_phone'];
    $customerFax = $data['customer_fax'];
    $customerEmail = $data['customer_email'];
    $quote = $data['quote'];
    $products = $data['products'];
    $rentArray = ['10CONRENT','20DDCONRENT','20CONRENT','40CONRENT','24CONRENT','20COMBORENT','20FULLRENT','40COMBORENT','40SCOMBORENT','20SHELVRENT','LOADRAMP'];
?>

<!DOCTYPE html>
<html>
    <head>

        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

    </head>

    <body style="background-color: white;">

        <div id="page-content-wrapper">

            <div class="container-fluid" id="webbg">

                <!-- 1st Row. [Confidentiality Portion] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <span>
                            CONFIDENTIAL QUOTE <br/>
                            For Individual Use Only
                        </span>
                    </div>
                </div>
                <!-- End of 1st Row. -->

                <!-- 2nd Row. [Page Logo] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:20px;">
                            <div class="row">
                                <img src="../img/logo.png" alt="Green Box Rentals Logo">
                            </div>
                            <div class="row" style="font-weight: bold;margin-top:5px;">
                                6988 AVENUE 304, VISALIA, CA 93291 &nbsp; &nbsp; &nbsp; PH (559) 733-5345 &nbsp; &nbsp; &nbsp; FX (559) 651-4288
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd Row. -->

                <!-- 3rd Row. [Page Title] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:5px;">
                            <div class="row">
                                <h3 style="font-weight: bold;">
                                <?= $type ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 3rd Row. -->

                <!-- 4th Row. [Customers Information] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table table-bordered">
                                    <tbody>
                                        <tr align="left">
                                            <td class="cust_data_td_left"><strong>TO:</strong></td>
                                            <td class="cust_data_td_middle"><?= $customer ?></td>
                                            <td class="cust_data_td_left"><strong>ATTN:</strong></td>
                                            <td class="cust_data_td_middle"><?= $attn ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="cust_data_td_left"><strong>PH:</strong></td>
                                            <td class="cust_data_td_middle"><?= $customerPhone ?></td>
                                            <td class="cust_data_td_left"><strong>FAX:</strong></td>
                                            <td class="cust_data_td_middle"><?= $customerFax ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="cust_data_td_left"><strong>EMAIL:</strong></td>
                                            <td class="cust_data_td_middle"><?= $customerEmail ?></td>
                                            <td class="cust_data_td_left"><strong>JOB:</strong></td>
                                            <td class="cust_data_td_middle"><?= $jobName ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 4th Row. -->

                <!-- 5th Row. [Products Ordered] -->
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:30px;">
                            <div class="row">
                            <?php if($type == "Sales" || $type == "Resale"): ?>

                                    <table class="cust_data_table table-bordered">
                                        <thead>
                                            <tr align="center" style="font-weight: bold;">
                                                <td>Item Name</td>
                                                <td>Quantity</td>
                                                <td>Cost</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php foreach ($products as $item): ?>

                                            <tr>
                                                <!-- Sales Quote -->
                                                <td><?= $item->getModName(); ?></td>
                                                <td class="text-center"><?= $item->getProductQuantity(); ?></td>
                                                <td class="text-center">$ <?= $item->getProductCost(); ?></td>
                                            </tr>

                                            <?php endforeach; ?>

                                            <tr>
                                                <td>Sales Tax</td>
                                                <td class="text-center">1</td>
                                                <td class="text-center">$ <?= $quote->getSalesTax(); ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center">$ <?= $quote->getTotalCost(); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                <?php elseif ($type == "Rental"): ?>

                                    <table class="cust_data_table table-bordered">
                                        <thead>
                                            <tr align="center" style="font-weight: bold;">
                                                <td>Item Name</td>
                                                <td>Quantity</td>
                                                <td>Monthly Cost</td>
                                                <td>Cost</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Will obviously need to do a foreach here and then have the total row at the end after the foreach to display the proper total, easy enough. -->
                                            <?php foreach ($products as $item): ?>
                                            <tr>
                                                <!-- Sales Quote -->
                                                <?php if(in_array($item->getModShortName(), $rentArray)): ?>
                                                    <td><?= $item->getModName(); ?></td>
                                                    <td class="text-center"><?= $item->getProductQuantity(); ?></td>
                                                    <td class="text-center">$ <?= $item->getProductCost(); ?></td>
                                                    <td class="text-center">$ <?= $item->getProductCost(); ?></td>
                                                <?php else: ?>
                                                    <td><?= $item->getModName(); ?></td>
                                                    <td class="text-center"><?= $item->getProductQuantity(); ?></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">$ <?= $item->getProductCost(); ?></td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td>Sales Tax</td>
                                                <td class="text-center"></td>
                                                <td></td>
                                                <td class="text-center">$ <?= $quote->getSalesTax(); ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="sum_row">
                                                <td>Total:</td>
                                                <td class="text-center"></td>
                                                <td class="text-center">$ <?= $quote->getMonthlyTotal(); ?></td>
                                                <td class="text-center">$ <?= $quote->getTotalCost(); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 5th Row. -->

                <!-- 6th Row. [Charge Explination] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:20px;">
                            <div class="row">
                                <span style="font-weight: bold;">
                                    No sales tax on delivery charges.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 6th Row. -->

                <!-- 7th Row. [Thanks!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table">
                                    <tbody>
                                        <tr class="tr_thanks">
                                            <td class="td_thanks">THANK YOU FOR CHOOSING GREEN BOX RENTALS, INC.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 7th Row. -->

                <!-- 8th Row. [Visit Us!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <span style="font-weight: bold;">
                                    Visit www.greenboxrentals.com for more information on our services!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 8th Row. -->

                <!-- 9th Row. [Comments Box] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table cust_border">
                                    <tbody>
                                        <tr>
                                            <td align="left">COMMENTS:</td>
                                        </tr>
                                        <tr style="height: 60px;">
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td align="center">Add sales tax on monthly rentals only.</td>
                                        </tr>
                                        <tr>
                                            <td align="center">One month minimum rental.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 9th Row. -->

                <!-- 10th Row. [Manager Signature] -->
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <span class="manager_sig">
                                    <?= $_SESSION['userfname'].' '.$_SESSION['userlname'].' - '.$_SESSION['usertitle']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 10th Row. -->

                <!-- 11th Row. [Customer Signature] -->
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:50px;">
                            <div class="row">
                                <table class="cust_data_table" style="border-top: 2px solid black;">
                                    <tbody>
                                        <tr style="font-weight: bold;">
                                            <td align="left">SIGNATURE</td>
                                            <td align="center">DATE</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 11th Row. -->

                <!-- 12th Row. [Visit Us!] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <span>
                                    Quote good for thirty days from issue date!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 12th Row. -->
                <!-- 13th Row. [Go Back Buttons] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="containerlink" href="javascript:history.back()"><button type="button" class="btn btn-gbr no-print" style="">Go Back</button></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- End of 13th Row. -->
            </div>
        </div>
    </body>
</html>