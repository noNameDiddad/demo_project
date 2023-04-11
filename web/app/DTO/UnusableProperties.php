<?php

namespace App\DTO;

trait UnusableProperties
{

    private function unsetUnusableProperties(array $array): array
    {
        foreach ($array as $key => $item) {
            if ($item === "" || $item === null) unset($array[$key]);
        }
        return $array;
    }
}
