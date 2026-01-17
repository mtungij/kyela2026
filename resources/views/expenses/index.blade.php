<x-layouts.app :title="__('Expenses Management')">

<div class="w-full max-w-screen-xl px-4 sm:px-6 lg:px-8 py-6">
    
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

    <!-- Header -->
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-red-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        Usimamizi wa Gharama
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gharama za Leo</h3>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                TZS {{ number_format($todayExpenses, 2) }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-orange-500">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gharama za Mwezi Huu</h3>
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                TZS {{ number_format($monthlyExpenses, 2) }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-gray-500">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumla ya Gharama Zote</h3>
            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-2">
                TZS {{ number_format($totalExpenses, 2) }}
            </p>
        </div>
    </div>

    <!-- Add Expense Button -->
    <div class="mb-4">
        <button type="button" onclick="openExpenseModal()" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ongeza Gharama
        </button>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Orodha ya Gharama</h2>
            
            @if($expenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-red-600 dark:bg-red-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tarehe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aina</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Maelezo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kiasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mpokeaji</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ($expenses->currentPage() - 1) * $expenses->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $expense->expense_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $expense->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                    TZS {{ number_format($expense->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $expense->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="openEditModal({{ $expense->id }}, '{{ $expense->category }}', '{{ $expense->description }}', {{ $expense->amount }}, '{{ $expense->expense_date->format('Y-m-d') }}')" 
                                    class="text-cyan-600 hover:text-cyan-900 dark:text-cyan-400 mr-3">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Una uhakika unataka kufuta gharama hii?')" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Hakuna gharama zilizorekodiwa bado</p>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Add/Edit Expense Modal -->
<div id="expense-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ongeza Gharama
                </h3>
                <button type="button" onclick="closeExpenseModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            
            <form id="expense-form" method="POST" action="{{ route('expenses.store') }}" class="p-4 md:p-5">
                @csrf
                <input type="hidden" id="expense-id" name="_method" value="">
                
                <div class="space-y-4">
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Aina ya Gharama</label>
                        <select name="category" id="category" class="bg-gray-50 border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-600 dark:border-red-500 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="">Chagua aina</option>
                            <option value="Transport">Transport</option>
                            <option value="Ofisi">Ofisi</option>
                            <option value="Malipo ya Kazi">Malipo ya Kazi</option>
                            <option value="Matengenezo">Matengenezo</option>
                            <option value="Mawasiliano">Mawasiliano</option>
                            <option value="Nyingine">Nyingine</option>
                        </select>
                        @error('category')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Maelezo</label>
                        <textarea name="description" id="description" rows="3" class="bg-gray-50 border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-600 dark:border-red-500 dark:placeholder-gray-400 dark:text-white" placeholder="Eleza gharama..." required></textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kiasi (TSh)</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" class="bg-gray-50 border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-600 dark:border-red-500 dark:placeholder-gray-400 dark:text-white" placeholder="Weka kiasi" required>
                        @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="expense_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarehe</label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-600 dark:border-red-500 dark:placeholder-gray-400 dark:text-white" required>
                        @error('expense_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Hifadhi
                    </button>
                    <button type="button" onclick="closeExpenseModal()" class="flex-1 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Ghairi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openExpenseModal() {
        document.getElementById('expense-modal').classList.remove('hidden');
        document.getElementById('expense-modal').classList.add('flex');
        document.getElementById('modal-title').textContent = 'Ongeza Gharama';
        document.getElementById('expense-form').action = "{{ route('expenses.store') }}";
        document.getElementById('expense-form').reset();
        document.getElementById('expense-id').value = '';
    }

    function openEditModal(id, category, description, amount, date) {
        document.getElementById('expense-modal').classList.remove('hidden');
        document.getElementById('expense-modal').classList.add('flex');
        document.getElementById('modal-title').textContent = 'Hariri Gharama';
        document.getElementById('expense-form').action = `/expenses/${id}`;
        document.getElementById('expense-id').value = 'PUT';
        document.getElementById('category').value = category;
        document.getElementById('description').value = description;
        document.getElementById('amount').value = amount;
        document.getElementById('expense_date').value = date;
    }

    function closeExpenseModal() {
        document.getElementById('expense-modal').classList.add('hidden');
        document.getElementById('expense-modal').classList.remove('flex');
    }

    // Auto-open modal if there are validation errors
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openExpenseModal();
    });
    @endif
</script>

</x-layouts.app>
