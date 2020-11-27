<?php

namespace Anax\IpValidator;

/**
 * A class to validate Ip-adresses.
 */
class IpValidator
{
    public function validateIp($ipAdress)
    {
        if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return "IPv6";
        };

        if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return "IPv4";
        };

        return false;
    }
}
