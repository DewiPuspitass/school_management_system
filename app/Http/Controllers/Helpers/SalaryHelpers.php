<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Salary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait SalaryHelpers
{
    private function processSalaryPayment(Request $request, $salarySheet, \Closure $msg)
    {
        $prev_full_date = date('Y-m', strtotime('-1 month'));
        $date_formate = date('Y-m', strtotime($request->date)); // Sesuai logika explode Y-m
        $salaryAmount = $salarySheet->amount;

        // Lookup Data
        $advance_salary_check = Salary::where('user_id', $request->id)->where('status', '2')->first();
        $prev_advanch_check = Salary::where('user_id', $request->id)->where('status', '2')->where('date', $prev_full_date)->first();

        // LOOKUP RULES: Memetakan kondisi agar tetap sama persis dengan kode asli
        $isPaidMonthly = ($request->status == '1' && $request->amount == $salaryAmount && $prev_full_date == $date_formate);
        $isAdvance = ($request->status == '2' && $request->amount > '0' && $request->amount < $salaryAmount);

        // Pengecekan null-safe untuk prev_advanch_check agar tidak error
        $isDuePay = ($request->status == '1' && $prev_full_date == $date_formate && $prev_advanch_check && $request->amount == $prev_advanch_check->due);

        // Flow Control menggunakan Early Return (Guard Clauses)
        if ($isPaidMonthly) {
            return $this->handleMonthlySalary($request, $date_formate, $msg);
        }

        if ($isAdvance) {
            return $this->handleAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check, $msg);
        }

        if ($isDuePay) {
            return $this->handleDueSalary($request, $prev_advanch_check, $msg);
        }

        return $msg('something with wrong pleace try agin!', 'warning');
    }

    private function handleMonthlySalary($request, $date_formate, $msg)
    {
        $prev_month_salary_check = Salary::where('user_id', $request->id)->where('date', $date_formate)->first();

        if ($prev_month_salary_check) {
            return $msg('you have alrady pay this month salary', 'warning');
        }

        $data = new Salary;
        $data->user_id = $request->id;
        $data->amount = $request->amount;
        $data->status = $request->status;
        $data->date = $date_formate;
        $data->save();

        return $msg('Teacher salary payment successfulliy', 'success');
    }

    private function handleAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check, $msg)
    {
        if ($advance_salary_check) {
            return $msg('Advance salary alrady exit', 'warning');
        }

        $data = new Salary;
        $data->user_id = $request->id;
        $data->amount = $request->amount;
        $data->due = $salaryAmount - $request->amount;
        $data->status = $request->status;
        $data->date = $date_formate;
        $data->save();

        return $msg('Advanch salary payment successfulliy', 'success');
    }

    private function handleDueSalary($request, $prev_advanch_check, $msg)
    {
        $prev_advanch_check->update([
            'amount' => $request->amount + $prev_advanch_check->amount,
            'due' => 0,
            'status' => $request->status,
        ]);

        return $msg('Pay due salary this month', 'info');
    }
}
