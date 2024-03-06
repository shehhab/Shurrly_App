<?php

namespace App\Console\Commands\Initialization;

use Carbon\Carbon;
use App\Models\Seeker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteUnverifiedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $unverifiedAccounts = Seeker::whereNull('email_verified_at')
                                  ->where('created_at', '<=', Carbon::now()->subDays(10))
                                  ->get();

        foreach ($unverifiedAccounts as $account) {
            DB::transaction(function () use ($account) {
                $account->delete();
            });
        }
        $this->info('UnVerified accounts deleted successfully.');
    }
}
