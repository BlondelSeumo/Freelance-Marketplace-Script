<?php

if($_POST['close'] == "close_cookies"){

   $cookie_name = "close_cookie";
   $cookie_value = "Cookie Bar";

}else{

   $cookie_name = "close_announcement";
   $cookie_value = $_POST['time'];

}

setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day


?>