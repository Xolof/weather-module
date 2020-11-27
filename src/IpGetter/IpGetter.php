<?php

namespace Anax\IpGetter;

/**
 * A class to get Ip-adress from client.
 */
class IpGetter
{
    public function getUserIP()
    {
        // if test env
        if (defined("PHPUNIT_RAMVERK1_TESTSUITE") && PHPUNIT_RAMVERK1_TESTSUITE) {
            return "185.236.203.11";
        }

        // else
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ipAddress = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ipAddress = $forward;
        } else {
            $ipAddress = $remote;
        }

        return $ipAddress;
    }
}
