<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class executeFakePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atomic:postFake {url=https://atomic.incfile.com/fakepost} {body?} {attemps=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute a fake post to atomic!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $url = $this->argument('url');
        $body = $this->argument('body') ?? "{}";
        $attemps = $this->argument('attemps');
        $sleepBetweenAttemps = 5; // seconds


        //print("url:".$url);
        //print("body:".$body);

        $opts = array(
            'http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $body
            )
        );

        $response = null;
        $i = 1;
        while (!$response && $i < $attemps) {
            $context = stream_context_create($opts);
            $response = file_get_contents($url, false, $context);
            sleep($sleepBetweenAttemps);
            $i++;
        }

        if (!$response) {
            $this->error("Max num of attemps has reached, Unable to get a response from: " . $url);
        } else {
            $this - info('Response:' . $response);
        }
    }
}
