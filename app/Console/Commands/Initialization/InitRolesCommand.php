<?php

namespace App\Console\Commands\Initialization;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class InitRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-roles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roles Initialize';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Role::create([
            'name' => 'admin',
            'guard'=>'web'
        ]);
        Role::create([
            'name' => 'advisor',
            'guard'=>'web'
        ]);

        Role::create([
            'name' => 'seeker',
            'guard'=>'web'
        ]);

        return;
    }
}
