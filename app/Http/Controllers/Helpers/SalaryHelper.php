<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Salary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait SalaryHelper
{
    private function handleSalaryProcess(Request $request, $salarySheet): ?RedirectResponse
    {
        $prev_full_date = date('Y-m', strtotime('-1 month'));
        $date_formate = $this->formatDate($request->date);
        $salaryAmount = $salarySheet->amount;

        $advance_salary_check = Salary::where('user_id', $request->id)->where('status', '2')->first();
        $prev_advanch_check = Salary::where('user_id', $request->id)->where('status', '2')->where('date', $prev_full_date)->first();

        if ($request->status == '1' && $request->amount == $salaryAmount && $prev_full_date == $date_formate) {
            return $this->processMonthlySalary($request, $date_formate);
        } elseif ($request->status == '2' && $request->amount > '0' && $request->amount < $salaryAmount) {
            return $this->processAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check);
        } elseif ($request->status == '1' && $prev_full_date == $date_formate && $request->amount == $prev_advanch_check->due) {
            return $this->processDueSalary($request, $prev_advanch_check);
        } else {
            return $this->returnMessage('something with wrong pleace try agin!', 'warning');
        }
    }

    private function formatDate($date): string
    {
        $expload = explode('-', $date);

        return $expload[0].'-'.$expload[1];
    }

    private function processMonthlySalary($request, $date_formate): RedirectResponse
    {
        $prev_month_salary_check = Salary::where('user_id', $request->id)->where('date', $date_formate)->first();
        if (! $prev_month_salary_check) {
            $data = new Salary;
            $data->user_id = $request->id;
            $data->amount = $request->amount;
            $data->status = $request->status;
            $data->date = $date_formate;
            $data->save();

            return $this->returnMessage('Teacher salary payment successfulliy', 'success');
        } else {
            return $this->returnMessage('you have alrady pay this month salary', 'warning');
        }
    }

    private function processAdvanceSalary($request, $date_formate, $salaryAmount, $advance_salary_check): RedirectResponse
    {
        if (! $advance_salary_check) {
            $data = new Salary;
            $data->user_id = $request->id;
            $data->amount = $request->amount;
            $data->due = $salaryAmount - $request->amount;
            $data->status = $request->status;
            $data->date = $date_formate;
            $data->save();

            return $this->returnMessage('Advanch salary payment successfulliy', 'success');
        } else {
            return $this->returnMessage('Advance salary alrady exit', 'warning');
        }
    }

    private function processDueSalary($request, $prev_advanch_check): RedirectResponse
    {
        $prev_advanch_check->update([
            'amount' => $request->amount + $prev_advanch_check->amount,
            'due' => 0,
            'status' => $request->status,
        ]);

        return $this->returnMessage('Pay due salary this month', 'info');
    }
}
