<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Salary;
use App\Models\Salarysheet;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends BaseController
{
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
        // validation
        $validation = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        if ($request->has(['date', 'amount', 'status'])) {
            $salarySheet = Salarysheet::where('user_id', $request->id)->first();
            $prev_full_date = date('Y-m', strtotime('-1 month'));
            $date_formate = date('Y-m', strtotime($request->date));

            if ($salarySheet) {
                $salaryAmount = $salarySheet->amount;
                $advance_salary_check = Salary::where('user_id', $request->id)->where('status', '2')->first();
                $prev_advanch_check = Salary::where('user_id', $request->id)->where('status', '2')->where('date', $prev_full_date)->first();

                $rules = [
                    'paid_monthly' => ($request->status == '1' && $request->amount == $salaryAmount && $prev_full_date == $date_formate),
                    'advance' => ($request->status == '2' && $request->amount > '0' && $request->amount < $salaryAmount),
                    'due_payment' => ($request->status == '1' && $prev_full_date == $date_formate && $request->amount == optional($prev_advanch_check)->due),
                ];

                $messages = [
                    'paid_monthly' => ['Teacher salary payment successfulliy', 'success'],
                    'already_paid' => ['you have alrady pay this month salary', 'warning'],
                    'advance' => ['Advanch salary payment successfulliy', 'success'],
                    'advance_exit' => ['Advance salary alrady exit', 'warning'],
                    'due_payment' => ['Pay due salary this month', 'info'],
                    'error' => ['something with wrong pleace try agin!', 'warning'],
                ];

                if ($rules['paid_monthly']) {
                    $prev_month_salary_check = Salary::where('user_id', $request->id)->where('date', $date_formate)->first();
                    if (! $prev_month_salary_check) {
                        $data = new Salary;
                        $data->user_id = $request->id;
                        $data->amount = $request->amount;
                        $data->status = $request->status;
                        $data->date = $date_formate;
                        $data->save();

                        return $this->returnMessage(...$messages['paid_monthly']);
                    }

                    return $this->returnMessage(...$messages['already_paid']);
                }

                if ($rules['advance']) {
                    if (! $advance_salary_check) {
                        $data = new Salary;
                        $data->user_id = $request->id;
                        $data->amount = $request->amount;
                        $data->due = $salaryAmount - $request->amount;
                        $data->status = $request->status;
                        $data->date = $date_formate;
                        $data->save();

                        return $this->returnMessage(...$messages['advance']);
                    }

                    return $this->returnMessage(...$messages['advance_exit']);
                }

                if ($rules['due_payment']) {
                    $prev_advanch_check->update([
                        'amount' => $request->amount + $prev_advanch_check->amount,
                        'due' => 0,
                        'status' => $request->status,
                    ]);

                    return $this->returnMessage(...$messages['due_payment']);
                }

                return $this->returnMessage(...$messages['error']);

            } else {
                return $this->returnMessage('Salary sheet not found for the specified user ID', 'error');
            }
        } else {
            return $this->returnMessage('One or more required fields are missing', 'warning');
        }
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
