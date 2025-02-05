<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Todo') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        @auth
            <x-app-layout>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-2xl font-bold text-gray-800">Task Manager</h2>
                                    <button 
                                        onclick="openModal()"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                                    >
                                        Add Task
                                    </button>
                                </div>

                                <!-- Filter -->
                                <div class="mb-6">
                                    @php
                                        $currentFilter = $filter ?? 'all';
                                        $filterOptions = [
                                            'all' => 'All Tasks',
                                            'active' => 'Active',
                                            'completed' => 'Completed',
                                            'work' => 'Work',
                                            'personal' => 'Personal',
                                            'groceries' => 'Groceries',
                                            'health' => 'Health',
                                            'other' => 'Other'
                                        ];
                                    @endphp
                                    <select 
                                        onchange="window.location.href='{{ route('tasks.index') }}?filter=' + this.value"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        @foreach($filterOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $currentFilter === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tasks List -->
                                <div class="space-y-4">
                                    @forelse($tasks ?? [] as $task)
                                        <div class="flex items-center justify-between p-4 border rounded-lg {{ $task->completed ? 'bg-gray-50' : 'bg-white' }}">
                                            <div class="flex items-center space-x-4">
                                                <input 
                                                    type="checkbox" 
                                                    {{ $task->completed ? 'checked' : '' }}
                                                    onchange="toggleTask({{ $task->id }}, this.checked)"
                                                    class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                                >
                                                <div>
                                                    <p class="font-medium {{ $task->completed ? 'line-through text-gray-500' : '' }}">
                                                        {{ $task->title }}
                                                    </p>
                                                    <div class="flex space-x-2 text-sm text-gray-500">
                                                        <span class="capitalize">{{ $task->category }}</span>
                                                        <span class="@if($task->priority === 'high') text-red-500 
                                                                   @elseif($task->priority === 'medium') text-yellow-500 
                                                                   @else text-green-500 @endif capitalize">
                                                            {{ $task->priority }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                @if($task->due_date)
                                                    <span class="text-sm text-gray-500">
                                                        {{ $task->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-center py-4">No tasks yet. Create one above!</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Task Modal -->
                @include('tasks.partials.modal')

                @push('scripts')
                <script>
                    function openModal() {
                        document.getElementById('taskModal').classList.remove('hidden');
                    }

                    function closeModal() {
                        document.getElementById('taskModal').classList.add('hidden');
                    }

                    function toggleTask(taskId, completed) {
                        fetch(`/tasks/${taskId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ completed })
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                </script>
                @endpush
            </x-app-layout>
        @else
            <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                </div>

                <div class="max-w-7xl mx-auto p-6 lg:p-8">
                    <div class="flex justify-center">
                        <h1 class="text-6xl font-bold text-gray-900 dark:text-white">Todo List</h1>
                    </div>

                    <div class="mt-16">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                            <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                                <div>
                                <a href="{{ route('login') }}">
                                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Manage Your Tasks</h2>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                            Create, organize, and track your tasks efficiently. Stay productive and never miss a deadline with our intuitive todo list application.
                                        </p>
                                    </a>
                                </div>
                            </div>


                            <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                                <div>
                                    <a href="{{ route('register') }}">
                                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Get Started</h2>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                            Sign up now to start organizing your tasks. Already have an account? Log in to access your tasks and continue staying productive.
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </body>
</html> 