<x-layouts.app :title="__('Collections')">

<div class="w-full px-4 lg:px-12">
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 md:p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                Search Customer
            </h3>
            
            <!-- Customer Select2 Dropdown -->
            <div class="mb-4">
                <label for="member-search" class="block text-sm font-medium mb-2 dark:text-gray-300">* Search Customer:</label>
                <select id="member-search" name="member_id"
                    class="py-3 px-4 pe-9 block w-full bg-cyan-600 border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:placeholder-gray-500 dark:focus:ring-gray-600 select2">
                    <option value="">Select customer</option>
                    @foreach(\App\Models\Member::orderBy('name')->get() as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->name }} - {{ $m->phone }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="button" onclick="navigateToMember()" class="w-full sm:w-auto text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-6 py-3 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">
                Tafuta
            </button>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Select2 Custom Styling */
    .select2-container--classic .select2-selection--single {
        border: 1px solid #06b6d4 !important;
        border-radius: 0.5rem !important;
        height: auto !important;
        padding: 0.625rem !important;
    }
    
    .select2-container--classic .select2-selection--single:focus,
    .select2-container--classic.select2-container--open .select2-selection--single {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1) !important;
    }
    
    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        color: #111827 !important;
    }
    
    .select2-container--classic .select2-selection--single .select2-selection__placeholder {
        color: #6b7280 !important;
    }
    
    .select2-dropdown {
        border: 1px solid #06b6d4 !important;
        border-radius: 0.5rem !important;
    }
    
    /* Search input styling */
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #06b6d4 !important;
        border-radius: 0.5rem !important;
        padding: 0.625rem !important;
        outline: none !important;
        background-color: #ffffff !important;
        color: #111827 !important;
    }
    
    .select2-search--dropdown .select2-search__field:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1) !important;
    }
    
    .select2-search--dropdown .select2-search__field::placeholder {
        color: #6b7280 !important;
    }
    
    .select2-container--classic .select2-results__option--highlighted[aria-selected] {
        background-color: #06b6d4 !important;
        color: #ffffff !important;
    }
    
    .select2-container--classic .select2-results__option[aria-selected=true] {
        background-color: #ecfeff !important;
        color: #0e7490 !important;
    }
    
    .select2-container--classic .select2-results__option:hover {
        background-color: #e0f2fe !important;
        color: #0e7490 !important;
    }
    
    /* Dark mode support */
    .dark .select2-container--classic .select2-selection--single {
        background-color: #374151 !important;
        border-color: #06b6d4 !important;
    }
    
    .dark .select2-container--classic .select2-selection--single .select2-selection__rendered {
        color: #ffffff !important;
    }
    
    .dark .select2-dropdown {
        background-color: #1f2937 !important;
        border-color: #06b6d4 !important;
    }
    
    .dark .select2-search--dropdown .select2-search__field {
        background-color: #374151 !important;
        border-color: #06b6d4 !important;
        color: #ffffff !important;
    }
    
    .dark .select2-search--dropdown .select2-search__field::placeholder {
        color: #9ca3af !important;
    }
    
    .dark .select2-search--dropdown .select2-search__field:focus {
        background-color: #374151 !important;
        border-color: #06b6d4 !important;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2) !important;
    }
    
    .dark .select2-container--classic .select2-results__option {
        color: #ffffff !important;
        background-color: #1f2937 !important;
    }
    
    .dark .select2-container--classic .select2-results__option:hover {
        background-color: #374151 !important;
        color: #ffffff !important;
    }
    
    .dark .select2-container--classic .select2-results__option--highlighted[aria-selected] {
        background-color: #06b6d4 !important;
        color: #ffffff !important;
    }
    
    .dark .select2-container--classic .select2-results__option[aria-selected=true] {
        background-color: #0e7490 !important;
        color: #ffffff !important;
    }
</style>

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#member-search').select2({
            placeholder: "Tafuta member kwa jina au simu...",
            allowClear: true,
            theme: 'classic',
            width: '100%'
        });
    });

    function navigateToMember() {
        const memberId = $('#member-search').val();
        if (memberId) {
            window.location.href = "/collections/" + memberId;
        } else {
            alert('Tafadhali chagua member kwanza');
        }
    }
</script>

    
</x-layouts.app>