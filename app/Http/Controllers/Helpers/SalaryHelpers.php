<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Salary;
use Illuminate\Http\Request;

trait SalaryHelpers
{
    private function formatDate($date): string
    {
        $expload = explode('-', $date);

        return $expload[0].'-'.$expload[1];
    }

    private function handleSalaryProcess(Request $request, $salarySheet, \Closure $msg)
    {
        $prev_full_date = date('Y-m', strtotime('-1 month'));
        $date_formate = $this->formatDate($request->date);
        $salaryAmount = $salarySheet->amount;

        $advance_salary_check = Salary::where('user_id', $request->id)->where('status', '2')->first();
        $prev_advanch_check = Salary::where('user_id', $request->id)->where('status', '2')->where('date', $prev_full_date)->first();

        // STRATEGI: LOOKUP TABLE (Menghitung semua kondisi di awal)
        $isMonthly = ($request->status == '1' && $request->amount == $salaryAmount && $prev_full_date == $date_formate);
        $isAdvance = ($request->status == '2' && $request->amount > '0' && $request->amount < $salaryAmount);

        $isDue = ($request->status == '1' && $prev_full_date == $date_formate && $prev_advanch_check && $request->amount == $prev_advanch_check->due);

        // STRATEGI: GUARD CLAUSES (Mengganti If-ElseIf-Else menjadi Early Return)
        if ($isMonthly) {
            return $this->processMonthlySalary($request, $date_formate, $msg);
        }

        if ($isAdvance) {
            return $this->processAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check, $msg);
        }

        if ($isDue) {
            return $this->processDueSalary($request, $prev_advanch_check, $msg);
        }

        return $msg('something with wrong pleace try agin!', 'warning');
    }

    private function processMonthlySalary($request, $date_formate, $msg)
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

    private function processAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check, $msg)
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

    private function processDueSalary($request, $prev_advanch_check, $msg)
    {
        $prev_advanch_check->update([
            'amount' => $request->amount + $prev_advanch_check->amount,
            'due' => 0,
            'status' => $request->status,
        ]);

        return $msg('Pay due salary this month', 'info');
    }
}
