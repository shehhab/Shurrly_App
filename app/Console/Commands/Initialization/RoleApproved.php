<?php

namespace App\Console\Commands\Initialization;

use App\Models\Advisor;
use Illuminate\Console\Command;


class RoleApproved extends Command
{
    protected $signature = 'app:role-approved';

    protected $description = 'Update user roles based on approval status';

    public function handle()
    {
        $users = Advisor::all();

        foreach ($users as $user) {
            if ($user->approved == 1) {
                $user->assignRole('advisor');
            }
        }

        $this->info('User roles updated successfully.');
    }
}
