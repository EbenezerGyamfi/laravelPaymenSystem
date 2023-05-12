<?php


namespace App\Services;

use App\Traits\ConsumeExternalServices;
use Illuminate\Http\Request;

class PayPalService
{

    use ConsumeExternalServices;
    protected $baseUri;
    protected $clientId;
    protected $clientSecret;


    public function __construct()
    {
        $this->baseUri = config('services.paypal.base_uri');

        $this->clientId = config('services.paypal.client_id');

        $this->clientSecret = config('services.paypal.client_secret');
    }


    public function   resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function   decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {

        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
        return "Basic {$credentials}";
    }

    public function createOrder($value, $currency)
    {

        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
            [],
            [

                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => $value
                        ]
                    ]
                ],
                'intent' => 'CAPTURE',

                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'action' => 'PAY_NOW',
                    'return_url' => route('approval'),
                    'cancel_url' => route('cancel'),
                ],
            ],

            [],
            $isJsonRequest = true
        );
    }

    public function capturePayment($approvalId)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalId}/capture",
            [],
            [],
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function handlePayment(Request $request)
    {
        $order = $this->createOrder($request->amount, $request->iso);

        $orderLinks = collect($order->links);

        $approve = $orderLinks->where('rel', 'approve')->first();

        session()->put('approvalId', $order->id);

        return redirect($approve->href);
    }


    public function handleApproval()
    {

        if (session()->has('approvalId')) {
            $approvalId = session()->get('approvalId');
            $this->capturePayment($approvalId);

            return redirect()->route('home')->with('success', 'Thank you very much');
        }

        return redirect()->route('home')->withErrors("We cannot capture the payment, please try again later");
    }
}
