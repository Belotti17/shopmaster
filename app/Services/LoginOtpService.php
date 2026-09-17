<?php

namespace App\Services;

use App\Models\LoginOtpCode;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Support\Facades\Hash;

class LoginOtpService
{
    public function sendCode(User $user): void
    {
        $user->loginOtpCodes()->delete();
        $code = (string) random_int(100000, 999999);

        LoginOtpCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new LoginOtpNotification($code));
    }

    public function verifyCode(User $user, string $code): bool
    {
        $otp = $user->loginOtpCodes()->latest()->first();

        if (!$otp || $otp->expires_at->isPast()) {
            $otp?->delete();
            return false;
        }

        if (!Hash::check($code, $otp->code)) {
            return false;
        }

        $otp->delete();
        return true;
    }
}
