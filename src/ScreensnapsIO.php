<?php

namespace TeamGC\ScreensnapsIO;

class ScreensnapsIO
{
    public $apiKey = "";
    public $userId = "";
    public $baseUrl = "https://api.screensnaps.io";
    public $apiVersion = "v1";
    public $connection_timeout = 3;
    public $execute_timeout = 25;

    function __construct($params)
    {

        if (!isset($params["apiKey"])) {
            throw new \Exception("Missing API Key");
        }

        if (!isset($params["userId"])) {
            throw new \Exception("Missing API Key");
        }

        if (isset($params["baseUrl"])) {
            $this->baseUrl = $params["baseUrl"];
        }

        if (isset($params["apiVersion"])) {
            $this->apiVersion = $params["apiVersion"];
        }

        if (isset($params["connection_timeout"])) {
            $this->connection_timeout = $params["connection_timeout"];
        }

        if (isset($params["execute_timeout"])) {
            $this->execute_timeout = $params["execute_timeout"];
        }

        $this->apiKey = $params["apiKey"];
        $this->userId = $params["userId"];
    }

    public function screenshots($params)
    {
        $offset = isset($params["offset"]) ? (int)$params["offset"] : 0;
        $limit = isset($params["limit"]) ? (int)$params["limit"] : 15;

        return $this->call("screenshots", "GET", ["offset" => $offset, "limit" => $limit]);
    }

    public function screenshot($params)
    {
        return $this->call("screenshot", "POST", $params);
    }

    public function status()
    {
        return $this->call("status", "GET", []);
    }

    public function query($params)
    {
        if (!isset($params["index"])) {
            throw new \Exception("Missing Index");
        }

        if (!isset($params["type"])) {
            throw new \Exception("Missing Type");
        }

        $this->index = $params["index"];
        $this->type = $params["type"];

        return $this->call("_search", "POST", $params["body"]);
    }

    private function call($path, $method = 'GET', $data = [])
    {
        $data["api_key"] = $this->apiKey;
        $getParams = http_build_query($data);

        $url = $this->baseUrl . '/' . $this->apiVersion . '/' . $path . "?" . $getParams;

        $headers = array('Authorization:' . $this->userId, 'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                unset($data["api_key"]);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            default:
                break;
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->execute_timeout);

        $response = curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->http_code = (int)$code;

        if ($this->http_code != 200) {
            $jsonError = ["httpCode" => $this->http_code, "results" => $response];
            throw new \Exception(json_encode($jsonError));
        }

        $json_decoded = json_decode($response);

        if ($json_decoded == null || $json_decoded == "") {
            $jsonError = ["httpCode" => $this->http_code, "results" => $response];
            throw new \Exception(json_encode($jsonError));
        }

        return $json_decoded;
    }
}
