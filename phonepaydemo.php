<?php

$apiKey ="099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
$merchantId = 'PGTESTPAYUAT';
$keyIndex= 1;

$paymentData = array(
    'merchantId' => 'PGTESTPAYUAT',
    'merchantTransactionId' => 'MT7850590068188104',
    "merchantUserId"=>"MUID123",
    'amount' => 1000,
    'redirectUrl'=>'http://localhost/Restaurant-Management-Project/phonepaysuccess.php',
    'redirectMode'=>'POST',
    'callbackUrl'=>'http://localhost/Restaurant-Management-Project/phonepaysuccess.php',
    "merchantOrderId"=>"12345",
   "mobileNumber"=>"1234567890",
   "message"=>"payment",
   "email"=>"test@gmail.com",
   "shortName"=>"test",
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);


$jsonencode = json_encode($paymentData);
$payloadMain = base64_encode($jsonencode);


$payload = $payloadMain.'/pg/v1/pay'.$apiKey;
$sha256 = hash("sha256", $payload);
$finalheader = $sha256.'###'.$keyIndex;
$request = json_encode(array('request'=>$payloadMain));


$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY:" . $finalheader,
     "accept: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);

    print_r($res);

if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;
header("Location:$payUrl");
}
}
        


?>
