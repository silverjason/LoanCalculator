<?php

namespace Tests\Unit;

use App\Amortization;
use PHPUnit\Framework\TestCase;
use function Symfony\Component\VarDumper\Dumper\esc;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMDentMethod()
    {

        //Calculations
        $income_per_crown = 1600;
        $expense_new_per_crown = 30;

        $no_interest_in_first_year = true;
        $interest_only_for_first_year = false;

        $expense_current_per_week = 300;
        $crowns_per_week = 8;

        $loan_amount = 100000;
        $loan_term = 5;
        $interest = 0.045;
        $payments_per_year = 12;

        $months_volume_impacted = 6;

        $is_company = false;
        $tax_rate = $is_company ? 0.27 : 0.45;
        $claim_instant_threshold = false;

        //NEW
        $data = array(
            'loan_amount' 	=> $loan_amount,
            'term_years' 	=> $loan_term,
            'interest' 		=> $interest * 100,
            'terms' 		=> $payments_per_year,
            'interest_only_for_first_year' => $interest_only_for_first_year
        );


        $amortization = new Amortization($data);

        $year = 1;
        $year_results = [];

        while ($year <= $loan_term) {

            $year_results[$year] = [];

            //1
            $gross_profit_per_crown = $income_per_crown - $expense_new_per_crown;
            $total_crowns_per_year = 52 * $crowns_per_week;

            $reduced_total_crowns_first_year = $total_crowns_per_year;
            if ($year == 1 && $months_volume_impacted > 0) {
                $reduced_total_crowns_first_year = $total_crowns_per_year / 12 * $months_volume_impacted;
            }


            //2
            $income = $income_per_crown * $reduced_total_crowns_first_year;
            $direct_expense = $expense_new_per_crown * $reduced_total_crowns_first_year;
            $gross_profit = $income - $direct_expense;
            //insert EBIT and Profit before tax


            $interest_expense = $no_interest_in_first_year && $year == 1 ? 0 : $amortization->getInterestForYear($year); // free for first year and then = sum of interest payments for that year
            $profit_before_tax = $gross_profit - $interest_expense;

            if ($year == 1) {
                $write_off = $claim_instant_threshold ? $loan_amount : 0;
                $tax_expense = abs(($profit_before_tax - $write_off) * $tax_rate);
            } else {
                $tax_expense = $profit_before_tax * $tax_rate;
            }
            $profit_after_tax = $profit_before_tax - $tax_expense;

            //4
            $cashflow_from_financing = $year == 1 && $interest_only_for_first_year ? 0 : $amortization->getPrincipalForYear($year);
            $cashflow = $profit_after_tax - $cashflow_from_financing;
            $year_results[$year]['cashflow'] = $cashflow;


            //3
            $prev_year_cash = $year_results[$year-1]['cash'] ?? 0;
            $cash = $prev_year_cash + $cashflow;
            $year_results[$year]['cash'] = $cash;
            $assets = $cash + $loan_amount;


            $borrowings = $amortization->getPrincipalBalanceForYear($year);
            $net_assets = $assets - $borrowings;

            $year ++;

        }


        $this->assertTrue(true);
    }


    public function testCurrentMethod()
    {

        //Calculations
        $income_per_crown = 1600;
        $expense_new_per_crown = 300;

        $no_interest_in_first_year = true;
        $interest_only_for_first_year = false;

        $expense_current_per_week = 300;
        $crowns_per_week = 8;

        $loan_amount = 100000;
        $loan_term = 5;
        $interest = 0.045;
        $payments_per_year = 12;

        $months_volume_impacted = 6;

        $is_company = false;
        $tax_rate = $is_company ? 0.27 : 0.45;
        $claim_instant_threshold = false;

        //NEW
        $data = array(
            'loan_amount' 	=> $loan_amount,
            'term_years' 	=> $loan_term,
            'interest' 		=> $interest * 100,
            'terms' 		=> $payments_per_year,
            'interest_only_for_first_year' => $interest_only_for_first_year
        );

        $amortization = new Amortization($data);


        $year = 1;

        $year_results = [];

        while ($year <= $loan_term) {

            $year_results[$year] = [];

            //1
            $gross_profit_per_crown = $income_per_crown - $expense_new_per_crown;
            $total_crowns_per_year = 52 * $crowns_per_week;

            $reduced_total_crowns_first_year = $total_crowns_per_year;
            if ($year == 1 && $months_volume_impacted > 0) {
                $reduced_total_crowns_first_year = $total_crowns_per_year / 12 * $months_volume_impacted;
            }


            //2
            $income = $income_per_crown * $reduced_total_crowns_first_year;
            $direct_expense = $expense_new_per_crown * $reduced_total_crowns_first_year;
            $gross_profit = $income - $direct_expense;
            //insert EBIT and Profit before tax


            $interest_expense = 0;
            $profit_before_tax = $gross_profit - $interest_expense;

            if ($year == 1) {
                $write_off = $claim_instant_threshold ? $loan_amount : 0;
                $tax_expense = abs(($profit_before_tax - $write_off) * $tax_rate);
            } else {
                $tax_expense = $profit_before_tax * $tax_rate;
            }
            $profit_after_tax = $profit_before_tax - $tax_expense;

            //4
            $cashflow_from_financing = 0;
            $cashflow = $profit_after_tax - $cashflow_from_financing;
            $year_results[$year]['cashflow'] = $cashflow;


            //3
            $prev_year_cash = $year_results[$year-1]['cash'] ?? 0;
            $cash = $prev_year_cash + $cashflow;
            $year_results[$year]['cash'] = $cash;
            $assets = $cash + $loan_amount;


            $borrowings = 0;
            $net_assets = $assets - $borrowings;

            $year ++;

        }


        $this->assertTrue(true);
    }
}
