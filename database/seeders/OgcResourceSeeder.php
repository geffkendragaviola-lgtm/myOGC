<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OgcResourceSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->value('id');

        $ogcResources = [
            [
                'title'          => 'Understanding Your Emotions',
                'description'    => 'A simple guide from the OGC team to help you name, understand, and work through difficult emotions in healthy ways.',
                'icon'           => 'fas fa-heart',
                'button_text'    => 'Read Guide',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 30,
            ],
            [
                'title'          => 'Stress Management for Students',
                'description'    => 'Practical tips and techniques curated by our counselors to help you manage academic pressure, deadlines, and burnout.',
                'icon'           => 'fas fa-brain',
                'button_text'    => 'Read Guide',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 31,
            ],
            [
                'title'          => 'How to Ask for Help',
                'description'    => 'Reaching out can feel hard. This guide walks you through how to talk to a counselor, a friend, or a family member when you\'re struggling.',
                'icon'           => 'fas fa-hands-helping',
                'button_text'    => 'Read Guide',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 32,
            ],
            [
                'title'          => 'Sleep & Mental Health',
                'description'    => 'Learn why sleep matters for your mental health and get practical advice on building better sleep habits as a student.',
                'icon'           => 'fas fa-moon',
                'button_text'    => 'Read Guide',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 33,
            ],
            [
                'title'          => 'Building Healthy Relationships',
                'description'    => 'A guide on communication, boundaries, and what healthy friendships and relationships look like — written for MSU-IIT students.',
                'icon'           => 'fas fa-user-friends',
                'button_text'    => 'Read Guide',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 34,
            ],
            [
                'title'          => 'Crisis Support & Where to Get Help',
                'description'    => 'If you or someone you know is in crisis, this resource lists local and national mental health hotlines, emergency contacts, and OGC walk-in hours.',
                'icon'           => 'fas fa-phone-alt',
                'button_text'    => 'View Resource',
                'link'           => 'https://www.msuiit.edu.ph/offices/ogc',
                'category'       => 'ogc',
                'use_yt_thumbnail' => false,
                'is_active'      => true,
                'show_disclaimer'=> false,
                'order'          => 35,
            ],
        ];

        foreach ($ogcResources as &$resource) {
            $resource['user_id']    = $adminId;
            $resource['created_at'] = now();
            $resource['updated_at'] = now();
        }

        DB::table('resources')->insert($ogcResources);
    }
}
