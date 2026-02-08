<x-layouts.app :title="__('Malipo - ' . $member->name)">

<div class="w-full px-4 sm:px-6 lg:px-8 py-6">
    @if(session('success'))
    <div class="bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 dark:bg-teal-800/10 dark:border-teal-900 dark:text-teal-500 mb-4" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-500">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
            </div>
            <div class="ms-3">
                <h3 class="text-gray-800 font-semibold dark:text-white">Success</h3>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Bar -->
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-cyan-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        Dashibodi Ya Malipo
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Dashboard Cards -->
    <div class="w-full">
        <div class="md:flex md:justify-between md:items-start md:space-x-4">
            
            <!-- Customer Card -->
            <div class="w-full md:w-1/4 mb-4 md:mb-0">
                <div class="bg-white p-4 border-t-4 border-cyan-500 rounded-lg shadow-md dark:bg-gray-800 dark:border-cyan-600">
                    <div class="image overflow-hidden mb-4 text-center">
                        <div class="w-32 h-32 mx-auto rounded-full bg-cyan-100 dark:bg-cyan-900 flex items-center justify-center border-4 border-cyan-400">
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
                    </div>
                    <h1 class="text-cyan-600 dark:text-cyan-400 font-bold text-xl text-center uppercase whitespace-nowrap overflow-hidden truncate">
                        {{ strtoupper($member->name) }}
                    </h1>
                    <div class="flex justify-center">
                        <a href="{{ route('send_sms', ['member' => $member->id]) }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all dark:bg-green-600 dark:hover:bg-green-700">
                            Tuma sms ya malipo
                        </a>
                    </div>
                      
                    <h2 class="text-sm text-cyan-500 dark:text-cyan-400 text-center font-semibold">({{ $member->address }})</h2>
                    <p class="text-center mt-2 text-gray-800 dark:text-gray-200 font-medium">{{ $member->phone }}</p>



                    @if($collection)
                    <ul class="mt-5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 divide-y divide-gray-300 dark:divide-gray-600 rounded-lg shadow-sm text-sm">
                        <li class="flex items-center justify-between py-2 px-3">
                            <span class="font-bold text-base">Hali</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $collection->status === 'completed' ? 'bg-green-500 text-white' : ($collection->status === 'partial' ? 'bg-yellow-500 text-white' : 'bg-blue-500 text-white') }}">
                                {{ $collection->status === 'completed' ? 'Kamaliza' : ($collection->status === 'partial' ? 'Inaendelea' : 'Hajaanza') }}
                            </span>
                        </li>
                      
                         <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Idadi Ya Siku</span>
                            <span>{{ $member->number_type}}</span>
                        </li>
                        <li class="flex items-center justify-between py-2 px-3 font-bold text-base">
                            <span>Kiasi kwa siku</span>
                            <span>{{ number_format($member->amount, 0) }}</span>
                        </li>
                       
                    </ul>
                    @endif
                </div>
            </div>



            <!-- Loan Information Table -->
            <div class="w-full  mb-4 md:mb-0">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md overflow-auto">
                    <h2 class="text-lg font-bold text-cyan-600 dark:text-cyan-400 mb-3">Taarifa Za Malipo</h2>
                    
                    @if($collection)
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                        <thead class="bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 font-semibold">
                            <tr>
                                <th class="px-4 py-2 border-b dark:border-gray-700">Kiasi Jumla</th>
                                <th class="px-4 py-2 border-b dark:border-gray-700">Kilicholipwa</th>
                                <th class="px-4 py-2 border-b dark:border-gray-700">Deni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 border-b dark:border-gray-700 font-semibold">{{ number_format($collection->total_amount, 0) }}</td>
                                <td class="px-4 py-2 border-b dark:border-gray-700 text-green-600 dark:text-green-400 font-semibold">{{ number_format($collection->amount_paid, 0) }}</td>
                                <td class="px-4 py-2 border-b dark:border-gray-700 text-red-600 dark:text-red-400 font-semibold">{{ number_format($collection->balance, 0) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($collection->penalty_balance > 0)
                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <h3 class="text-sm font-bold text-red-800 dark:text-red-400 mb-2">⚠️ Faini ya Kuchelewa</h3>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div>
                                <p class="text-red-600 dark:text-red-400">Jumla ya Faini:</p>
                                <p class="font-bold text-red-800 dark:text-red-300">{{ number_format($collection->total_penalty, 0) }} TSh</p>
                            </div>
                            <div>
                                <p class="text-red-600 dark:text-red-400">Amelipa:</p>
                                <p class="font-bold text-green-600 dark:text-green-400">{{ number_format($collection->penalty_paid, 0) }} TSh</p>
                            </div>
                            <div>
                                <p class="text-red-600 dark:text-red-400">Baki ya Faini:</p>
                                <p class="font-bold text-red-800 dark:text-red-300">{{ number_format($collection->penalty_balance, 0) }} TSh</p>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-red-700 dark:text-red-400">
                            * Faini lazima ilipwe kwanza kabla ya malipo ya mchango
                        </p>
                    </div>
                    @endif

                    <div class="mt-4 text-center">
                        @if($collection->penalty_balance > 0)
                        <button type="button" onclick="openPaymentModal('penalty')" class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all dark:bg-orange-600 dark:hover:bg-orange-700">
                            ⚠️ Lipa Faini
                        </button>
                        @elseif($collection->balance > 0)
                        <button type="button" onclick="openPaymentModal('regular')" class="inline-flex items-center px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all dark:bg-cyan-600 dark:hover:bg-cyan-700">
                            💰 Fanya Malipo
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">Hakuna mchango ulioandikishwa</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            
        </div>
    </div>

    <!-- Search Customer Section -->


 <div class="w-full  md:flex-row items-center ">
        <!-- Search Dropdown -->
        
        <div class="w-full mt-3 mb-3">
            <label for="memberSelect" class="block text-sm font-medium mb-1 dark:text-gray-300">* Search Customer:</label>
            <select id="memberSelect" required name="member_id"
                class="py-2 px-3 block w-full bg-cyan-600 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:placeholder-gray-500 select2">
                <option value="">Tafuta Member</option>
                                           @foreach($members as $m)

                     <option  value="{{ $m->id }}" {{ $m->id == $member->id ? 'selected' : '' }}>
                                    {{ mb_strtoupper($m->name , 'UTF-8') }} - {{ $m->phone }}
                                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>






    <!-- Payment Statement Table -->
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Historia ya Malipo</h3>

@if($allPayments->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
        <thead class="bg-cyan-600 dark:bg-cyan-700">
            <tr>
                <th class="py-3 px-6 text-start border-r border-gray-300 dark:border-gray-600">Tarehe</th>
                <th class="py-3 px-6 text-start">Maelezo</th>
                <th class="py-3 px-6 text-start border-r border-gray-300 dark:border-gray-600">Kiasi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($allPayments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                    {{ \Carbon\Carbon::parse($payment['date'])->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-sm font-semibold {{ $payment['type'] === 'Faini' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                    {{ $payment['user'] }}/{{ $payment['type'] }}
                </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold border-r border-gray-200 dark:border-gray-700">
                    {{ number_format($payment['amount'], 0) }} 
                </td>
               
           
              
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p class="text-gray-500 dark:text-gray-400">Hakuna malipo yaliyorekodiwa bado</p>
@endif

</div>

<!-- Payment Modal -->
<div id="payment-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white">
                    Fanya Malipo
                </h3>
                <button type="button" onclick="closePaymentModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('collections.storePayment') }}" class="p-4 md:p-5">
                @csrf
                <input type="hidden" name="member_id" value="{{ $member->id }}">
                <input type="hidden" name="collection_id" value="{{ $collection->id ?? '' }}">
                <input type="hidden" name="payment_type" id="payment_type" value="regular">
                
                <!-- Penalty Info Alert -->
                <div id="penalty-info" class="hidden mb-4 p-4 bg-orange-50 border border-orange-200 rounded-lg dark:bg-orange-900/20 dark:border-orange-800">
                    <h4 class="text-sm font-bold text-orange-800 dark:text-orange-400 mb-2">⚠️ Malipo ya Faini</h4>
                    <div class="text-xs text-orange-700 dark:text-orange-300">
                        <p class="mb-1"><strong>Baki ya Faini:</strong> TZS {{ number_format($collection->penalty_balance ?? 0, 0) }}</p>
                        <p class="text-xs italic">Faini lazima ilipwe kwanza kabla ya malipo ya mchango</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi (TSh)</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" class="bg-gray-50 border border-cyan-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-600 dark:border-cyan-500 dark:placeholder-gray-400 dark:text-white" placeholder="Weka kiasi" required>
                        @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="payment_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarehe ya Malipo</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-cyan-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-600 dark:border-cyan-500 dark:placeholder-gray-400 dark:text-white" required>
                        @error('payment_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Maelezo (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="bg-gray-50 border border-cyan-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-600 dark:border-cyan-500 dark:placeholder-gray-400 dark:text-white" placeholder="Weka maelezo yoyote..."></textarea>
                    </div> -->
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                        Hifadhi
                    </button>
                    <button type="button" onclick="closePaymentModal()" class="flex-1 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Ghairi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
.select2-container--default .select2-selection--single {
    background-color: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    height: auto;
    color: #06b6d4; 
    font-size: 0.875rem;
    position: relative;
}
.select2-selection__rendered,
.select2-selection__clear,
.select2-selection__arrow {
    color: #d1d5db;
}
.select2-selection__arrow {
    right: 1rem;
    top: 0;
    width: 1.5rem;
    position: absolute;
}
.select2-selection__clear {
    right: 2.5rem;
    top: 50%;
    transform: translateY(-50%);
    position: absolute;
}
.custom-select2-dropdown {
    background-color: #1f2937;
    color: #d1d5db;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 0.5rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #ffffff !important; /* Force white text */
}
.custom-select2-dropdown .select2-results__option--highlighted {
    background-color: #06b6d4 !important; /* Tailwind cyan-400 */
    color: #ffffff !important;
}

/* White text in the dropdown input if searchable */
.select2-search__field {
    color: #ffffff !important;
    background-color: #1f2937 !important; /* match dark bg */
    border: 1px solid #374151;
}
.custom-select2-dropdown .select2-results__option--highlighted {
    background-color: #06b6d4;
    color: #ffffff;
}
.custom-select2-container { margin: 0; }
</style> 

<script>
$(document).ready(function () {
    const selectConfig = {
        placeholder: "Select",
        allowClear: true,
        width: '100%',
        dropdownCssClass: 'custom-select2-dropdown',
        containerCssClass: 'custom-select2-container'
    };

    // Customer Search Select
    const memberSelect = $('#memberSelect');
    memberSelect.select2({...selectConfig, placeholder: "Tafuta Mteja"});

    const navigateToMember = (memberId) => {
        if (memberId) {
            window.location.href = "/collections/" + memberId;
        }
    };

    // Auto-navigate when customer is selected
    memberSelect.on('select2:select', function () {
        navigateToMember($(this).val());
    });

    memberSelect.on('change', function () {
        navigateToMember(this.value);
    });



   
});


</script>

<script>
function openPaymentModal(type) {
    const modal = document.getElementById('payment-modal');
    const paymentType = document.getElementById('payment_type');
    const penaltyInfo = document.getElementById('penalty-info');
    const modalTitle = document.getElementById('modal-title');

    if (!modal || !paymentType || !penaltyInfo || !modalTitle) {
        return;
    }

    paymentType.value = type === 'penalty' ? 'penalty' : 'regular';

    if (type === 'penalty') {
        penaltyInfo.classList.remove('hidden');
        modalTitle.textContent = 'Lipa Faini';
    } else {
        penaltyInfo.classList.add('hidden');
        modalTitle.textContent = 'Fanya Malipo';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePaymentModal() {
    const modal = document.getElementById('payment-modal');
    if (!modal) {
        return;
    }
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('payment-modal');
    if (!modal) {
        return;
    }

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closePaymentModal();
        }
    });
});
</script>



</x-layouts.app>
