<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create admin account';

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
        $param = [
            'email' => 'technology@newdreamservices.com',
            'name' => 'admin',
            'password' => '123456',
            'phone' => '13719171146',
        ];
        $m = Account::addAccount($param);
        dd($m);
    }
}
