<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateMaxPics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:max_pics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update max_pics to 1 for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('users')->update(['max_pics' => 1]);
        $this->info('Updated max_pics for all users.');
        return 0;
        
    }
}
