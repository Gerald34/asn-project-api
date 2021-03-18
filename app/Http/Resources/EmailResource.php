<?php

namespace App\Http\Resources;
use App\Http\Resources\GeneratorResource;
use Illuminate\Support\Facades\Mail;
class EmailResource {

    public function sendVerificationEmail(string $email, string $name, string $code): void {
        $data = ['name'=> $name, 'body' => $code];
        Mail::send('verification', $data, function($message) use ($name, $email) {
            $message->to($email, $name)->subject('Account Verification');
            $message->from('code45dev@gmail.com', 'Accounts');
        });
    }
}
