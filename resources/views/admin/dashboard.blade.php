<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-blue-800 text-white shadow-lg">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-8">
                        <div class="text-xl font-bold">OGC Admin</div>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-300 font-semibold">Dashboard</a>
                            <a href="{{ route('admin.users') }}" class="hover:text-yellow-300">Users</a>
                            <a href="{{ route('admin.students') }}" class="hover:text-yellow-300">Students</a>
                            <a href="{{ route('admin.counselors') }}" class="hover:text-yellow-300">Counselors</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span>Welcome, {{ $admin->user->first_name }}</span>
                        <a href="{{ route('dashboard') }}" class="hover:text-yellow-300">
                            <i class="fas fa-home"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Students</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_students'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-user-md text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Counselors</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_counselors'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-user-shield text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Admins</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_admins'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_users'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.users.create') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Create User</h3>
                            <p class="text-sm text-gray-600">Add new user account</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.users') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <i class="fas fa-users-cog text-green-600 text-2xl"></i>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Manage Users</h3>
                            <p class="text-sm text-gray-600">View all users</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.students') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-purple-600 text-2xl"></i>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900">Student Records</h3>
                            <p class="text-sm text-gray-600">Manage student data</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentUsers as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' :
                                           ($user->role === 'counselor' ? 'bg-purple-100 text-purple-800' :
                                           'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M j, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
