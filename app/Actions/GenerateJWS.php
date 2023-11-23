<?php

namespace App\Actions;

class GenerateJWS
{

  public function generate()
  {
    $header = [
      'alg' => 'HS256',
      'clientid' => env('PG_BD_MERCHANT_ID'),
    ];

    $payload = [
      'iss' => 'http://example.com',
      'aud' => 'http://example.org',
      'iat' => 1356999524,
      'nbf' => 1357000000
    ];

    $secret = env('PG_BD_CLIENT_SECRET'); // secret key currently hard coded for Bill Desk PG

    $header = json_encode($header);
    $payload = json_encode($payload);

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
  }

}
