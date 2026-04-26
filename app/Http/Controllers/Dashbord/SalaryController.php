<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Helpers\SalaryHelpers;
use App\Models\Salary;
use App\Models\Salarysheet;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends BaseController
{
    use SalaryHelpers;
    public function __construct()
    {
        $this->middleware('role_or_permission:Salary access|Salary create|Salary edit|Salary delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Salary create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Salary edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Salary delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prev_full_date = date('Y-m', strtotime('-1 month'));

        $salarySheets = Salarysheet::all();

        return view('dashbord.Salary.index', compact('salarySheets', 'prev_full_date'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($user_id)
    {
        $user = User::where('id', $user_id)->first();
        $prev_full_date = date('Y-m', strtotime('-1 month'));
        $prev_advanch_check = Salary::where('user_id', $user_id)->where('status', '2')->where('date', $prev_full_date)->first();

        $salaries = Salary::where('user_id', $user_id)->latest()->get();

        return view('dashbord.Salary.create', compact('user', 'prev_full_date', 'prev_advanch_check', 'salaries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        // Guard Clause: Pengecekan has()
        if (! $request->has(['date', 'amount', 'status'])) {
            return $this->returnMessage('One or more required fields (date, amount, status) are missing in the request.', 'warning');
        }

        $salarySheet = Salarysheet::where('user_id', $request->id)->first();

        // Guard Clause: Pengecekan salarySheet
        if (! $salarySheet) {
            return $this->returnMessage('Salary sheet not found for the specified user ID', 'error');
        }

        $msg = function ($text, $type) {
            return $this->returnMessage($text, $type);
        };

        // Decomposition ke proses utama
        return $this->handleSalaryProcess($request, $salarySheet, $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $user = User::where('id', $salary->user_id)->first();

        return view('dashbord.Salary.edit', compact('user', 'salary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
