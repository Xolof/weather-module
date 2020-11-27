<?php

namespace Anax\KeyHolder;

/**
 * A class to hold valu for API-key.
 */
class KeyHolder
{
    private $key;



    public function getKey() : string
    {
        return $this->key;
    }



    public function setKey($key) : void
    {
        $this->key = trim($key);
    }
}
