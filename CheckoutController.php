<?php

class CheckoutController extends Controller
{
    public function checkoutConfirm(Request $request)
    {
        $tran_id = 'generateTransactionId';

        // Create Payment
         // write your code
        // Create Order
         // write your code
        //
        // and then finally payment
        SSLCommerzService::payment($request->all(),$others);
    }
}
