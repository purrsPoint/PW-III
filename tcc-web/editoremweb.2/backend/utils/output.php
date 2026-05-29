<?php

function decodificar(?string $texto): string {
    if(!$texto){
        return "";
    }

    $textoDecodificado = base64_decode(
        $texto,
        true
    );

    return $textoDecodificado !== false ? $textoDecodificado : $texto;
}

function normalizarOutput(string $texto) : string{
    $texto = str_replace(
        "\r\n",
        "\n",
        $texto
    );

    return trim($texto);
}