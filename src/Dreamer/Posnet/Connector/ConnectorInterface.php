<?php

namespace Dreamer\Posnet\Connector;


interface ConnectorInterface
{
    public function open();

    public function write($data);

    public function read();

    public function close();
}