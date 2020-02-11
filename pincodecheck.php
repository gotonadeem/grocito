<?php

$logpostData    = array(
    "pickup_postcode"    => "302020",
    "delivery_postcode" => "302020",
    "weight" => "1",
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://apiv2.shiprocket.in/v1/external/courier/serviceability/",
   CURLOPT_POST           => true,
   CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS     => json_encode($logpostData),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjE5MjQ3OSwiaXNzIjoiaHR0cHM6Ly9hcGl2Mi5zaGlwcm9ja2V0LmluL3YxL2V4dGVybmFsL2F1dGgvbG9naW4iLCJpYXQiOjE1NjE1MzkzMDQsImV4cCI6MTU2MjQwMzMwNCwibmJmIjoxNTYxNTM5MzA0LCJqdGkiOiI0QjNTZ3p5UEk0aVZGOUZEIn0.T8B92xahd2sVOHnJ_TAMvbXag-fuevZJBBKBSBHIoXw",
    "Content-Type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}