<?php

namespace App\Services\Helpers;

class ObjectToArray
{
    public function handle(mixed $data): mixed
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        return is_array($data) ? array_map(__METHOD__, $data) : $data;
    }
}
