<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TradeTag;
use App\Models\User;

class TradeTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultTags = [
            ['name' => 'Untagged', 'color' => '#9CA3AF'],
            ['name' => 'Breakout', 'color' => '#10B981'],
            ['name' => 'False Breakout/Fakeout', 'color' => '#EF4444'],
            ['name' => 'Range Breakout', 'color' => '#3B82F6'],
            ['name' => 'Earnings Play', 'color' => '#F59E0B'],
            ['name' => 'Pre-Market Gap', 'color' => '#8B5CF6'],
            ['name' => 'Economic Data Release', 'color' => '#EC4899'],
        ];

        // Get all users and create default tags for each
        $users = User::all();

        foreach ($users as $user) {
            foreach ($defaultTags as $tag) {
                TradeTag::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $tag['name'],
                    ],
                    [
                        'color' => $tag['color'],
                    ]
                );
            }
        }
    }
}
