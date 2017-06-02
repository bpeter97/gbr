<?php

        // Create session variables - they're easier to use later on.
        echo $order_date = $_POST['frmorderdate'].'</br>';
        echo $order_time = $_POST['frmordertime'].'</br>';
        echo $order_customer = $_POST['frmcustomername'].'</br>';
        echo $ordered_by = $_POST['frmorderedby'].'</br>';
        echo $order_type = $_POST['frmordertype'].'</br>';
        echo $job_name = $_POST['frmjobname'].'</br>';
        echo $job_address = $_POST['frmjobaddress'].'</br>';
        echo $job_city = $_POST['frmjobcity'].'</br>';
        echo $job_zipcode = $_POST['frmjobzipcode'].'</br>';
        $unformatted_tax_rate = $_POST['frmtaxrate'];
        echo $tax_rate = (float)$unformatted_tax_rate.'</br>';
        echo $onsite_contact = $_POST['frmcontact'].'</br>';
        echo $onsite_contact_phone = $_POST['frmcontactphone'].'</br>';
        echo $total_cost = $_POST['cartTotalCost'].'</br>';
        echo $sales_tax = $_POST['cartTax'].'</br>';
        echo $cost_before_tax = $_POST['cartBeforeTaxCost'].'</br>';
        // echo '<pre>';
        // var_dump($cart_items = $_POST['cartData']);
        // echo '</pre>';
        $i = 0;
        while ($i < $_POST['itemCount'])
        {
            $product = json_decode($_POST['product'.$i], true);
            echo '<pre>';
            var_dump($product);
            echo '</pre>';
            echo '-----------------------------';
            echo $product['qty'];
            $i++;
        }


        //         // Insert the first part of the order into the database. If order is canceled later, we will delete it from the database.
        // $db->insert('orders',array(
        //     'order_customer'=>$custname,
        //     'order_date'=>$orderdate,
        //     'order_time'=>$ordertime,'ordered_by'=>$orderedby,'order_type'=>$ordertype,'job_name'=>$jobname,'job_address'=>$jobaddress,'job_city'=>$jobcity,'job_zipcode'=>$jobzipcode,'onsite_contact'=>$onsitecontact,'onsite_contact_phone'=>$onsitecontactphone
        //     ));
        
        // $res = $db->getResult();

        // if($res){
        //         // Grab the new ID if the insert worked.
        //         $_SESSION['new_order_id'] = $db->grabID();




    //                             // }
    //     $db->insert('product_orders',array('order_id'=>$orderid,'product_name'=>$prodName,'product_qty'=>$prodQty,'product_cost'=>$prodCost,'product_msn'=>$prodMsn,'product_type'=>$prodType));
    //     }

    // // This will update the total cost and sales tax on the quote at the end of this page.
    // $db->update('orders',array('total_cost'=>$total_cost,'sales_tax'=>$sales_tax,'cost_before_tax'=>$cbt, 'monthly_total'=>$monthly_total), 'order_id='.$orderid);

    // $datetime = $_SESSION['order_date'].' '.$_SESSION['order_time'];
    // $latertime = strtotime($datetime) + 60*60;
    // $endtime = date('Y/m/d H:i:s', $latertime);

    // // This will create the event.
    // $calendar = new Calendar;
    // $eventResult = $calendar->createEvent($_SESSION['customer_name'],$datetime,$endtime,'delivery',$db, $orderid);


?>