<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FilesResource extends JsonResource {
    protected static $response;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return parent::toArray($request);
    }

    /**
     * @param string $file_name
     * @param string $path
     * @return array|\Illuminate\Http\Response
     */
    public static function retrieveFile(string $file_name, string $path) {
        $filePath = storage_path( $path . $file_name);
        return (!File::exists($filePath)) ? ['error' => 'file not found.'] : self::_getFile($filePath);
    }

    /**
     * Fetch file if path and file name return true
     * @param $path
     * @return \Illuminate\Http\Response
     */
    private static function _getFile($path = '') {
        $file = File::get($path);
        $type = File::mimeType($path);
        self::$response = Response::make($file, 200);
        self::$response->header("Content-Type", $type);

        return self::$response;
    }
}
