<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GenerateJWS
{

  public static function encryptPG(Order $order,$ip="127.0.0.1",$user_agent="Mozilla/5.0(WindowsNT10.0;WOW64;rv:51.0)Gecko/20100101 Firefox/51.0",$accept_header="text/html")
  {
    $header = [
      'alg' => 'HS256',
      'clientid' => env('PG_BD_CLIENT_ID'),
    ];

    $payload = [
      'mercid' => env('PG_BD_MERCHANT_ID'),
      'orderid' => 'SPO48'.sprintf("%07d", $order->id),
      'amount' => number_format($order->total_order_amount/100, 2),
      'order_date' => $order->tenant_order_date_time,
      'currency' => '356',
      'ru' => 'https://splitpayments.in/wh/transactions/status',
      'additional_info' => '',
      'itemcode' => 'DIRECT',
      'split_payment' => [
        [
          'mercid' => 'UATFORT1V2',
          'amount' => number_format($order->submerchant_payout_amount/100, 2),
        ],
        [
          'mercid' => 'UATFORT2V2',
          'amount' => number_format($order->tenant_commission_amount/100, 2),
        ],
      ],
      'device' => [
        'init_channel' => 'internet',
        'ip' => $ip,
        'user_agent' => $user_agent,
        'accept_header' => $accept_header,
        'fingerprintid' => '61b12c18b5d0cf901be34a23ca64bb19',
        'browser_tz' => '-330',
        'browser_color_depth' => '32',
        'browser_java_enabled' => 'false',
        'browser_screen_height' => '768',
        'browser_screen_width' => '1366',
        'browser_language' => 'en-US',
        'browser_javascript_enabled' => 'true',
        ]
    ];


    $secret = env('PG_BD_CLIENT_SECRET'); // secret key currently hard coded for Bill Desk PG

    $header = json_encode($header);
    $payload = json_encode($payload);
    
    Log::info($payload);

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
  }


  public static function decryptPG($jwt)
  {
    $secret = env('PG_BD_CLIENT_SECRET'); // secret key currently hard coded for Bill Desk PG

    $jwt_values = explode('.', $jwt);
    $recieved_signature = $jwt_values[2];
    $recieved_header_and_payload = $jwt_values[0] . '.' . $jwt_values[1];

    $valid_signature = hash_hmac('sha256', $recieved_header_and_payload, $secret, true);
    $base64UrlValidSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($valid_signature));

    if ($base64UrlValidSignature == $recieved_signature) {
      $header = json_decode(base64_decode($jwt_values[0]), true);
      $payload = json_decode(base64_decode($jwt_values[1]), true);
      return $payload;
    } else {
      //$header = json_decode(base64_decode($jwt_values[0]), true);
      //$payload = json_decode(base64_decode($jwt_values[1]), true);
      //dd($jwt,$jwt_values,$recieved_signature,$recieved_header_and_payload,$valid_signature,$base64UrlValidSignature,$header,$payload);
      //return json_decode(base64_decode($jwt_values[1]), true);
      return "ER48027";
    }
  }

}
