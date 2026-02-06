<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $payType = $request->session()->get('pay_type');

        if ($payType === 'mchango_mdogo') {
            return redirect()->intended('/mchango_mdogo');
        }

        if ($payType === 'mchango_mkubwa') {
            return redirect()->intended('/mchango_mkubwa');
        }

        return redirect()->intended(config('fortify.home'));
    }
}
