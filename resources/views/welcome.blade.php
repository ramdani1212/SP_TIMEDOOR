@extends('layouts.app')

@section('title', 'Timedoor Academy - Home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="header-card max-w-md mx-auto p-6 text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Timedoor Academy</h1>
        <p class="text-sm text-gray-600">Learning Technology Excellence Since 2010</p>
    </div>

    <nav class="bg-white rounded-lg shadow-md p-4 mb-6">
        <ul class="flex justify-center space-x-8">
            <li><a href="#" class="text-gray-700 hover:text-green-600 font-medium">Home</a></li>
            <li><a href="#" class="text-gray-700 hover:text-green-600 font-medium">Courses</a></li>
            <li><a href="#" class="text-gray-700 hover:text-green-600 font-medium">About</a></li>
            <li><a href="#" class="text-gray-700 hover:text-green-600 font-medium">Contact</a></li>
        </ul>
    </nav>

    @if(isset($courses) && count($courses) > 0)
    <div class="max-w-6xl mx-auto">
        <h2 class="text-center text-2xl font-bold text-white mb-8">Our Courses</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="course-card bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-800">{{ $course['title'] }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $course['description'] }}</p>
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>Duration: {{ $course['duration'] }}</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $course['level'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection