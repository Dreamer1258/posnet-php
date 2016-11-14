<?php

namespace Dreamer\Posnet\Exception;


class Connector extends Posnet
{
    public function __construct($message)
    {
        $message = 'PosnetConnector error: ' . $message;
        parent::__construct($message);
    }
}