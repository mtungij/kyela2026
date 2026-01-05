<x-layouts.app :title="__('Members')">



    <div class="w-full px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center" method="GET" action="{{ route('members.index') }}" id="searchForm">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" placeholder="Tafuta kwa jina, simu, au mahali..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>  
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button type="button" id="defaultModalButton" data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="flex items-center justify-center text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Sajili Member
                    </button>
                   
                </div>
            </div>
           <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-4">

             
                        @if($members->isEmpty())
                            <div class="col-span-full">
                                <p class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Hamna member Kwa sasa.</p>
                            </div>
                        @endif

                        @foreach($members as $member)


        <div class="flex flex-col gap-2 p-4 shadow-lg border border-gray-300 rounded-lg bg-white dark:bg-gray-900">
            <div class="w-full flex justify-center items-center -mt-8">
                <img class="w-24 h-24 rounded-full outline outline-offset-2 outline-1 outline-blue-400" src="{{ asset('images/member.png') }}" alt="Member" />
            </div>

            <div class="w-full text-center flex flex-col gap-4 mt-2">
                  <ul class="mt-5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 divide-y divide-gray-300 dark:divide-gray-600 rounded-lg shadow-sm text-sm">
                        <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span >Jina</span>
                           {{ $member->name }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Namba Ya Simu</span>
                            <span class="capitalize">{{ $member->phone }}</span>
                        </li>
                         <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Makazi</span>
                            <span>{{ $member->address}}</span>
                        </li>
                        <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Biashara</span>
                            <span>{{ $member->business_address }}</span>
                        </li>

                          <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Kiasi cha Kuchangia</span>
                            <span>{{ number_format($member->amount, 0) }}</span>
                        </li>

                         <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
    <span>Muda Gani</span>
    <span>
        @if($member->type === 'daily')
            Kila Siku ({{ $member->number_type }})
        @elseif($member->type === 'weekly')
            Kila Wiki ({{ $member->number_type }})
        @elseif($member->type === 'monthly')
            Kila Mwezi ({{ $member->number_type }})
        @endif
    </span>
</li>


       <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Faini</span>
                            @php
                                    $collection = $member->collections->first();
                                    $penaltyBalance = $collection ? $collection->getCurrentPenaltyBalance() : 0;
                                @endphp
                                @if($penaltyBalance > 0)
                                    <span class="font-semibold text-orange-600 dark:text-orange-400">{{ number_format($penaltyBalance) }}</span>
                                @else
                                    <span class="text-gray-800 dark:text-gray-400">0</span>
                                @endif
                        </li>


                       
                    </ul>



                    


    <!-- Action Button -->
    <button id="dropdown-{{ $member->id }}-button" 
            data-dropdown-toggle="dropdown-{{ $member->id }}" 
            class="inline-flex items-center justify-center text-white bg-cyan-700 box-border border border-transparent hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-600 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" 
            type="button">
        Action 
        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div id="dropdown-{{ $member->id }}" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdown-{{ $member->id }}-button">
            <li>
                <a href="#" 
                   data-modal-target="editModal-{{ $member->id }}" 
                   data-modal-toggle="editModal-{{ $member->id }}" 
                   class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                   Edit Member
                </a>
            </li>
            @if($penaltyBalance > 0)
            <li>
                <a href="#" 
                   data-modal-target="forgivePenaltyModal-{{ $member->id }}" 
                   data-modal-toggle="forgivePenaltyModal-{{ $member->id }}" 
                   class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                   Samehe Faini
                </a>
            </li>
            @endif
            <li>
                <a href="#" 
                   data-modal-target="deleteModal-{{ $member->id }}" 
                   data-modal-toggle="deleteModal-{{ $member->id }}" 
                   class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded text-red-700">
                   Futa Member
                </a>
            </li>
        </ul>
    </div>


            </div>

        </div>
                      
                        @endforeach
            </div>

<!-- Edit Modals for each member -->
@foreach($members as $member)
<div id="editModal-{{ $member->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full">
    <div class="relative p-4 w-full max-w-2xl h-auto max-h-[90vh]">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Member</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="editModal-{{ $member->id }}">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Funga</span>
                </button>
            </div>
            <form action="{{ route('members.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="edit_name_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jina</label>
                        <input type="text" name="name" id="edit_name_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->name }}" required>
                    </div>
                    <div>
                        <label for="edit_phone_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Namba Ya Simu</label>
                        <input type="number" name="phone" id="edit_phone_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->phone }}" required>
                    </div>
                    <div>
                        <label for="edit_address_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa makazi</label>
                        <input type="text" name="address" id="edit_address_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->address }}">
                    </div>
                    <div>
                        <label for="edit_business_address_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa Biashara</label>
                        <input type="text" name="business_address" id="edit_business_address_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->business_address }}">
                    </div>
                    <div>
                        <label for="edit_type_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Muda Kulipa</label>
                        <select id="edit_type_{{ $member->id }}" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" required>
                            <option value="daily" {{ $member->type == 'daily' ? 'selected' : '' }}>Kila Siku (Daily)</option>
                            <option value="weekly" {{ $member->type == 'weekly' ? 'selected' : '' }}>Kila Wiki (Weekly)</option>
                            <option value="monthly" {{ $member->type == 'monthly' ? 'selected' : '' }}>Kila Mwezi (Monthly)</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_amount_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi Cha Kuchangia</label>
                        <input type="number" name="amount" id="edit_amount_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->amount }}" required>
                    </div>
                    <div>
                        <label for="edit_number_type_{{ $member->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Idadi Ya Malipo</label>
                        <input type="number" name="number_type" id="edit_number_type_{{ $member->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500" value="{{ $member->number_type }}" required>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Badilisha
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Confirmation Modals -->
@foreach($members as $member)
<div id="deleteModal-{{ $member->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-4 md:p-6">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white rounded-lg text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-hide="deleteModal-{{ $member->id }}">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
                <span class="sr-only">Funga</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 dark:text-gray-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-6 text-lg text-gray-500 dark:text-gray-400">Je, una uhakika unataka kufuta <span class="font-semibold">{{ $member->name }}</span>?</h3>
                <div class="flex items-center space-x-4 justify-center">
                    <form action="{{ route('members.destroy', $member->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                            Ndio, Futa
                        </button>
                    </form>
                    <button data-modal-hide="deleteModal-{{ $member->id }}" type="button" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 border border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Hapana, Ghairi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgive Penalty Modal -->
<div id="forgivePenaltyModal-{{ $member->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-4 md:p-6">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white rounded-lg text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-hide="forgivePenaltyModal-{{ $member->id }}">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
                <span class="sr-only">Funga</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-orange-400 dark:text-orange-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-4 text-lg text-gray-500 dark:text-gray-400">Samehe Faini kwa <span class="font-semibold">{{ $member->name }}</span>?</h3>
                @if($penaltyBalance > 0)
                <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                    Faini ya sasa: <span class="font-bold text-orange-600 dark:text-orange-400">{{ number_format($penaltyBalance) }} TSh</span>
                </p>
                @endif
                <div class="flex items-center space-x-4 justify-center">
                    <form action="{{ route('members.forgive-penalty', $member->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                            Ndio, Samehe
                        </button>
                    </form>
                    <button data-modal-hide="forgivePenaltyModal-{{ $member->id }}" type="button" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 border border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Hapana, Ghairi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Inaonyesha
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $members->firstItem() }}-{{ $members->lastItem() }}</span>
                    kati ya
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $members->total() }}</span>
                </span>
                
                <div class="inline-flex items-stretch -space-x-px">
                    {{ $members->appends(['search' => request('search')])->links() }}
                </div>
            </nav>
        </div>
    </div>

    <div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full">
    <div class="relative p-4 w-full max-w-2xl h-auto max-h-[90vh]">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 max-h-[90vh] overflow-y-auto">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sajili Member Mpya
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Funga</span>
                </button>
            </div>
            <!-- Modal body -->
            
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Kuna makosa!</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jina</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('name') border-red-500 @enderror" placeholder="jaza jina la member" required="" value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Namba Ya Simu</label>
                        <input type="number" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('phone') border-red-500 @enderror" placeholder="Namba ya simu"  value="{{ old('phone') }}">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa makazi</label>
                        <input type="text" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('address') border-red-500 @enderror" placeholder="Sehemu pa makazi"  value="{{ old('address') }}">
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="business_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sehemu pa Biashara</label>
                        <input type="text" name="business_address" id="business_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('business_address') border-red-500 @enderror" placeholder="Sehemu pa biashara"  value="{{ old('business_address') }}">
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Muda Kulipa</label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('type') border-red-500 @enderror">
                            <option value="">Chagua Kiasi Cha Kulipa</option>
                            <option value="daily" {{ old('type', 'daily') == 'daily' ? 'selected' : '' }}>Kila Siku (Daily)</option>
                            <option value="weekly" {{ old('type') == 'weekly' ? 'selected' : '' }}>Kila Wiki (Weekly)</option>
                            <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Kila Mwezi (Monthly)</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                     <div>
                        <label for="business_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi Cha Kuchangia</label>
                        <input type="number" name="amount" id="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('amount') border-red-500 @enderror" placeholder="Kiasi Cha Kuchangia"  value="{{ old('amount') }}">
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="number_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Idadi Ya Malipo</label>
                        <input type="number" name="number_type" id="number_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('number_type') border-red-500 @enderror" placeholder="Idadi ya malipo"  value="{{ old('number_type') }}">
                        @error('number_type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <button type="submit" class="text-white inline-flex items-center bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Hifadhi
                </button>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('defaultModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('simple-search');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(function() {
                const searchValue = searchInput.value;
                const url = new URL(window.location.href);
                
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }
                
                // Reload page with new search parameter
                window.location.href = url.toString();
            }, 500); // Wait 500ms after user stops typing
        });
    });
</script>




</x-layouts.app>