<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $payType = $request->session()->get('pay_type');
        if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        $expensesQuery = Expense::with('user')
            ->when($payType, function ($query, $payType) {
                return $query->where('pay_type', $payType);
            });

        $expenses = (clone $expensesQuery)
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalExpenses = (float) (clone $expensesQuery)->sum('amount');
        $monthlyExpenses = (float) (clone $expensesQuery)->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');
        $todayExpenses = (float) (clone $expensesQuery)->whereDate('expense_date', Carbon::today())->sum('amount');

        $paymentsQuery = Payment::query()
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($memberQuery) use ($payType) {
                    $memberQuery->where('pay_type', $payType);
                });
            });

        $totalPayments = (float) (clone $paymentsQuery)->sum('amount');
        $monthlyPayments = (float) (clone $paymentsQuery)->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('amount');
        $todayPayments = (float) (clone $paymentsQuery)->whereDate('payment_date', Carbon::today())->sum('amount');

        $netTotal = $totalPayments - $totalExpenses;
        $netMonthly = $monthlyPayments - $monthlyExpenses;
        $netToday = $todayPayments - $todayExpenses;
        
        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'monthlyExpenses',
            'todayExpenses',
            'totalPayments',
            'monthlyPayments',
            'todayPayments',
            'netTotal',
            'netMonthly',
            'netToday',
            'payType'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        $payType = $request->session()->get('pay_type');
        $validated['pay_type'] = in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true) ? $payType : null;

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Gharama imeongezwa kikamilifu!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);
        $payType = $request->session()->get('pay_type');
        $validated['pay_type'] = in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)
            ? $payType
            : $expense->pay_type;
        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Gharama imesasishwa kikamilifu!');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Gharama imefutwa kikamilifu!');
    }
}
