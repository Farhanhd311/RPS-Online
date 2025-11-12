<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rps;

class SubmitRpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update some RPS records to submitted status for testing reviewer functionality
        Rps::where('status', 'draft')
            ->orWhere('status', 'approved')
            ->limit(3)
            ->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
            
        $this->command->info('Updated some RPS records to submitted status for reviewer testing.');
    }
}
