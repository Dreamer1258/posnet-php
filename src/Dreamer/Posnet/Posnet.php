<?php

namespace Dreamer\Posnet;


use Dreamer\Posnet\Exception\Posnet as PosnetException;
use Dreamer\Posnet\Connector\ConnectorInterface;
use Dreamer\Posnet\Protocol\Frame;

class Posnet
{
    private $_connector;
    private $_token;

    public function __construct()
    {
        $this->_connector = null;
        $this->_token = null;
    }

    function __destruct()
    {
        $this->disconnect();
    }

    public function setConnector(ConnectorInterface $connector)
    {
        $this->_connector = $connector;
    }

    public function setToken($token)
    {
        if(strlen($token) != 4 || !is_numeric($token)) {
            throw new PosnetException('Token must be numeric and 4 characters long.');
        }

        $this->_token = $token;
    }

    public function connect()
    {
        if($this->_connector instanceof ConnectorInterface) {
            $this->_connector->open();
            return true;
        }

        throw new PosnetException('Connector can not be null.');
    }

    public function disconnect()
    {
        if($this->_connector instanceof ConnectorInterface) {
            $this->_connector->close();
            return true;
        }

        throw new PosnetException('Connector can not be null.');
    }

    public function getInfo()
    {
        $sid = $this->request('sid');

        if(count($sid['args']) != 2) {
            throw new PosnetException('Device is not valid Posnet printer.');
        }

        return array(
            'name' => $sid['args']['nm'],
            'version' => $sid['args']['vr']
        );
    }

    private function request($command, array $args = array())
    {
        if(!($this->_connector instanceof ConnectorInterface)) {
            throw new PosnetException('Connector can not be null.');
        }

        $request_frame = Frame::encode($command, $args);
        $this->_connector->write($request_frame);

        usleep(1000000 / 4);

        $response_frame = $this->_connector->read();

        return Frame::decode($response_frame);
    }
}