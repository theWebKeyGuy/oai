<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Resources\UDPServer;

class SendHelloWorldUDPServerMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'udp:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run This Command To Send A Hello World Message To Your UDP Server For Testing!';

    /** 
     * The udp server script start.
     */
    private $server;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UDPServer $server)
    {
        $this->server = $server;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending Message.');
        
        $this->server->SendMessage('Hello World!', env('UDP_HOST', '127.0.0.1'), env('UDP_PORT', '3074'));

        return 1;
    }
}
