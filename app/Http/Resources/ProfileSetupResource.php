<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class ProfileSetupResource {

    public static function newProfileSetup(string $uid) {
        Storage::disk('profiles')->makeDirectory("{$uid}");
        self::_defaultAvatar($uid);
        // self::_defaultCoverImage($uid);
    }

    protected static function _defaultAvatar(string $uid): void {
        Storage::disk('profiles')->makeDirectory("{$uid}/avatars/");
        $defaultAvatar = storage_path() . '/app/profiles/avatars/default.jpg';
        $fileDestination = storage_path() . '/app/profiles/' . $uid . '/avatars/default.jpg';
        copy($defaultAvatar, $fileDestination);
    }

    protected static function _defaultCoverImage(string $uid): void {
        Storage::disk('profiles')->makeDirectory("{$uid}/covers/");
        $defaultAvatar = storage_path() . '/app/profiles/covers/default.jpg';
        $fileDestination = storage_path() . '/app/profiles/' . $uid . '/covers/default.jpg';
        copy($defaultAvatar, $fileDestination);
    }
}
