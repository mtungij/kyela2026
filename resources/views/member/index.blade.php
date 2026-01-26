<x-layouts.app :title="__('Members')">


 

<section class="w-full mb-6 bg-gray-50 dark:bg-gray-900">
    <div class=" px-4 mx-auto lg:px-12">
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

            <!-- Top Bar -->
            <div class="flex flex-col gap-4 p-4 md:flex-row md:items-center md:justify-between">

                <!-- Search & Filters -->
                <form
                    method="GET"
                    action="{{ route('members.index') }}"
                    id="searchForm"
                    class="flex flex-col w-full gap-4 sm:flex-row sm:flex-wrap md:w-2/3"
                >
                    <!-- Search -->
                    <div class="relative w-full sm:flex-1">
                        <input
                            id="simple-search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name, phone, address"
                            class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg
                                   focus:ring-cyan-500 focus:border-cyan-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                        />
                    </div>

                    <!-- Pay Type -->
                    <div class="w-full sm:w-56">
                        <select
                            name="pay_type"
                            id="pay_type_filter"
                            class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg
                                   focus:ring-cyan-500 focus:border-cyan-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                            <option value="">-- Aina Ya Mchango --</option>
                            <option value="mchango_mdogo" {{ request('pay_type') == 'mchango_mdogo' ? 'selected' : '' }}>
                                Mchango Mdogo (5000)
                            </option>
                            <option value="mchango_mkubwa" {{ request('pay_type') == 'mchango_mkubwa' ? 'selected' : '' }}>
                                Mchango Mkubwa (10000)
                            </option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col gap-2 sm:flex-row sm:space-x-2">
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-cyan-700 hover:bg-cyan-800
                                   focus:ring-4 focus:ring-cyan-300 dark:focus:ring-cyan-800"
                        >
                            Filter
                        </button>

        <button
    type="submit"
    formaction="{{ route('members.download-pdf') }}"
    formtarget="_blank"
    class="px-4 py-2 text-sm font-medium text-white bg-red-800 rounded-lg hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-700 flex items-center space-x-2"
>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
    </svg>

    <span>Download PDF</span>
</button>


                    </div>
                </form>

                <!-- Right Actions -->
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:space-x-2">

                    <!-- Add Member -->
                    <button
                        type="button"
                        data-modal-target="defaultModal"
                        data-modal-toggle="defaultModal"
                        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg
                               bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300
                               dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Sajili Member
                    </button>

                    <!-- Actions Dropdown -->
                </div>
            </div>
        </div>
    </div>
</section>

  


           <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-4">

             
                        @if($members->isEmpty())
                            <div class="col-span-full">
                                <p class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Hamna member Kwa sasa.</p>
                            </div>
                        @endif

                        @foreach($members as $member)

<div
    class="relative flex flex-col gap-4 p-5 mt-3 max-w-sm w-full
           bg-white dark:bg-gray-900
           border border-gray-200 dark:border-gray-700
           rounded-2xl shadow-md hover:shadow-xl
           transition-shadow duration-300">

    <!-- Avatar -->
   <div class="flex justify-center -mt-12">
    @if($member->pay_type == 'mchango_mdogo')
        <img
            class="w-24 h-24 rounded-full bg-white dark:bg-gray-800
                   ring-2 ring-blue-400 ring-offset-4 ring-offset-white dark:ring-offset-gray-900"
            src="{{ asset('images/member.png') }}"
            alt="Member - Mchango Mdogo">
    @elseif($member->pay_type == 'mchango_mkubwa')
        <img
            class="w-24 h-24 rounded-full bg-white dark:bg-gray-800
                   ring-2 ring-blue-400 ring-offset-4 ring-offset-white dark:ring-offset-gray-900"
            src="{{ asset('images/vip.png') }}"
            alt="Member">
    @else
        <img
            class="w-24 h-24 rounded-full bg-white dark:bg-gray-800
                   ring-2 ring-blue-400 ring-offset-4 ring-offset-white dark:ring-offset-gray-900"
            src="{{ asset('images/member.png') }}"
            alt="Member">
    @endif
</div>


    <!-- Member Details -->
    <ul
        class="mt-4 text-sm rounded-xl overflow-hidden
               bg-gray-100 dark:bg-gray-800
               text-gray-800 dark:text-gray-200
               divide-y divide-gray-300 dark:divide-gray-700">

        <li class="flex justify-center px-4 py-2 font-semibold">
            <span class="uppercase">{{ $member->name }}</span>
        </li>

        <li class="flex justify-center px-4 py-2">
            <span>{{ $member->phone }}</span>
        </li>

        <li class="flex justify-between px-4 py-2">
            <span>Makazi</span>
            <span>{{ $member->address }}</span>
        </li>

       <li class="flex justify-between px-4 py-2">
    <span>Alianza Lini</span>
    <span>{{ \Carbon\Carbon::parse($member->start_date)->format('d-m-Y') }}</span>
</li>


        <li class="flex justify-between px-4 py-2">
            <span>Mwisho</span>
            <span>{{ \Carbon\Carbon::parse($member->end_date)->format('d-m-Y') }}</span>
        </li>

        <li class="flex justify-between px-4 py-2">
            <span>Biashara</span>
            <span>{{ $member->business_address }}</span>
        </li>

        <li class="flex justify-between px-4 py-2 font-semibold">
            <span>Kiasi cha Kuchangia</span>
            <span>{{ number_format($member->amount, 0) }}</span>
        </li>

        {{-- <li class="flex justify-between px-4 py-2">
            <span>Muda</span>
            <span>
                @if($member->type === 'daily')
                    Kila Siku ({{ $member->number_type }})
                @elseif($member->type === 'weekly')
                    Kila Wiki ({{ $member->number_type }})
                @elseif($member->type === 'monthly')
                    Kila Mwezi ({{ $member->number_type }})
                @endif
            </span>
        </li> --}}

        <li class="flex justify-between px-4 py-2 font-semibold">
            <span>Faini</span>
            @php
                $collection = $member->collections->first();
                $penaltyBalance = 0;
                $totalPenalty = 0;
                $penaltyPaid = 0;
                
                if ($collection) {
                    // Get current penalty balance (this will recalculate and save)
                    $penaltyBalance = $collection->getCurrentPenaltyBalance();
                    // Refresh to get updated values
                    $collection->refresh();
                    $totalPenalty = $collection->total_penalty;
                    $penaltyPaid = $collection->penalty_paid;
                }
            @endphp

            <div class="text-right">
                @if($penaltyBalance > 0)
                    <div class="text-orange-600 dark:text-orange-400 font-bold">
                        {{ number_format($penaltyBalance, 0) }} TSh
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Jumla: {{ number_format($totalPenalty, 0) }} | 
                        Imelipwa: {{ number_format($penaltyPaid, 0) }}
                    </div>
                @else
                    <span class="text-gray-500 dark:text-gray-400">0</span>
                @endif
            </div>
        </li>
    </ul>
   @if(auth()->user()->isAdmin())
    <!-- Action Button -->
    <button
        id="dropdown-{{ $member->id }}-button"
        data-dropdown-toggle="dropdown-{{ $member->id }}"
        class="mt-3 inline-flex items-center justify-center gap-2
               w-full px-4 py-2.5 text-sm font-medium
               rounded-xl text-white
               bg-cyan-700 hover:bg-cyan-800
               focus:ring-4 focus:ring-cyan-500 focus:outline-none">
        Action
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m19 9-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown -->
    <div
        id="dropdown-{{ $member->id }}"
        class="hidden absolute right-5 bottom-16 z-20 w-44
               bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-xl shadow-lg">

        <ul class="p-2 text-sm">
            <li>
                <a href="#"
                   data-modal-target="editModal-{{ $member->id }}"
                   data-modal-toggle="editModal-{{ $member->id }}"
                   class="block px-3 py-2 rounded-lg
                          hover:bg-gray-100 dark:hover:bg-gray-700">
                    Edit Member
                </a>
            </li>

            @if($penaltyBalance > 0)
            <li>
                <a href="#"
                   data-modal-target="forgivePenaltyModal-{{ $member->id }}"
                   data-modal-toggle="forgivePenaltyModal-{{ $member->id }}"
                   class="block px-3 py-2 rounded-lg
                          hover:bg-gray-100 dark:hover:bg-gray-700">
                    Samehe Faini
                </a>
            </li>
            @endif

            <li>
                <a href="#"
                   data-modal-target="deleteModal-{{ $member->id }}"
                   data-modal-toggle="deleteModal-{{ $member->id }}"
                   class="block px-3 py-2 rounded-lg text-red-600
                          hover:bg-red-50 dark:hover:bg-red-900/20">
                    Futa Member
                </a>
            </li>
        </ul>
    </div>
    @endif
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
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        Aina Ya Mchango
    </label>
    <select
        name="pay_type"
        class="pay-type-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
               block w-full p-2.5"
        required
    >
        <option value="mchango_mdogo" {{ $member->pay_type == 'mchango_mdogo' ? 'selected' : '' }}>
            Mchango Mdogo (5000)
        </option>
        <option value="mchango_mkubwa" {{ $member->pay_type == 'mchango_mkubwa' ? 'selected' : '' }}>
            Mchango Mkubwa (10000)
        </option>
    </select>
</div>

                   <input type="hidden" name="type" value="daily">

                  <div>
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        Kiasi Cha Kuchangia
    </label>
    <input
        type="number"
        name="amount"
        class="amount-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
               focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
        readonly
        value="{{ old('amount') }}"
    >
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
    <label for="pay_type"  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pay Type</label>
    <select name="pay_type" id="pay_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500"  required>
        <option value="">-- Chagua Aina Ya Mchango --</option>
        <option value="mchango_mdogo"
            {{ old('pay_type') == 'mchango_mdogo' ? 'selected' : '' }}>
            Mchango Mdogo (5000 TSh)
        </option>
        <option value="mchango_mkubwa"
            {{ old('pay_type') == 'mchango_mkubwa' ? 'selected' : '' }}>
            Mchango Mkubwa (10,000 TSh)
        </option>
    </select>
</div>
                  <input type="hidden" name="type" value="daily">



<div> 
    <label for="business_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi Cha Kuchangia</label>


                    <input
    type="number"
    name="amount"
    id="amount"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5
           dark:bg-gray-700 dark:border-gray-600 dark:text-white
           @error('amount') border-red-500 @enderror"
    placeholder="Kiasi Cha Kuchangia"
    value="{{ old('amount') }}"
    readonly
>
   
</div>


                    <div>
                        <label for="number_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Idadi Ya Malipo</label>
                        <input type="number" name="number_type" id="number_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('number_type') border-red-500 @enderror" placeholder="Idadi jumla ya siku za malipo"  value="{{ old('number_type') }}">
                        @error('number_type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                        <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anaanza kutoa Lini</label>
                        <input type="date" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cyan-500 dark:focus:border-cyan-500 @error('start_date') border-red-500 @enderror" placeholder="Anza kutoa tarehe"  value="{{ old('start_date') }}">
                        @error('start_date')
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payTypeSelect = document.getElementById('pay_type');
    const amountInput = document.getElementById('amount');

    function updateAmount() {
        if (payTypeSelect.value === 'mchango_mdogo') {
            amountInput.value = 5000;
        } else if (payTypeSelect.value === 'mchango_mkubwa') {
            amountInput.value = 10000;
        } else {
            amountInput.value = '';
        }
    }

    // On change
    payTypeSelect.addEventListener('change', updateAmount);

    // On page load (for old values / validation errors)
    updateAmount();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function setAmount(selectEl) {
        const form = selectEl.closest('form');
        const amountInput = form.querySelector('.amount-input');

        if (!amountInput) return;

        if (selectEl.value === 'mchango_mdogo') {
            amountInput.value = 5000;
        } else if (selectEl.value === 'mchango_mkubwa') {
            amountInput.value = 10000;
        } else {
            amountInput.value = '';
        }
    }

    document.querySelectorAll('.pay-type-select').forEach(select => {
        // on change
        select.addEventListener('change', function () {
            setAmount(this);
        });

        // on load (edit modal default)
        setAmount(select);
    });

});
</script>




</x-layouts.app>