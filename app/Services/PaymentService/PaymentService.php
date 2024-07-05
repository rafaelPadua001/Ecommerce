<?php

namespace App\Services\PaymentService;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService\Partials\CreditPayment;
use App\Services\PaymentService\Partials\DebitPayment;
use App\Services\PaymentService\Partials\PixPayment;



class PaymentService
{
    protected $payment;
    protected $debitPaymentService;
    protected $creditPaymentService;
    protected $pixPaymentService;
    public function __construct(
        Payment $payment,
        DebitPayment $debitPaymentService,
        CreditPayment $creditPaymentService,
        PixPayment $pixPaymentService,
    ) {
        $this->payment = $payment;
        $this->debitPaymentService = $debitPaymentService;
        $this->creditPaymentService = $creditPaymentService;
        $this->pixPaymentService = $pixPaymentService;
    }
    public function paymentType(Request $request)
    {
        $paymentType = $request->paymentType;
        if ($paymentType == 'credit') {
            $creditPayment = $this->creditPaymentService->creditPayment($request);
            return $creditPayment;
        } else if ($paymentType == 'DebitCard') {
            $debitPayment = $this->debitPaymentService->debitPayment($request);
            return $debitPayment;
        } else {
            $pixPayment = $this->pixPaymentService->pixPayment($request);
            return $pixPayment;
        }
    }
}
