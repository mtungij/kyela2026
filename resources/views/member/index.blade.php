<x-layouts.app :title="__('Members')">

    <div class="w-full px-4 lg:px-12 py-6">
        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
            
            <!-- Top Bar: Search + Add Member Button -->
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                
                <!-- Search Form -->
                <div class="w-full md:w-1/2">
                    <form class="flex items-center" method="GET" action="{{ route('members.index') }}" id="searchForm">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" placeholder="Tafuta kwa jina, simu, au mahali..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>  

                <!-- Add Member Button -->
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button type="button" id="defaultModalButton" data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="flex items-center justify-center text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Sajili Member
                    </button>
                </div>
            </div>

            <!-- Members Grid -->
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">

                @if($members->isEmpty())
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">Hamna member Kwa sasa.</div>
                @endif

                @foreach($members as $member)
                    @php
                        $collection = $member->collections->first();
                        $penaltyBalance = $collection ? $collection->getCurrentPenaltyBalance() : 0;
                    @endphp

                    <div class="w-full flex flex-col gap-2 px-4 py-6 shadow-lg border rounded-lg bg-white dark:bg-gray-900 relative">

                        <!-- Avatar -->
                        <div class="w-full flex justify-center">
                            <img class="w-24 h-24 rounded-full outline outline-2 outline-blue-400 -mt-12" src="{{ asset('images/member.png') }}" alt="Member" />
                        </div>

                        <!-- Member Info -->
                        <ul class="mt-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 divide-y divide-gray-300 dark:divide-gray-600 rounded-lg shadow-sm text-sm">
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Jina</span> <span>{{ $member->name }}</span></li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Namba Ya Simu</span> <span>{{ $member->phone }}</span></li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Makazi</span> <span>{{ $member->address }}</span></li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Biashara</span> <span>{{ $member->business_address }}</span></li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Kiasi cha Kuchangia</span> <span>{{ number_format($member->amount) }}</span></li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Muda Gani</span> 
                                <span>
                                    @if($member->type === 'daily') Kila Siku ({{ $member->number_type }})
                                    @elseif($member->type === 'weekly') Kila Wiki ({{ $member->number_type }})
                                    @elseif($member->type === 'monthly') Kila Mwezi ({{ $member->number_type }})
                                    @endif
                                </span>
                            </li>
                            <li class="flex justify-between py-2 px-3 font-bold text-base"><span>Faini</span>
                                @if($penaltyBalance > 0)
                                    <span class="font-semibold text-orange-600 dark:text-orange-400">{{ number_format($penaltyBalance) }}</span>
                                @else
                                    <span class="text-gray-800 dark:text-gray-400">0</span>
                                @endif
                            </li>
                        </ul>

                        <!-- Actions -->
                        <div class="mt-4 flex justify-center">
                            <button id="dropdown-{{ $member->id }}-button" data-dropdown-toggle="dropdown-{{ $member->id }}" class="inline-flex items-center justify-center text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-600 font-medium rounded-lg text-sm px-4 py-2">
                                Action
                                <svg class="w-4 h-4 ms-1.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdown-{{ $member->id }}" class="hidden z-10 w-44 bg-white dark:bg-gray-800 border rounded-lg shadow-md absolute mt-2">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-300">
                                    <li><a href="#" data-modal-target="editModal-{{ $member->id }}" data-modal-toggle="editModal-{{ $member->id }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Edit Member</a></li>
                                    @if($penaltyBalance > 0)
                                        <li><a href="#" data-modal-target="forgivePenaltyModal-{{ $member->id }}" data-modal-toggle="forgivePenaltyModal-{{ $member->id }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Samehe Faini</a></li>
                                    @endif
                                    <li><a href="#" data-modal-target="deleteModal-{{ $member->id }}" data-modal-toggle="deleteModal-{{ $member->id }}" class="block px-4 py-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-700">Futa Member</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Inaonyesha <span class="font-semibold text-gray-900 dark:text-white">{{ $members->firstItem() }}-{{ $members->lastItem() }}</span> kati ya <span class="font-semibold text-gray-900 dark:text-white">{{ $members->total() }}</span>
                </span>
                <div class="inline-flex items-stretch -space-x-px">
                    {{ $members->appends(['search' => request('search')])->links() }}
                </div>
            </nav>
        </div>
    </div>

    <!-- Sajili Member Modal -->
    <div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4">
        <div class="relative w-full max-w-2xl max-h-screen overflow-y-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6">
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 pb-3 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sajili Member Mpya</h3>
                <button type="button" class="text-gray-400 hover:text-gray-900 dark:hover:text-white" data-modal-toggle="defaultModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('members.store') }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                @csrf
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jina</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Jaza jina la member" required value="{{ old('name') }}">
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Namba Ya Simu</label>
                    <input type="number" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Namba ya simu" value="{{ old('phone') }}">
                </div>
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa makazi</label>
                    <input type="text" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Sehemu pa makazi" value="{{ old('address') }}">
                </div>
                <div>
                    <label for="business_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa Biashara</label>
                    <input type="text" name="business_address" id="business_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Sehemu pa biashara" value="{{ old('business_address') }}">
                </div>
                <div>
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Muda Kulipa</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Chagua Kiasi Cha Kulipa</option>
                        <option value="daily" {{ old('type', 'daily') == 'daily' ? 'selected' : '' }}>Kila Siku (Daily)</option>
                        <option value="weekly" {{ old('type') == 'weekly' ? 'selected' : '' }}>Kila Wiki (Weekly)</option>
                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Kila Mwezi (Monthly)</option>
                    </select>
                </div>
                <div>
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi Cha Kuchangia</label>
                    <input type="number" name="amount" id="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Kiasi Cha Kuchangia" value="{{ old('amount') }}">
                </div>
                <div>
                    <label for="number_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Idadi Ya Malipo</label>
                    <input type="number" name="number_type" id="number_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Idadi ya malipo" value="{{ old('number_type') }}">
                </div>

                <div class="sm:col-span-2 flex justify-end mt-2">
                    <button type="submit" class="text-white bg-cyan-700 hover:bg-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5">Hifadhi</button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
