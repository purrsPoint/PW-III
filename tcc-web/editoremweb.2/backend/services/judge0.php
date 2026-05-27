<?php

class judge0{

    private string $urlbase;

    public function _construct(){
        $this->urlBase = "https://ce.judge0.com";
    }

    public function enviarcode(
        string $codigo,
        string $entrada = ""
    ): array{

        //oq vai ser enviado
        $payload = [
            "language_id" => 62,
            "source_code" => base64_encode($codigo),
            "stdin" => base64_encode($entrada)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [

            CURLOPT_URL => 
            $this-> urlbases . "/submissoions?base64_encode=true&await=false",

            CURLOPT_RETURNTRANSFER => true,

            CURL_POST => true,

            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],

            CURLOPT_POSTFIELDS =>  json_encode($payload);

        ]);

        $resposta = curl_exec($curl);

           //tratamento de erros
            if(curl_errno($curl)){
                return[
                    "erro" => curl_error($curl)
                ];
            }

            curl_close($curl);

            return json_decode($resposta, true);
    }

    public function pegaresult(
        string $token
    ): array {
        $curl = curl_init();

        curl_setopt_array($curl,[

            CURLOPT_URL =>  
            $this->urlbase ."/submissions/{$token}?base64_enconded=true",

            CURLOPT_RETURNTRANSFER => true
        ]);

        $resposta = curl_exec($curl);

        if(curl_errno($curl)){

            return [
                "erro" => curl_error($curl)
            ];
        }

        curl_close($curl);

        return json_decode($resposta, true);
    }

}