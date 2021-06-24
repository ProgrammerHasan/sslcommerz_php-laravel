<?php
/**
 * Created by PhpStorm
 * User: ProgrammerHasan
 * Date: 31-10-2020
 * Time: 9:16 PM
 */
class SSLCommerzService
{
    // Service
    public static function payment($request_data,$tran_id,$order_id,$total_price,$currency): void
    {
        // SSLCOMMERZ API Init
        $post_data = array();
        $post_data['store_id'] = env('SSLCOMMERZ_STORE_ID');
        $post_data['store_passwd'] = env('SSLCOMMERZ_PASSWD');
        $post_data['total_amount'] = '';
        $post_data['currency'] = '';
        $post_data['tran_id'] = '';
        $post_data['success_url'] = route('your_success_url');
        $post_data['fail_url'] = route('your_faild_url');
        $post_data['cancel_url'] = route('your_cancel_url');
        # $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

        # EMI INFO
        $post_data['emi_option'] = "1";
        $post_data['emi_max_inst_option'] = "4";
        $post_data['emi_selected_inst'] = "9";

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = '';
        $post_data['cus_email'] = '';
        $post_data['cus_add1'] = '';
        $post_data['cus_add2'] = '';
        $post_data['cus_city'] = '';
        $post_data['cus_state'] = '';
        $post_data['cus_postcode'] = '';
        $post_data['cus_country'] = '';
        $post_data['cus_phone'] = '';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "";
        $post_data['ship_add1 '] = "";
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = "";
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = "";
        $post_data['ship_country'] = "";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = '';
        $post_data['value_b'] = '';
        $post_data['value_c'] = "";
        $post_data['value_d'] = "";

        $post_data['product_amount'] = '';
        $post_data['vat'] = "0";
        $post_data['discount_amount'] = '';
        $post_data['convenience_fee'] = "0";

        # REQUEST SEND TO SSLCOMMERZ
        self::send_to_sslcommerz($post_data);
        // End Ssl Api code
    }

    // =================================================== //
    // REQUEST SEND TO SSLCOMMERZ
    public static function send_to_sslcommerz($post_data): void
    {
        $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            curl_close( $handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close( $handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }

        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );

        if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
            # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
            # header("Location: ". $sslcz['GatewayPageURL']);
            exit;
        } else {
            echo "JSON Data parsing error!";
        }
    }

    // Payment Validate Checking
    public static function validate_payment($val_id): void
    {
        // this code for payment validation
        $val_id=urlencode($val_id);
        $store_id=urlencode(env('SSLCOMMERZ_STORE_ID'));
        $store_passwd=urlencode(env('SSLCOMMERZ_PASSWD'));
        $requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=4&format=json");

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true); # IF YOU RUN FROM LOCAL PC

        $result = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle)))
        {
            # TO CONVERT AS ARRAY
            # $result = json_decode($result, true);
            # $status = $result['status'];

            # TO CONVERT AS OBJECT
            $result = json_decode($result);

            # TRANSACTION INFO
            $status = $result->status;
            $tran_date = $result->tran_date;
            $tran_id = $result->tran_id;
            $val_id = $result->val_id;
            $amount = $result->amount;
            $store_amount = $result->store_amount;
            // 	$bank_tran_id = $result->bank_tran_id;
            // 	$card_type = $result->card_type;

            // 	# EMI INFO
            // 	$emi_instalment = $result->emi_instalment;
            // 	$emi_amount = $result->emi_amount;
            // 	$emi_description = $result->emi_description;
            // 	$emi_issuer = $result->emi_issuer;

            // 	# ISSUER INFO
            // 	$card_no = $result->card_no;
            // 	$card_issuer = $result->card_issuer;
            // 	$card_brand = $result->card_brand;
            // 	$card_issuer_country = $result->card_issuer_country;
            // 	$card_issuer_country_code = $result->card_issuer_country_code;

            // 	# API AUTHENTICATION
            // 	$APIConnect = $result->APIConnect;
            // 	$validated_on = $result->validated_on;
            // 	$gw_version = $result->gw_version;

        } else {

//            echo "Failed to connect with SSLCOMMERZ";
        }
    }
}
