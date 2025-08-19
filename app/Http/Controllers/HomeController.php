<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $courses = [
            [
                'title' => 'Programming Fundamentals',
                'description' => 'Learn the basics of programming with hands-on projects',
                'duration' => '8 weeks',
                'level' => 'Beginner'
            ],
            [
                'title' => 'Web Development',
                'description' => 'Build modern web applications using Laravel and Vue.js',
                'duration' => '12 weeks',
                'level' => 'Intermediate'
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Create mobile apps for iOS and Android platforms',
                'duration' => '10 weeks',
                'level' => 'Advanced'
            ]
        ];

        return view('welcome', compact('courses'));
    }
}