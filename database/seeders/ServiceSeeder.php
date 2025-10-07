<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'UPCOMING SEMINAR ON MENTAL HEALTH AWARENESS',
                'description' => 'Attend our seminar on mental health awareness to gain helpful tips and resources for managing well-being during your studies.',
                'image_url' => 'https://images.unsplash.com/photo-1584697964358-3e14ca57658b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'route_name' => 'mhc', // Route for Mental Health Corner
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'MENTAL HEALTH CORNER',
                'description' => 'Visit our Health Corner for tips, tools, and guidance on maintaining your mental and emotional well-being throughout your academic journey.',
                'image_url' => 'https://images.unsplash.com/photo-1596363505724-6d24f19ad5a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'route_name' => 'mhc', // Route for Mental Health Corner
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'BOOK AN APPOINTMENT',
                'description' => 'Need someone to talk to? Book a private session with one of our guidance counselors and take a step toward a healthier, balanced you.',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'route_name' => 'bap', // Route for Book Appointment
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('services')->insert($services);
    }
}
