<?php

$Access_Key_ID = ''; //Contained within the downloaded file when generating a new key from your account security settings
$Private_Key = ''; //Contained within the downloaded file when generating a new key from your account security settings
$Associate_tag = ''; //something-20

$Operation = 'ItemSearch';
$Version = '2013-08-01';
$ResponseGroup = 'ItemAttributes';
$SearchIndex = 'VideoGames'; //Leave this blank to get an XML response containing the available search indexes
$Keywords = 'battlegrounds'; //Replace with your keyword
$Timestamp = urlencode(date('c')); 
$SignatureVersion = '4';
$Region = 'com';

$request= array(
   "AssociateTag" => $Associate_tag,
   "AWSAccessKeyId" => $Access_Key_ID,
   "Operation" => $Operation,
   "Version" => $Version,
   "SearchIndex" => $SearchIndex,
   "Keywords" => $Keywords,
   "Timestamp" => $Timestamp,
   "ResponseGroup" => $ResponseGroup,
   "SignatureVersion" => $SignatureVersion
   );

function aws_signed_request($region, $params, $public_key= null, $private_key = null, $associate_tag= null, $version='2013-08-01')
{
    $method = 'GET';
    $host = 'webservices.amazon.' . $region;
    $uri = '/onca/xml';
    
    // additional parameters
    $params['Service'] = 'AWSECommerceService';
    $params['AWSAccessKeyId'] = $public_key;
    // GMT timestamp
    $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    // API version
    $params['Version'] = $version;
    if ($associate_tag !== NULL) {
        $params['AssociateTag'] = $associate_tag;
    }

    ksort($params);
    
    $canonicalized_query = array();
    foreach ($params as $param=>$value)
    {
        $param = str_replace('%7E', '~', rawurlencode($param));
        $value = str_replace('%7E', '~', rawurlencode($value));
        $canonicalized_query[] = $param.'='.$value;
    }

    $canonicalized_query = implode('&', $canonicalized_query);
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;

    $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $private_key, TRUE));
    $signature = str_replace('%7E', '~', rawurlencode($signature));
    
    $request = 'http://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;
    
    return $request;
}

$finalRequest = aws_signed_request($Region, $request, $Access_Key_ID, $Private_Key, $Associate_tag, $Version);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $finalRequest);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$response = curl_exec($ch);
curl_close($ch);
$parsed_xml = new SimpleXMLElement($response);

print_r($parsed_xml->Items); //Print or return whatever you want
?>