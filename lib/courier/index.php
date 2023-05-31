<?php

/**
 * 1) Install Courier SDK: composer require trycourier/courier
 * 2) Create this php file in your project's base folder
 */
require "./lib/courier/vendor/autoload.php";

use Courier\CourierClient;

function sendEmail_notification($email, $titre, $content)
{
  
        $courier =  new CourierClient("https://api.courier.com/", "pk_prod_ACX4R4H3EAM6K6KFCQ3K53R6PDMV");
      
        $result = $courier->sendEnhancedNotification(
          (object) [
            'to' => [
              'email' => $email
            ],
            'content' => [
              'title' => $titre,
              'body' => ".$titre.{{joke}}"
            ],
            'data' => [
              'joke' => $content,
            ],
          ]
        );
}
function sendEmail_notification1($email, $titre, $content)
{
  
        $courier =  new CourierClient("https://api.courier.com/", "pk_prod_ACX4R4H3EAM6K6KFCQ3K53R6PDMV");
      
        $result = $courier->sendEnhancedNotification(
          (object) [
            'to' => [
              'email' => $email
            ],
            'content' => [
              'title' => $titre,
              'body' => "COSAMED vous remercie pour votre message.'.$titre.' {{joke}} ?"
            ],
            'data' => [
              'joke' => $content,
            ],
          ]
        );
}

