<x-layouts.app :title="__('Ambao Wamelipa - Ripoti Ya Malipo')">

<div class="w-full px-4 lg:px-8 py-6">
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
                <h3 class="text-gray-800 font-semibold dark:text-white">Karibu</h3>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-sm text-red-800 rounded-lg p-4 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500 mb-4" role="alert">
        <div class="flex">
            <div class="ms-3">
                <h3 class="text-gray-800 font-semibold dark:text-white">Hitilafu</h3>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Header -->
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-cyan-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between w-full">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        Ambao Wamelipa - {{ $payTypeLabel ?? 'Wote' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('payments.report') }}" class="space-y-4" id="paymentFilterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- From Date -->
                <div>
                   <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Chagua Tarehe
    </label>
                  <div>
   
    <input 
        type="date" 
        name="payment_date" 
        id="payment_date"
        value="{{ request('payment_date') }}"
        onchange="document.getElementById('paymentFilterForm').submit()"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
    />
</div>
                </div>

               

                <!-- Pay Type -->
                <div>
                    <label for="pay_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Aina ya Mchango
                    </label>
                    <select 
                        name="pay_type" 
                        id="pay_type"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="" {{ !isset($payType) ? 'selected' : '' }}>Wote</option>
                        <option value="mchango_mdogo" {{ isset($payType) && $payType === 'mchango_mdogo' ? 'selected' : '' }}>Mchango Mdogo</option>
                        <option value="mchango_mkubwa" {{ isset($payType) && $payType === 'mchango_mkubwa' ? 'selected' : '' }}>Mchango Mkubwa</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition-colors"
                    >
                        Chafya
                    </button>
                    <a 
                        href="{{ route('payments.download-pdf', request()->query()) }}"
                        class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Payments -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-cyan-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Malipo</div>
            <div class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $summary['total_payments'] }}</div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Kiasi</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($summary['total_amount'], 0) }}</div>
        </div>

        <!-- Total Members -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-purple-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Wanachama</div>
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $summary['total_members'] }}</div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">

    <div class="mb-4">
    <input 
        type="text" 
        id="searchInput"
        placeholder="Tafuta jina la mwanachama..."
        class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
    >
</div>
       <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                <thead class="bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 font-semibold sticky top-0">
                    <tr>
                    <th class="px-6 py-3 border-b dark:border-gray-700">S/No</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Jina la Mwanachama</th>
                     
                        
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-right">Kiasi</th>

                          <th class="px-6 py-3 border-b dark:border-gray-700 text-right">tenda</th>
                       
                       
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-center">Hatua</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $index => $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white uppercase">
                                {{ $payment->member->name }}
                            </td>
                          
                         
                            <td class="px-6 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($payment->amount, 0) }}
                            </td>
                        <td class="px-6 py-3">
    <a href="{{ route('payments.member.statement', $payment->member->id) }}" class="inline-flex items-center justify-center text-green-500 hover:text-green-600 transition">
        
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.5" 
             stroke="currentColor" 
             class="w-5 h-5">
             
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
            
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>

    </a>
</td>
                          
                            <td class="px-6 py-3 text-center">
                                <form action="{{ route('payments.delete', $payment->id) }}" method="POST" class="inline delete-payment-form" data-member-name="{{ $payment->member->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors open-delete-modal">
                                        Futa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Hakuna malipo kwenye kipindi hiki
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<div id="deletePaymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4" role="dialog" aria-modal="true" aria-labelledby="deletePaymentModalTitle">
    <div class="w-full max-w-md rounded-lg bg-white dark:bg-gray-800 shadow-xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 id="deletePaymentModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Thibitisha Kufuta Malipo</h3>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Una uhakika unataka kufuta malipo ya
                <span id="deleteMemberName" class="font-semibold text-gray-900 dark:text-white"></span>?
            </p>
         
        </div>
        <div class="px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
            <button type="button" id="cancelDeletePayment" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                Ghairi
            </button>
            <button type="button" id="confirmDeletePayment" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                Ndiyo, Futa
            </button>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let nameCell = row.querySelector('td:first-child');
            if (nameCell) {
                let text = nameCell.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
    });
}

const deletePaymentModal = document.getElementById('deletePaymentModal');
const deleteMemberName = document.getElementById('deleteMemberName');
const confirmDeletePayment = document.getElementById('confirmDeletePayment');
const cancelDeletePayment = document.getElementById('cancelDeletePayment');
const openDeleteButtons = document.querySelectorAll('.open-delete-modal');
let selectedDeleteForm = null;
let lastTriggerButton = null;

function closeDeleteModal() {
    if (deletePaymentModal) {
        deletePaymentModal.classList.add('hidden');
        deletePaymentModal.classList.remove('flex');
    }
    selectedDeleteForm = null;

    if (lastTriggerButton) {
        lastTriggerButton.focus();
        lastTriggerButton = null;
    }
}

openDeleteButtons.forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('.delete-payment-form');
        if (!form || !deletePaymentModal || !deleteMemberName) {
            return;
        }

        lastTriggerButton = this;
        selectedDeleteForm = form;
        deleteMemberName.textContent = form.dataset.memberName || 'mwanachama huyu';
        deletePaymentModal.classList.remove('hidden');
        deletePaymentModal.classList.add('flex');

        if (cancelDeletePayment) {
            cancelDeletePayment.focus();
        }
    });
});

if (cancelDeletePayment) {
    cancelDeletePayment.addEventListener('click', closeDeleteModal);
}

if (confirmDeletePayment) {
    confirmDeletePayment.addEventListener('click', function () {
        if (selectedDeleteForm) {
            selectedDeleteForm.submit();
        }
    });
}

if (deletePaymentModal) {
    deletePaymentModal.addEventListener('click', function (event) {
        if (event.target === deletePaymentModal) {
            closeDeleteModal();
        }
    });
}

document.addEventListener('keydown', function (event) {
    if (!deletePaymentModal || deletePaymentModal.classList.contains('hidden')) {
        return;
    }

    if (event.key === 'Escape') {
        closeDeleteModal();
    }

    if (event.key === 'Enter') {
        const isTextarea = document.activeElement && document.activeElement.tagName === 'TEXTAREA';
        if (!isTextarea && selectedDeleteForm) {
            event.preventDefault();
            selectedDeleteForm.submit();
        }
    }
});
</script>

</x-layouts.app>
