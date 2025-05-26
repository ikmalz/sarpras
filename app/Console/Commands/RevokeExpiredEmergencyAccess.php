<?php

namespace App\Console\Commands;

use App\Models\EmergencyRequest;
use Illuminate\Console\Command;

class RevokeExpiredEmergencyAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emergency:revoke-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cabut akses darurat yang sudah kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredRequests = EmergencyRequest::where('expires_at', '<', now())
            ->where('is_approved', true)
            ->get();

        foreach ($expiredRequests as $request) {
            $user = $request->user;

            if ($user->hasPermissionTo('emergency_borrow')) {
                $user->revokePermissionTo('emergency_borrow');
                $this->info("Permission dicabut untuk user ID: {$user->id}");
            }
        }
    }
}
