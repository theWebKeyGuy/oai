<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Resources\UDPServer;
use App\Models\Messages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use \Exception;

class StartUDPServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'udp:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Holds our UDP Server Resource.
     * 
     * @var UDPServer
     */
    private $server;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UDPServer $server)
    {
        parent::__construct();
        $this->server = $server;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $this->server->start();
            $this->info('Server Started!');
            
            $this->GetMessages();

        } catch(Exception $e) {
            $this->error('Caught Exception: '.$e->getMessage());
            $this->error('Error On Line '.$e->getLine());
            return 0;
        }

        return 1;
    }

    /** 
     * Gets all of our messages for us and closes socket if it stops.
     */
    public function GetMessages()
    {
        while(true)
        {   
            $this->info('Waiting On New Message...');

            //Receive some data
            socket_recvfrom($this->server->GetSocket(), $message, 512, 0, $remote_ip, $remote_port);

            if(!empty($message) && !empty($remote_ip) && !empty($remote_port))
            {
                $this->info('Message Recieved!');

                Cache::store('redis')->tags(['message'])
                ->put('udp:message:'.$remote_ip.':'.$remote_port.':'.date('Y-m-dH:i:s'), $message, 1200);

                $message = Messages::create([
                    'ip'      => $remote_ip,
                    'message' => $message
                ]);

                Log::info($remote_ip.' : '.$remote_port.' -- '.$message);

                $this->info('Message Stored!');
            }
        }
    }
}
