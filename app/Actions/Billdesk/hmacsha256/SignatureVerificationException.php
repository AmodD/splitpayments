<?php

//namespace io\billdesk\client\hmacsha256;
namespace App\Actions\Billdesk\Hmacsha256;

use Exception;

class SignatureVerificationException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
