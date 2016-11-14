<?php

namespace Dreamer\Posnet\Connector;


use Dreamer\Posnet\Exception\Connector;

class TcpSocketConnector implements ConnectorInterface
{
    private $_address = null;
    private $_port = null;
    private $_timeout = null;

    private $_socket = null;

    public function __construct($address, $port, $timeout = 10)
    {
        $this->_address = $address;
        $this->_port = $port;
        $this->_timeout = $timeout;
    }

    public function open()
    {
        $errno = null;
        $errstr = null;
        $this->_socket = fsockopen($this->_address, $this->_port, $errno, $errstr, $this->_timeout);

        if($errno != 0) {
            throw new Connector($errstr);
        }
    }

    public function write($data)
    {
        if($this->_socket) {
            if(fwrite($this->_socket, $data) == 0) {
                throw new Connector(error_get_last());
            }

            return true;
        }

        return false;
    }

    public function read()
    {
        if($this->_socket) {
            $buffer = '';
            while(($char = fread($this->_socket, 1)) !== false) {
                $buffer .= $char;

                if($char == "\x03") {
                    break;
                }
            }

            return $buffer;
        }

        return null;
    }

    public function close()
    {
        if($this->_socket) {
            fclose($this->_socket);
        }
    }
}