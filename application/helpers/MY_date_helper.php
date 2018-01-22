<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function dataUS($data, $separator = '-')
{
    $arr = self::dataBR2US($data, $separator);
    if (isset($arr['dataUS']))
    {
        return $arr['dataUS'];
    }
    return '0000-00-00';
}

function dataBR($data, $separator = '/')
{
    $arr = dataBR2US($data, $separator);
    if (isset($arr['dataBR']))
    {
        return $arr['dataBR'];
    }
    return '';
}

function dataBR2US($data, $separator = '-')
{
    $data = str_replace("/", "-", $data);
    $data = str_replace(".", "-", $data);
    $data = str_replace("_", "-", $data);
    $data = str_replace("\\", "-", $data);
    $retorno = Array();

    // Normaliza os numeros menores que 10 para eles terem o ZERO na frente. (i.e: 8 vira 08)
    $dataArr = explode("-", $data);
    foreach ($dataArr as $key => $value)
    {
        if ($value < 10 && (strlen($value) == 1))
        {
            $dataArr[$key] = "0" . $value;
        }
    }

    // Verifica se a data tem 10 caracteres (xx-xx-xxxx)		
    if (strlen(implode("-", $dataArr)) != 10)
    {
        return 0;
    }

    if (!is_array($dataArr) || count($dataArr) != 3)
    {
        return 0;
    }

    // Verifica se a data está em portugues	DD-MM-AAAA
    if ((strlen($dataArr[0]) == 2) && (strlen($dataArr[1]) == 2) && (strlen($dataArr[2]) == 4))
    {
        $retorno["dia"] = $dataArr[0];
        $retorno["mes"] = $dataArr[1];
        $retorno["ano"] = $dataArr[2];
        $retorno["dataBR"] = $dataArr[0] . $separator . $dataArr[1] . $separator . $dataArr[2];
        $retorno["dataUS"] = $dataArr[2] . $separator . $dataArr[1] . $separator . $dataArr[0];
    }
    // Verifica se a data está em ingles AAAA-MM-DD
    elseif ((strlen($dataArr[2]) == 2) && (strlen($dataArr[1]) == 2) && (strlen($dataArr[0]) == 4))
    {
        $retorno["dia"] = $dataArr[2];
        $retorno["mes"] = $dataArr[1];
        $retorno["ano"] = $dataArr[0];
        $retorno["dataBR"] = $dataArr[2] . $separator . $dataArr[1] . $separator . $dataArr[0];
        $retorno["dataUS"] = $dataArr[0] . $separator . $dataArr[1] . $separator . $dataArr[2];
    }

    // Dia maior que 31 nao existe
    if ($retorno["dia"] > 31)
    {
        return 0;
    }
    // Mes maior que 12 nao existe
    if ($retorno["mes"] > 12)
    {
        return 0;
    }

    // Verifica se é uma data valida
    if (!checkdate($retorno["mes"], $retorno["dia"], $retorno["ano"]))
    {
        return 0;
    }

    // Se achar alguma coisa diferente de numero na data, retorna 0
    if (preg_match("/[^0-9]/", $retorno["dia"] . $retorno["mes"] . $retorno["ano"]))
    {
        return 0;
    }

    return $retorno;
}
