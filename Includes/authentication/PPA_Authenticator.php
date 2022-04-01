<?php

declare(strict_types=1);

namespace PPA\includes\authentication;

use WP_REST_Request;

defined('ABSPATH') || die;

class PPA_Authenticator
{
    public function authenticate(WP_REST_Request $request) : bool
    {
        //TODO: implement proper authentication
        return true;
    }
}
