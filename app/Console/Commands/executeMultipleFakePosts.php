<?php

namespace App\Console\Commands;

use App\Utils\MultipleRequests;
use Illuminate\Console\Command;

class executeMultipleFakePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atomic:multiplePostFake {url=https://atomic.incfile.com/fakepost} {body?} {attemps=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute multiple fake posts to atomic!';

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

        $urlList = array();

        for ($i = 0; $i < 20; ++$i) {
            $urlList[] = $url;
        }

        print_r($urlList);

        $multipleRequest = new MultipleRequests(15);
        $multipleRequest->setRequestList($urlList);
        $results  = $multipleRequest->processRequests();

        print_r($results);
    }
}
