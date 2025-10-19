<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prophecy;

class VideoUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add video URLs to existing prophecies for testing
        $prophecies = Prophecy::all();
        
        $videoUrls = [
            'https://www.youtube.com/watch?v=ScMzIvxBSi4', // Sample nature video
            'https://www.youtube.com/watch?v=aqz-KE-bpKQ', // Big Buck Bunny
            'https://youtu.be/YE7VzlLtp-4', // Sintel short film
            'https://www.youtube.com/watch?v=LXb3EKWsInQ', // Elephant's Dream
        ];
        
        foreach ($prophecies as $index => $prophecy) {
            $videoUrl = $videoUrls[$index % count($videoUrls)];
            $prophecy->update(['video_url' => $videoUrl]);
            
            $this->command->info("Added video URL to prophecy ID {$prophecy->id}: {$videoUrl}");
        }
        
        $this->command->info('Video URLs added to all prophecies.');
    }
}
