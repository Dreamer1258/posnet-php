<?php

use Dreamer\Posnet\Connector\TcpSocketConnector;
use Dreamer\Posnet\Posnet;

require_once 'autoload.php';

#91.90.188.5

try {
    $posnet = new Posnet();
//    $posnet->setConnector(new TcpSocketConnector('localhost', 8080));
    $posnet->setConnector(new TcpSocketConnector('91.90.188.5', 1024));
    $posnet->connect();
    print_r($posnet->getInfo());

//    $posnet->printDocument();

    $posnet->disconnect();
}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}