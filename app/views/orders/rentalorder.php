<?php
    $order = $data['order'];
    $customer = $data['customer'];
    $orderedProducts = $order->getProducts();
    $conArray = $data['conArray'];
    $pudArray = $data['pudArray'];
    $deliveryTotal = 0;
    $pickupTotal = 0;
?>

<!DOCTYPE html>
<html>
    <head>

        <?php require_once(Config::get('site/baseurl').Config::get('site/assets').'/header.php'); ?>

    </head>

    <body style="background-color: white;">

        <div id="page-content-wrapper" style="margin-top:-50px;">

            <div class="container-fluid" id="webbg">

                <!-- 1st Row. [Confidentiality Portion] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <img src="<?= Config::get('site/siteurl').Config::get('site/resources/img').'/logo.png'; ?>" alt="">
                    </div>
                </div>
                <!-- End of 1st Row. -->

                <!-- 3rd Row. [Page Title] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:0px;">
                            <div class="row">
                                <h3 style="font-weight: bold;">
                                Rental Order
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 3rd Row. -->

                <!-- 4th Row. [Customers Information] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                            <div style="margin-top:0px;">
                                <div class="row">
                                    <table class="cust_data_table">
                                        <tbody>
                                            <tr align="left">
                                                <td class="" style="border: 3px solid black;width: 50px;height: 30px;text-align:center;font-size:18px;"><strong>X</strong></td>
                                                <td class="" style="font-size:12px;border: 0px dashed black;">&nbsp;&nbsp;&nbsp;<strong>DELIVERY</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>DATE:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getDate(); ?></strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>TIME:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getTime(); ?></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td class="" style="border: 3px solid black;width: 50px;height: 30px;text-align:center;font-size:18px;"></td>
                                                <td class="" style="font-size:12px;border: 0px solid black;">&nbsp;&nbsp;&nbsp;<strong>PICKUP</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>DATE:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>TIME:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
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
                        <div style="margin-top:10px;">
                            <div class="row">
                            
                            <?php foreach($orderedProducts as $product): ?>
                                <?php if(in_array($product->getModShortName(), $conArray)): ?>
                                    <table class="cust_data_table">
                                        <tbody>
                                            <tr align="left">
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>SIZE:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $product->getModName(); ?></strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>UNIT #:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>TYPE:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong>DD&nbsp;&nbsp;&nbsp;HC&nbsp;&nbsp;&nbsp;S1&nbsp;&nbsp;&nbsp;S2</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>DOORS:</strong></td>
                                                <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong>ON&nbsp;&nbsp;&nbsp;OFF</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 5th Row. -->

                <!-- 6th Row. [Charge Explination] -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>RATE:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getMonthlyTotal(); ?></strong></td>
                                            <?php foreach($orderedProducts as $product): ?>
                                                <?php 
                                                    if(in_array($product->getModShortName(), $pudArray) && $product->getProductType() == "delivery"){
                                                        $deliveryTotal += $product->getProductCost();
                                                    }
                                                ?>
                                            <?php endforeach; ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height:30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong>DELIVERY:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= number_format($deliveryTotal, 2); ?></strong></td>
                                            <?php foreach($orderedProducts as $product): ?>
                                                <?php 
                                                    if(in_array($product->getModShortName(), $pudArray) && $product->getProductType() == "pickup"){
                                                        $pickupTotal += $product->getProductCost();
                                                    }
                                                ?>
                                            <?php endforeach; ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height:30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong>PICKUP:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= number_format($pickupTotal, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 6th Row. -->

                <!-- 7th Row. -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Lessee:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerName(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>Job Name:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getJobName(); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Address:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerAddress1(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>Address:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getJobAddress(); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Address:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerAddress2(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>City/State/Zip:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerCity().', '.$customer->getCustomerState().', '.$customer->getCustomerZipcode(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;">&nbsp;&nbsp;&nbsp;<strong>City/State/Zip:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getJobCity().', CA, '.$order->getJobZipcode(); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Phone:</strong></td>
                                            <?php if($customer->getCustomerFax() != ''): ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerPhone().'&nbsp;&nbsp;Ext: '.$customer->getCustomerFax(); ?></strong></td>
                                            <?php else: ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerPhone(); ?></strong></td>
                                            <?php endif; ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Ordered By:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getOrderedBy(); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Fax:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $customer->getCustomerFax(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>PO #</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">
                                                <div class="col-md-6">
                                                    &nbsp;&nbsp;&nbsp;<strong></strong>
                                                </div>
                                                <div class="col-md-6">
                                                    &nbsp;&nbsp;&nbsp;<strong>RDP:</strong>
                                                    &nbsp;&nbsp;&nbsp;None&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;5
                                                </div>
                                            </td>                                            
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
                                <table class="cust_data_table">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>DELIVERY OSC:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getOnSiteContact(); ?></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>PHONE:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= $order->getOnSiteContactPhone(); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>PICKUP OSC:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;text-align:center;"></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>PHONE:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;text-align:center;"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                                            <td align="left">&nbsp;DEL/PU NOTES:</td>
                                        </tr>
                                        <tr style="height: 60px;">
                                            <td></td>
                                        </tr>
                                        <tr style="border: 1px solid black;height:40px;">
                                            <td align="center" style="width:25%;">DRIVER COLLECT?</td>
                                            <td align="center" style="width:2.5%;">YES</td>
                                            <td align="center" style="width:5%;">NO</td>
                                            <td align="center" style="width:30%;">IF YES COLLECT:</td>
                                            <td align="center" style="width:40%;">CHECK #:</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 9th Row. -->

                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:20px;">
                            <div class="row">
                                <table class="cust_data_table" style="border-bottom: 2px solid black;">
                                    <tbody>
                                        <tr style="font-weight: bold;">
                                            <td align="left" style="width:80%;">DELIVERED BY</td>
                                            <td align="center" style="width:20%;">DATE</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:20px;">
                            <div class="row">
                                <table class="cust_data_table" style="border-bottom: 2px solid black;">
                                    <tbody>
                                        <tr style="font-weight: bold;">
                                            <td align="left" style="width:80%;">PICKED UP BY</td>
                                            <td align="center" style="width:20%;">DATE</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="font-size:10px;height: 30px;width: 25%;">&nbsp;&nbsp;&nbsp;<strong>RENTAL READY</strong></td>
                                            <td class="" style="text-align:left;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;">&nbsp;&nbsp;&nbsp;RETURN NOTES</td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="font-size:10px;height: 30px;width: 25%;">&nbsp;&nbsp;&nbsp;<strong>NEEDS REPAIR</strong></td>
                                            <td class="" style="border-left:1px solid black;border-right:1px solid black;"></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="font-size:10px;height: 30px;width: 25%;">&nbsp;&nbsp;&nbsp;<strong>CUSTOMER DAMAGE</strong></td>
                                            <td class="" style="border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black;"></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </body>
</html>