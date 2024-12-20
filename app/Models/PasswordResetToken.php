<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordResetToken extends Model
{
    use HasFactory;
    protected $table = "password_reset_tokens";

    public static function fetchOriginalToken($token)
    {
        $passwordReset = null;
        $passwordResets = PasswordResetToken::all();
        foreach ($passwordResets as $record) {
            if (Hash::check($token, $record->token)) {
                $passwordReset = $record;
                break;
            }
        }
        return $passwordReset;
    }
}
