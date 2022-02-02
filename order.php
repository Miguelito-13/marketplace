<?php 

  session_start();

  include("includes/db.php");
  include("functions/functions.php");

  if(isset($_GET['c_id'])){

    $customer_id = $_GET['c_id'];

  }

  $ip_add = getRealIpUser();

  $status = "Pending";

  $invoice_no = mt_rand();

  $select_cart = "select * from dlpp_cart where ip_add='$ip_add'";

  $run_cart = mysqli_query($conn,$select_cart);

  while($row_cart=mysqli_fetch_array($run_cart)){

    $pro_id = $row_cart['p_id'];

    $pro_qty = $row_cart['qty'];

    $pro_size = $row_cart['size'];

    $get_products = "select * from dlpp_products where product_id='$pro_id'";

    $run_products = mysqli_query($conn,$get_products);

    while($row_product=mysqli_fetch_array($run_products)){

      $sub_total = $row_product['product_price']*$pro_qty;

      $insert_customer_order = "insert into dlpp_customer_orders (customer_id,due_amount,invoice_no,qty,size,order_date,order_status) 
      values ('$customer_id','$sub_total','$invoice_no','$pro_qty','$pro_size',NOW(),'$status')";

      $run_customer_order = mysqli_query($conn,$insert_customer_order);

      $insert_pending_order = "insert into dlpp_pending_orders (customer_id,invoice_no,product_id,qty,size,order_status) 
      values ('$customer_id','$invoice_no','$pro_id','$pro_qty','$pro_size','$status')";

      $run_pending_order = mysqli_query($conn,$insert_pending_order);

      $delete_cart = "delete from dlpp_cart where ip_add='$ip_add'";

      $run_delete = mysqli_query($conn,$delete_cart);

      echo "<script>alert('Your orders has been submitted')</script>";

      echo "<script>window.open('customer/my_account.php?my_orders','_self')</script>";

    }

  }

?>