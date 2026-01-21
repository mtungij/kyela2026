<x-layouts.app :title="__('users Management')">
    <div class="w-full max-w-full px-2 sm:px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between p-4">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                    Wasimamizi Wa Users
                </h1>

                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center justify-center text-white bg-cyan-700 hover:bg-cyan-800
                   focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-4 py-2
                   dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Sajili User Mpya
                </a>
            </div>

            <!-- Alerts -->
            @if ($message = session('success'))
                <div class="mx-4 mb-4 p-3 text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-400">
                    <strong>Mafanikio!</strong> {{ $message }}
                </div>
            @endif

            @if ($message = session('error'))
                <div class="mx-4 mb-4 p-3 text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-400">
                    <strong>Kosa!</strong> {{ $message }}
                </div>
            @endif

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Jina</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3 hidden md:table-cell">Tarehe</th>
                            <th class="px-4 py-3">Vitendo</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">{{ $user->id }}</td>

                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 hidden sm:table-cell">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $user->isAdmin()
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $user->isAdmin() ? 'Admin' : 'Cashier' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 hidden md:table-cell">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
           class="bg-cyan-600 hover:bg-cyan-700 text-white font-medium px-3 py-1 rounded-md text-sm text-center">
            Badilisha
        </a>


                                        @if($user->id !== auth()->id())
                                            <button
                onclick="confirm('Je, una hakika?') &&
                fetch('{{ route('users.destroy', $user) }}', {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                }).then(() => location.reload())"
                class="bg-red-600 hover:bg-red-700 text-white font-medium px-3 py-1 rounded-md text-sm text-center">
                Futa
            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center">
                                    Hamna users kwa sasa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Inaonyesha {{ $users->firstItem() }}–{{ $users->lastItem() }}
                    kati ya {{ $users->total() }}
                </span>

                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-layouts.app>
