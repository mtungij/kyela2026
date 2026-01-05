<x-layouts.app :title="__('Wasimamizi Wa Users')">
    <div class="w-full px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Wasimamizi Wa Users</h1>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a href="{{ route('users.create') }}" class="flex items-center justify-center text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Sajili User Mpya
                    </a>
                </div>
            </div>

            @if ($message = session('success'))
                <div class="p-4 m-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Mafanikio!</span> {{ $message }}
                </div>
            @endif

            @if ($message = session('error'))
                <div class="p-4 m-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Kosa!</span> {{ $message }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">ID</th>
                            <th scope="col" class="px-4 py-3">Jina</th>
                            <th scope="col" class="px-4 py-3">Email</th>
                            <th scope="col" class="px-4 py-3">Jukumu (Role)</th>
                            <th scope="col" class="px-4 py-3">Tarehe Ya Kuundwa</th>
                            <th scope="col" class="px-4 py-3">Vitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($user->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Cashier
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="font-medium text-cyan-600 dark:text-cyan-500 hover:underline">
                                            Badilisha
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" onclick="confirm('Je, una hakika kuwa unataka kufuta user huyu?') && fetch('{{ route('users.destroy', $user) }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => location.reload())" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                Futa
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Hamna users kwa sasa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Inaonyesha
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $users->firstItem() }}-{{ $users->lastItem() }}</span>
                    kati ya
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $users->total() }}</span>
                </span>
                
                <div class="inline-flex items-stretch -space-x-px">
                    {{ $users->links() }}
                </div>
            </nav>
        </div>
    </div>
</x-layouts.app>
