<?php

class Judge0 {

    private string $urlBase;

    public function __construct()
    {
        $this->urlBase = "https://ce.judge0.com";
    }

    public function submitCodigo(
        string $code,
        string $stdin = ""
    ): array {

        $payload = [
            "language_id" => 62,
            "source_code" => base64_encode($code),
            "stdin" => base64_encode($stdin)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL =>
                $this->urlBase .
                "/submissions?base64_encoded=true&wait=false",

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],

            CURLOPT_POSTFIELDS => json_encode($payload)

        ]);

        $response = curl_exec($curl);

        if(curl_errno($curl)) {

            return [
                "erro" => curl_error($curl)
            ];
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    public function getSubmission(
        string $token
    ): array {

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL =>
                $this->urlBase .
                "/submissions/{$token}?base64_encoded=true",

            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($curl);

        if(curl_errno($curl)) {

            return [
                "erro" => curl_error($curl)
            ];
        }

        curl_close($curl);

        return json_decode($response, true);
    }
}
?>