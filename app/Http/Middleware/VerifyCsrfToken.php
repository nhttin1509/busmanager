<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
		'datveAndroid',
        'gettinh',
        'chuyenxeAndroid',
        'chonveAndroid',
        'lichsuAndroid',
        'dangkyAndroid',
        'dangnhapAndroid',
        'updateUserAndroid',
        'changePassAndroid',
        'dangnhapDriverAndroid',
        'dangkyDriverAndroid',
        'ticketInfoAndroid',
        'checkphoneAndroid',
        'dangkyveAndroid',
        'xulydatveAndroid',
        'destroydatveAndroid',
        'capnhatDriver',
        'doimatkhauDriver',
		'ticketAndroid'
    ];
}
