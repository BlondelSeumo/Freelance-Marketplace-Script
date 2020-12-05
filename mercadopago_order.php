<?php

session_start();
require_once("includes/db.php");
require_once("functions/payment.php");

if(!isset($_SESSION['seller_user_name'])){
   echo "<script>window.open('login','_self');</script>";
}


if(isset($_GET['reference_no'])){

   $payment = new Payment();
   $payment->mercadopago_execute();

}