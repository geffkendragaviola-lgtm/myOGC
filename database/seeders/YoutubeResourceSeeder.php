<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YoutubeResourceSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->value('id');

        $videos = [
            [
                'title'          => 'How to Make Stress Your Friend',
                'description'    => 'Psychologist Kelly McGonigal shares how changing the way you think about stress can make you healthier and more resilient.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=RcGyVTAoXEU',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 10,
            ],
            [
                'title'          => 'The Power of Vulnerability',
                'description'    => 'Brené Brown talks about human connection, courage, and what it means to truly belong — a must-watch for anyone feeling alone.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=iCvmsMzlF7o',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 11,
            ],
            [
                'title'          => 'Why We All Need to Practice Emotional First Aid',
                'description'    => 'Guy Winch explains why we should treat emotional injuries the same way we treat physical ones — and how to start.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=F2hc2FLOdhI',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 12,
            ],
            [
                'title'          => 'Overcoming Anxiety | Jonas Kolker',
                'description'    => 'A personal and practical talk on how to face anxiety head-on and build a life that isn\'t controlled by fear.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=WWloIAQpMcQ',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 13,
            ],
            [
                'title'          => 'The Secret to Self Control',
                'description'    => 'Jonathan Bricker explains the science behind willpower and why acceptance — not resistance — is the key to lasting change.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=-moW9jvvMr4',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 14,
            ],
            [
                'title'          => 'How to Stop Feeling Anxious About Anxiety',
                'description'    => 'Tim Box shares a simple but powerful reframe for dealing with anxiety that can help you feel calmer almost immediately.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=arj7oStGLkU',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 15,
            ],
            [
                'title'          => 'Mindfulness and Neural Integration',
                'description'    => 'Dr. Daniel Siegel explains how mindfulness practices can literally reshape your brain and improve emotional well-being.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=LiyaSr5aeho',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 16,
            ],
            [
                'title'          => 'The Surprising Science of Happiness',
                'description'    => 'Dan Gilbert reveals that we can synthesize happiness — and that it\'s just as real as the happiness we stumble upon.',
                'icon'           => 'fab fa-youtube',
                'button_text'    => 'Watch Video',
                'link'           => 'https://www.youtube.com/watch?v=4q1dgn_C0AU',
                'category'       => 'youtube',
                'use_yt_thumbnail' => true,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 17,
            ],
        ];

        foreach ($videos as &$video) {
            $video['user_id']    = $adminId;
            $video['created_at'] = now();
            $video['updated_at'] = now();
        }

        DB::table('resources')->insert($videos);
    }
}
