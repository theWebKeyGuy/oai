<?php

namespace App\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use \Exception;

class UDPServer
{
    private $socket;

    /** 
     * Starts our udp server and listens for messages.
     * @throws Exception
     * @return bool
     */
    public function start() 
    {
        try {
            $this->CreateSocket();

            $this->BindSocket();

            return true;
        } catch(Exception $e) {
            Log::error('Caught Exception: '.$e->getMessage());
            Log::error('Error On Line '.$e->getLine());
            return false;
        }
    }

    /** 
     *  Gets our socket so we can use it in our terminal.
     */
    public function GetSocket()
    {
        return $this->socket;
    }

    /** 
     * Creates our UDP Socket.
     * @throws Exception
     * @return bool
     */
    protected function CreateSocket() 
    {
        if(!($this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)))
        {
            $errorcode  = socket_last_error();
            $errormsg   = socket_strerror($errorcode);

            throw new Exception('Couldn\'t bind socket: ['.$errorcode.'] '.$errormsg);
        }

        return true;
    }

    /** 
     * Binds OUR UDP Socket.
     * @throws Exception
     * @return bool
     */
    protected function BindSocket()
    {
        if(!socket_bind($this->socket, env('UDP_HOST', '127.0.0.1'), env('UDP_PORT', '3074')))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new Exception('Couldn\'t bind socket: ['.$errorcode.'] '.$errormsg);
        }

        return true;
    }

    /** 
     * Closes our socket server if needed.
     * @return bool
     */
    public function end()
    {
        if(!socket_close($this->socket)) {
            $errorcode = socket_last_error();
            $errormsg  = socket_strerror($errorcode);
            throw new Exception('Couldn\'t close socket. Error Code ['.$errorcode.'] '.$errormsg);
        }

        return true;
    }

    /** 
     * Sends a Message to the IP specified.
     * @param string $data
     * @param string $host
     * @param string $port
     * @throws Exception
     * @return bool
     */
    public function SendMessage(string $data, string $host, string $port = '3074')
    {
        if(empty($this->socket))
        {
            $this->CreateSocket();
        }

        if(!socket_sendto($this->socket, $data, strlen($data), 0, $host, $port))
        {
            $errorcode = socket_last_error();
            $errormsg  = socket_strerror($errorcode);
            throw new Exception('Can\'t Send Data to IP: '.$host.', Error Code ['.$errorcode.'] '.$errormsg);
        }

        return true;
    }
}