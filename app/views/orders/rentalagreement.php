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

        <div id="page-content-wrapper" style="margin-top:-60px;">

            <div class="container-fluid" id="webbg">

                <!-- 1st row -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="col-sm-8">
                            <img src="<?= Config::get('site/siteurl').Config::get('site/resources/img').'/logo.png'; ?>" alt="">
                        </div>
                        <div class="col-sm-4">
                            <table class="cust_data_table">
                                <tbody>
                                    <tr align="left">
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;Lessor:</td>
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;<?= Config::get('company/name'); ?></td>
                                    </tr>
                                    <tr align="left">
                                        <td class="" style="font-size:10px;"></td>
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;<?= Config::get('company/address1'); ?></td>
                                    </tr>
                                    <tr align="left">
                                        <td class="" style="font-size:10px;"></td>
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;<?= Config::get('company/address2'); ?></td>
                                    </tr>
                                    <tr align="left">
                                        <td class="" style="font-size:10px;"></td>
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;<?= Config::get('company/phone'); ?></td>
                                    </tr>
                                    <tr align="left">
                                        <td class="" style="font-size:10px;"></td>
                                        <td class="" style="font-size:10px;">&nbsp;&nbsp;&nbsp;<?= Config::get('company/fax'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End of first row. -->

                <!-- 2nd row -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:-40px;">
                            <div class="row">
                                <h3 style="font-weight: bold;">
                                Rental Agreement
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 2nd row -->

                <!-- 3rd row -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div style="margin-top:0px;">
                            <div class="row">
                                <table class="cust_data_table" style="font-size:12px;">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Lessee:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerName(); ?></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;">&nbsp;&nbsp;&nbsp;Job Name:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $order->getJobName(); ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Address:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerAddress1(); ?></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;">&nbsp;&nbsp;&nbsp;Address:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $order->getJobAddress(); ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Address:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerAddress2(); ?></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;">&nbsp;&nbsp;&nbsp;</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;City/State/Zip:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerCity().', '.$customer->getCustomerState().', '.$customer->getCustomerZipcode(); ?></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;">&nbsp;&nbsp;&nbsp;City/State/Zip:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $order->getJobCity().', CA, '.$order->getJobZipcode(); ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Phone:</td>
                                            <?php if($customer->getCustomerFax() != ''): ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerPhone().'&nbsp;&nbsp;Ext: '.$customer->getCustomerFax(); ?></td>
                                            <?php else: ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerPhone(); ?></td>
                                            <?php endif; ?>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Ordered By:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $order->getOrderedBy(); ?></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;Fax:</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;">&nbsp;&nbsp;&nbsp;<?= $customer->getCustomerFax(); ?></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;width: 15%;">&nbsp;&nbsp;&nbsp;PO #</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 25px;text-align:center;"></td>                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 3rd row. -->

                <!-- 4th row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top:10px;">
                            <div class="row">
                                <table class="cust_data_table" style="font-size:12px;">
                                    <tbody>
                                        <tr align="left">
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;Customer Notes:</td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;Delivery Date:</td>
                                            <td class="" style=""><strong><?= $order->getDate(); ?></strong></td>
                                        </tr>
                                        <?php foreach($orderedProducts as $product): ?>
                                            <?php if(in_array($product->getModShortName(), $conArray) && $product->getProductType() == "container"): ?>
                                                <tr align="left">
                                                    <td class="" style="">&nbsp;&nbsp;&nbsp;Unit:</td>
                                                    <td class="" style=""><?= $product->getModName(); ?></td>
                                                    <td class="" style="">&nbsp;&nbsp;&nbsp;Unit Number:</td>
                                                    <td class="" style=""><strong></strong></td>
                                                    <td class="" style="">&nbsp;&nbsp;&nbsp;Rental Rate:</td>
                                                    <td class="" style=""><strong><?= $product->getProductCost(); ?></strong></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <tr align="left">
                                            <?php foreach($orderedProducts as $product): ?>
                                                <?php 
                                                    if(in_array($product->getModShortName(), $pudArray) && $product->getProductType() == "delivery"){
                                                        $deliveryTotal += $product->getProductCost();
                                                    }
                                                ?>
                                            <?php endforeach; ?>
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;Delivery:</td>
                                            <td class="" style=""><strong><?= number_format($deliveryTotal, 2); ?></strong></td>
                                            <?php foreach($orderedProducts as $product): ?>
                                                <?php 
                                                    if(in_array($product->getModShortName(), $pudArray) && $product->getProductType() == "pickup"){
                                                        $pickupTotal += $product->getProductCost();
                                                    }
                                                ?>
                                            <?php endforeach; ?>
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;Pickup:</td>
                                            <td class="" style=""><strong><?= number_format($pickupTotal, 2); ?></strong></td>
                                            <td class="" style=""></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;Damage Coverage:</td>
                                            <td class="" style="">None</td>
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;12% Storage</td>
                                            
                                            <td class="" style="">&nbsp;</td>
                                            <td class="" style="">&nbsp;&nbsp;&nbsp;15% Office</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 4th row. -->

                <!-- 6th Row. [Charge Explination] -->
                <div class="row">
                    <div class="col-md-12">
                        <div style="margin-top:20px;">
                            <div class="row">
                                <div class="container" style="">
                                    <span style="text-align:left;font-size:11px;">This agreement is made as of: <strong><?= $order->getDate(); ?></strong></span>
                                </div>
                                <div class="container" style="margin-top:5px;">
                                    <span style="text-align:left;font-size:11px;">Lessee hereby agrees to lease from Lessor the following equipment (‘Equipment’) on the terms and conditions stated herein:</span>
                                </div>
                                <div class="container" style="margin-top:5px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        Lessee agrees to pay Lessor the monthly rental rate, delivery and pick up charges, 
                                        sales tax and all other charges referred to herein for the use of Equipment. Individuals using Equipment 
                                        for personal use must complete the Customer Information Worksheet prior to rental, if requested. Rental 
                                        payments are on a monthly basis.
                                    </span>
                                </div>
                                <div class="container" style="margin-top:5px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        Lessee shall pay or shall reimburse Lessor for all fees or assessments related to the Equipment, its 
                                        value, use, operation or rental including storage related charges attributable to the delayed delivery, 
                                        pick up and/or installation of the Equipment required or requested by the Lessee.
                                        Payments are net 30 days upon invoicing. Lessee agrees to pay a late charge of 1 ½% per month or 18% per annum 
                                        on balances exceeding 30 days. Lessee assumes all risk of loss or damage to the Equipment (normal wear & tear excepted) 
                                        and all contents therein from any and all causes whatsoever. Lessee is liable for all repairs to, and cleaning of, the 
                                        Equipment. Lessee shall not move the Equipment. Lessee shall notify Lessor if relocation is required.
                                    </span>
                                </div>
                                <div class="container" style="margin-top:5px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        The Equipment is for domestic storage only and not to be used for any other purpose. Lessee agrees to indemnify, 
                                        defend and hold Lessor harmless from any and all losses, claims, or expenses, including, but not limited to those 
                                        arising out of or caused by the negligence of Lessor or its agents or employees, related to any loss or damage to 
                                        the Equipment and to any personal injury or property damage related to or arising out of the delivery, installation, 
                                        use, possession, condition, return or repossession of the Equipment.
                                    </span>
                                </div>
                                <div class="container" style="margin-top:5px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        Lessee’s failure to make any payment or comply with any terms and conditions herein will constitute default. Upon Lessee’s 
                                        default, Lessor has the right to accelerate all payments due hereunder, repossess the Equipment and take any action permitted 
                                        by the Uniform Commercial Code. Lessee hereby waives any and all rights to, or claims of sovereign immunity. Any property remaining 
                                        in Equipment upon its return or repossession will be subject to a claim or lien and may be sold to satisfy the lien if the rent or 
                                        other charges remain unpaid for 14 consecutive days. Please provide alternate name and address where preliminary lien notice and 
                                        other subsequent notices may be sent (if different than above):
                                    </span>
                                </div>
                                <div class="container" style="margin-top:10px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        NAME:_________________________ADDRESS:_____________________________CITY:___________________ST:______ZIP:________
                                    </span>
                                </div>
                                <div class="container" style="margin-top:10px;">
                                    <span style="text-align:justify;font-size:11px;">
                                        This Agreement continues on a month-to-month basis after the minimum lease term of one month, until the Equipment is returned to 
                                        Lessor. The rental rate may be increased by the Lessor at any given time during the rental period. By signing below, the parties 
                                        agree to the terms and conditions stated herein. By signing below, the parties agree that Equipment was delivered in good condition. 
                                        The parties are hereby authorized to accept and rely upon a facsimile signature of either party on this Agreement. Any such signature 
                                        shall be treated as an original signature for all purposes.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 6th Row. -->
                </br>
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
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Lessor:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong><?= Config::get('company/name'); ?></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Sign:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Sign:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Print:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Print:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Title:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong>Title:</strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                        </tr>
                                        <tr align="left">
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;I have authority to bind lease.</td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;width: 15%;">&nbsp;&nbsp;&nbsp;<strong></strong></td>
                                            <td class="" style="border: 1px solid black;font-size:12px;height: 30px;text-align:center;">&nbsp;&nbsp;&nbsp;I have authority to bind lease.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of 7th Row. -->      
                
                <!-- Need to add rental damage protection table. -->

            </div>
        </div>
    </body>
</html>