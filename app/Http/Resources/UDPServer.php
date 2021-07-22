<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use \Exception;

class UDPServer extends Controller
{
    private $socket;

    public function __constructor() 
    {
        parent::__construct();
    }

        /** 
     * Starts our udp server and listens for messages.
     * @throws Exception
     * @return bool
     */
    public function Start()
    {
        try {
            $this->CreateSocket();

            $this->BindSocket();

            $this->GetMessages();

            $this->CloseSocket();

            return true;
        } catch(Exception $e) {
            Log::error('Caught Exception: '.$e->getMessage());
            Log::error('Error On Line '.$e->getLine());
            return false;
        }
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
     * Gets all of our messages for us and closes socket if it stops.
     */
    private function GetMessages()
    {
        while(true)
        {   
            //Receive some data
            socket_recvfrom($this->socket, $message, 512, 0, $remote_ip, $remote_port);

            if(!empty($message) && !empty($remote_ip) && !empty($remote_port))
            {
                Log::info($remote_ip.' : '.$remote_port.' -- '.$message);
            }
        }
    }

    /** 
     * Closes our socket server if needed.
     * @return bool
     */
    protected function CloseSocket()
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
    protected function SendMessage($data, $host, $port)
    {
        if(empty($port)) {
            $port = env('UDP_PORT', '3074');
        }

        if(empty($host)) {
            $host = env('UDP_HOST', '127.0.0.1'); 
        }

        if(!socket_sendto($this->socket, $data, strlen($data), 0, $host, $port))
        {
            $errorcode = socket_last_error();
            $errormsg  = socket_strerror($errorcode);
            throw new Exception('Can\'t Send Data to IP: '.$host.', Error Code ['.$errorcode.'] '.$errormsg);
        }
    }
}