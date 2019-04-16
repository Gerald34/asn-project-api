<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneratorResource extends JsonResource
{

    /**
     * @param string $name
     * @return mixed
     */
    public static function generateSlugFromName(string $name) {
        return strtolower(str_replace(' ', '_', $name));
    }

    /**
     * @param $length
     * @return string
     */
    public static function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtolower($randomString);
    }
}
