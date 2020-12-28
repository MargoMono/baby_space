<?php

namespace App\API;


use DateTime;

class RussianPost
{
    private $object = 7020;
    private $from = 420076;
    private $to;
    private $weight;
    private $sumoc;

    public function __construct($to, $weight, $sumoc)
    {
        $this->to = $to;
        $this->weight = $weight;
        $this->sumoc = $sumoc;
    }

    public function getTariff()
    {
        $date = new DateTime();
        echo $date->format('Y-m-d H:i:s');

        $data = [
            'object' => $this->object,
            'from' => $this->from,
            'to' => $this->to,
            'weight' => $this->weight,
            'group' => 0,
            'sumoc' => $this->sumoc,
            'date' => $date->format('Ymd'),
            'time' => $date->format('Hi'),
        ];

        $getParams = http_build_query($data);
        $url = "https://tariff.pochta.ru/v2/calculate/tariff?json&{$getParams}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);;
        $result =  curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);
        return $response->ground->valnds;
    }
}