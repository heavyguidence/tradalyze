<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\TradeTag;

class TagExistingPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all positions that don't have any tags
        $positions = Position::doesntHave('tags')->get();
        
        foreach ($positions as $position) {
            // Get the user's "Untagged" tag
            $untaggedTag = TradeTag::where('user_id', $position->instrument->user_id)
                ->where('name', 'Untagged')
                ->first();
            
            if ($untaggedTag) {
                $position->tags()->attach($untaggedTag->id);
            }
        }
        
        $this->command->info("Tagged {$positions->count()} positions with 'Untagged'");
    }
}
