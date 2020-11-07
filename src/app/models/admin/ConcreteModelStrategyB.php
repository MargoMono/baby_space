<?php

namespace App\Model\Admin;

class ConcreteModelStrategyB implements ModelStrategy
{
    public function doAlgorithm(array $data): array
    {
        rsort($data);

        return $data;
    }
}