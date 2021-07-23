<?php

namespace App\Resources;

use Illuminate\Http\Request;
use App\Resources\UDPServer;
use Illuminate\Support\Facades\Log;
use \Exception;

class UDPServerTest extends UDPServer
{
    /** 
     * Changes our Create Socket method from protected to public for testing.
     * @throws Exception
     * @return bool
     */
    public function CreateSocket()
    {
        return parent::CreateSocket();
    }

    /** 
     * Changes our Bind Socket method from protected to public for testing.
     * @throws Exception
     * @return bool
     */
    public function BindSocket()
    {
        return parent::BindSocket();
    }

    /** 
     * Changes our class from protected to public for testing.
     * @return bool
     */
    public function CloseSocket()
    {
        return parent::CloseSocket();
    }
}