<?php

namespace App\Utils;

class MultipleRequests
{
    private $curl_handle;
    private $requestList = array();
    function __construct($seconds = 60)
    {
        set_time_limit($seconds);
    }


    public function setRequestList($list)
    {
        $this->requestList = $list;
    }

    public function processRequests()
    {

        $mh = curl_multi_init();
        $result = array();

        // Create the curl handles and add them to the multi_request
        foreach ($this->requestList as $i => $url) {
            $conn[$i] = curl_init($url);

            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => "{\n  \n\n}",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "x-api-key: whateveriyouneedinyourheader"
                ),
            );
            curl_setopt_array($conn[$i], $options);
            curl_multi_add_handle($mh, $conn[$i]);

            $result[uniqid()] = array(
                'url' => $url,
                'conn' => $conn[$i],
                'error' => '',
                'response' => ''
            );
        }

        // executing all the pending requests
        $running = NULL;
        do {
            $status = curl_multi_exec($mh, $running);
            if (curl_multi_select($mh) == -1) {
                usleep(250);
            }
        } while ($running);



        //reading status and prepary the response objects
        foreach ($result as $key => $urlReq) {
            $urlReq["body"] = curl_multi_getcontent($urlReq["conn"]);
            $error = curl_multi_info_read($mh, $urlReq["conn"]);
            if ($error && $error['result'] != 0) { // checks if there is error
                $urlReq["error"] = curl_error($error['handle']);
            }
            $result[$key] = $urlReq;
            //curl_close($conn[$i]);
        }
        curl_multi_close($mh);

        return $result;
    }
}
