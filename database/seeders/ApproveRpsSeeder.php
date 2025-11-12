<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rps;

class ApproveRpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update some RPS records to approved status for testing
        Rps::where('status', 'draft')
            ->limit(5)
            ->update([
                'status' => 'approved',
                'approved_by' => 1, // Assuming user ID 1 exists
                'approved_at' => now(),
            ]);
            
        $this->command->info('Updated some RPS records to approved status for testing.');
    }
}
