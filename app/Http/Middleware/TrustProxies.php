<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    // protected $proxies;
        // protected $proxies = [
        // '103.21.244.0/22',
        // '103.22.200.0/22',
        // '103.31.4.0/22',
        // '104.16.0.0/12',
        // '108.162.192.0/18',
        // '131.0.72.0/22',
        // '141.101.64.0/18',
        // '162.158.0.0/15',
        // '172.64.0.0/13',
        // '173.245.48.0/20',
        // '188.114.96.0/20',
        // '190.93.240.0/20',
        // '197.234.240.0/22',
        // '198.41.128.0/17'];
        protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
