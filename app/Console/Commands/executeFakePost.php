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
    protected $signature = 'atomic:postFake {url=https://atomic.incfile.com/fakepost} {body?}';

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
    public function handle() {

        $url = $this->argument('url');
        $body = $this->argument('body') ?? "{}";

        //print("url:".$url);
        //print("body:".$body);

        $opts = array('http' => 
            array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $body
            )
        );
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        $this-info('Response:' . $response);
    }
}
