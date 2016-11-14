<?php

namespace Dreamer\Posnet\Connector;


class SerialPortConnector implements ConnectorInterface
{
    const COM0 = 'tty0';
    const COM1 = 'tty1';
    const COM2 = 'tty2';
    const COM3 = 'tty3';
    const COM4 = 'tty4';

    const TEST = '../tmp/vmodem0';

    private $_port = null;
    private $_fd = null;

    public function __construct($port)
    {
        $this->_port = $port;
    }

    public function open()
    {
        $this->_fd = fopen('/dev/' . $this->_port, 'r+');

        if(!$this->_fd) {
            throw new \Exception('Posnet SerialConnector error: Unable to open serial port');
        }
    }

    public function write($data)
    {
        if($this->_fd) {
            if(fwrite($this->_fd, $data) == 0) {
                throw new \Exception('Posnet SerialConnector error: ' . error_get_last());
            }

            return true;
        }

        return false;
    }

    public function read()
    {
        if($this->_fd) {
            $buffer = '';
            while(($char = fread($this->_fd, 1)) !== false) {
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
        if($this->_fd) {
            fclose($this->_fd);
        }
    }
}