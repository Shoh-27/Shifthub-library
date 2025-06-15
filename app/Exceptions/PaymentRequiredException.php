<?php

namespace App\Exceptions;

use Exception;
use App\Models\Payment;

class PaymentRequiredException extends Exception
{
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        parent::__construct('Payment required for translation.');
    }

    public function getPayment()
    {
        return $this->payment;
    }
}
