<?php

function RetrieveItems()
{
    echo "\r\n**************************************";
    console.log("Log: ", "*****************");
    $application = getenv("VCAP_APPLICATION");
    echo "\r\napplication:" . $application;
    console.log("Application: ", $application);
    $application_json = json_decode($application, true);
    $applicationURI = $application_json["application_uris"][0];
    echo "\r\napplicationURI:" . $applicationURI;
    console.log("Application URI: ", $applicationURI);
    if (substr( $applicationURI, 0, 3 ) === "ui-") {
        $catalogHost = "catalog-api-" . substr($applicationURI, 3);
    } else {
        $catalogHost = str_replace("-ui-", "-catalog-api-", $applicationURI);
    }
    echo "\r\ncatalogHost:" . $catalogHost;    
    console.log(">>>>>>>>>>>>>>Catalog Host:", $catalogHost);
    $catalogRoute = "http://" . $catalogHost;
    echo "\r\ncatalogRoute:" . $catalogRoute;  
    console.log(">>>>>>>>>>>>>>Catalog Route", $catalogRoute);
    $url = $catalogRoute . "/items";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $curlResult = curl_exec($curl);
    $curlError = curl_error($curl);
    $curlErrno = curl_errno($curl);
    curl_close($curl);
    $firstChar = substr($curlResult, 0, 1); /* should check if $curlResult === FALSE if newer PHP */
    if ($firstChar != "{") {
        $errorObject = new stdClass();
        $errorObject->error = $curlError;
        $errorObject->errno = $curlErrno;
        return json_encode($errorObject);
    }
    return $curlResult;
}

?>

