<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EbookResourceSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->value('id');

        $ebooks = [
            [
                'title'          => 'Feeling Good: The New Mood Therapy',
                'description'    => 'Dr. David Burns\' classic guide to cognitive behavioral therapy techniques you can use on your own to overcome depression, anxiety, and low self-esteem.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://archive.org/details/feelinggood00burn',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 20,
            ],
            [
                'title'          => 'The Anxiety and Worry Workbook',
                'description'    => 'A practical, step-by-step workbook by Clark and Beck that helps you identify and challenge the thoughts that fuel anxiety.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://www.guilford.com/books/The-Anxiety-and-Worry-Workbook/Clark-Beck/9781606239186',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 21,
            ],
            [
                'title'          => 'The Mindfulness and Acceptance Workbook for Anxiety',
                'description'    => 'Based on Acceptance and Commitment Therapy (ACT), this workbook teaches you to stop fighting anxiety and start living the life you want.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://www.newharbinger.com/9781572245143/the-mindfulness-and-acceptance-workbook-for-anxiety/',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 22,
            ],
            [
                'title'          => 'Self-Compassion: The Proven Power of Being Kind to Yourself',
                'description'    => 'Dr. Kristin Neff explains why self-compassion is more powerful than self-esteem and how to practice it in everyday life.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://self-compassion.org/the-book/',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 23,
            ],
            [
                'title'          => 'The Body Keeps the Score',
                'description'    => 'Bessel van der Kolk explores how trauma reshapes the body and brain, and the paths to recovery through therapy, yoga, and mindfulness.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://www.besselvanderkolk.com/resources/the-body-keeps-the-score',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 24,
            ],
            [
                'title'          => 'Lost Connections: Why You\'re Depressed and How to Find Hope',
                'description'    => 'Johann Hari challenges conventional thinking about depression and anxiety, exploring the real causes and unexpected solutions.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://thelostconnections.com/',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 25,
            ],
            [
                'title'          => 'Maybe You Should Talk to Someone',
                'description'    => 'Therapist Lori Gottlieb takes you inside the therapy room — both as a therapist and as a patient — in this warm, honest, and deeply human book.',
                'icon'           => 'fas fa-book-open',
                'button_text'    => 'Read eBook',
                'link'           => 'https://lorigottlieb.com/books/maybe-you-should-talk-to-someone/',
                'category'       => 'ebooks',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> true,
                'order'          => 26,
            ],
        ];

        foreach ($ebooks as &$ebook) {
            $ebook['user_id']    = $adminId;
            $ebook['created_at'] = now();
            $ebook['updated_at'] = now();
        }

        DB::table('resources')->insert($ebooks);
    }
}
