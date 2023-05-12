<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //

    public function store(StorePaymentRequest $request)
    {



        $validated = $request->validated();

        $paymentPlatform = resolve(PayPalService::class);

        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {

        $paymentPlatform = resolve(PayPalService::class);

        return $paymentPlatform->handleApproval();
    }

    public function cancel()
    {
    }
  
}
