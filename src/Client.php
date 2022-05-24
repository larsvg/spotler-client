<?php

namespace Spotler;

use CurlHandle;
use Spotler\Exceptions\SpotlerException;

class Client
{
    private string  $consumerKey;
    private string  $consumerSecret;
    private string  $bashUrl        = 'https://restapi.mailplus.nl';
    private ?string $certificate    = null;
    private string  $verifyCertificate;
    private string  $oauthSignature = 'HMAC-SHA1';
    private string  $oauthVersion   = '1.0';
    private int     $responseCode;
    private         $responseBody;



    public function __construct(
        string  $consumerKey,
        string  $consumerSecret,
        ?string $certificate = null,
        bool    $verifyCertificate = true
    ) {
        $this->consumerKey       = $consumerKey;
        $this->consumerSecret    = $consumerSecret;
        $this->certificate       = $certificate;
        $this->verifyCertificate = $verifyCertificate;
    }



    /**
     * @throws SpotlerException
     */
    public function execute(string $endpoint, string $method = 'GET', $data = null): bool|string
    {
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: " . $this->createAuthorizationHeader($method, $endpoint),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => sprintf('%s/%s', $this->bashUrl, $endpoint),
            CURLOPT_HEADER         => 0,
        ]);
        $curl = $this->setExecuteMethode($curl, $method, $data);
        $curl = $this->setVerifyHostCertificate($curl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response           = curl_exec($curl);
        $this->responseBody = $response;
        $this->responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // when $response return false we have a critical error
        if ($response === false) {
            throw new SpotlerException(curl_error($curl), curl_errno($curl));
        }
        curl_close($curl);
        return $response;
    }



    public function getLastResponseCode(): int
    {
        return $this->responseCode;
    }



    public function getLastResponseBody()
    {
        return $this->responseBody;
    }



    private function setExecuteMethode(CurlHandle $curl, string $method, $data = null): CurlHandle
    {
        if ($method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        } elseif ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        return $curl;
    }



    private function setVerifyHostCertificate(CurlHandle $curl): CurlHandle
    {
        if ($this->verifyCertificate) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            return $curl;
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        return $curl;
    }



    private function createAuthorizationHeader($method, $endpoint): string
    {
        $authParams = [
            'oauth_consumer_key'     => $this->consumerKey,
            'oauth_signature_method' => $this->oauthSignature,
            'oauth_timestamp'        => time(),
            'oauth_nonce'            => md5(microtime(true)),
            'oauth_version'          => $this->oauthVersion,
        ];

        $authParams['oauth_signature'] = $this->createSignature($authParams, $method, $endpoint);

        $authParamsValues = [];
        foreach ($authParams as $paramName => $paramValue) {
            $authParamsValues[] = $paramName . '="' . $paramValue . '"';
        }

        return 'OAuth ' . implode(',', $authParamsValues);
    }



    private function createSignature(array $authParams, string $method, string $endpoint): string
    {
        $sigBase = strtoupper($method) . "&" . rawurlencode($this->bashUrl . '/' . $endpoint) . "&"
            . rawurlencode("oauth_consumer_key=" . rawurlencode($this->consumerKey)
                . "&oauth_nonce=" . rawurlencode($authParams['oauth_nonce'])
                . "&oauth_signature_method=" . rawurlencode($this->oauthSignature)
                . "&oauth_timestamp=" . $authParams['oauth_timestamp']
                . "&oauth_version=" . $this->oauthVersion);
        $sigKey  = $this->consumerSecret . "&";

        return base64_encode(hash_hmac("sha1", $sigBase, $sigKey, true));
    }
}