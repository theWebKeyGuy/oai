<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Resources\UDPServerTest AS UDPServer;

class UDPServerStartTest extends TestCase
{
    /** 
     * Checks to see if we are able to create a socket.
     * @return void
     */
    public function test_create_upd_socket()
    {
        $server = new UDPServer();
        $this->assertTrue($server->CreateSocket());
    }

    /** 
     * Checks to see if we are able to properly bind a socket.
     */
    public function test_bind_udp_socket()
    {
        $server = new UDPServer();
        $this->assertTrue($server->CreateSocket());
        $this->assertTrue($server->BindSocket());
    }
}
