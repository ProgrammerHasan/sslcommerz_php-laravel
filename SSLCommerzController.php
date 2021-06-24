<?php

namespace App\Http\Controllers;

use App\SSLCommerzService;
use Illuminate\Http\Request;

class SSLCommerzController extends Controller
{
    // IPN listener set your sslcommerz merchant panel  https://yoursite.com/sslcommerz/ipn-listener
    public function ipn_listener(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $currency = $request->input('currency');
        $val_id = $request->input('val_id');

        if($request->input('status') == 'VALID') {
            // Create a New Payment

            // this static function for payment validation
            SSLCommerzService::validate_payment($val_id);

            CheckoutController::checkout_success($payment);
        }
    }

}
