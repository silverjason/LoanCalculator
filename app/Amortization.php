<?php


namespace App;


class Amortization {

    private $loan_amount;
    private $term_years;
    private $interest;
    private $terms;
    private $period;
    private $principal;
    private $balance;
    private $term_pay;
    private $results;
    private $interest_free_months;
    private $month;


    public function getInterestForYear(int $year) {
        return $this->results['year_totals'][$year]['interest'];
    }

    public function getPrincipalForYear(int $year) {
        return $this->results['year_totals'][$year]['principal'];
    }

    public function getPrincipalBalanceForYear(int $year) {
        $balance = $this->results['year_totals'][$year]['principal_balance'];
        return $balance > 0 ? $balance : 0;
    }

    public function __construct($data)
    {
        if($this->validate($data)) {


            $this->loan_amount 	= (float) $data['loan_amount'];
            $this->term_years 	= (int) $data['term_years'];
            $this->interest 	= (float) $data['interest'];
            $this->terms 		= (int) $data['terms'];

            if ($data['interest_only_for_first_year']) {
                $this->interest_free_months = 12;
            }
            $this->month = 0;

            $this->terms = ($this->terms == 0) ? 1 : $this->terms;

            $this->period = $this->terms * $this->term_years;
            $this->interest = ($this->interest/100) / $this->terms;

            $this->results = array(
                'inputs' => $data,
                'summary' => $this->getSummary(),
                'schedule' => $this->getSchedule(),
            );

            $year_totals = [];

            foreach ($this->results['schedule'] as $i=>$month) {
                $year = intval(($i/12) + 1);

                if (!isset($year_totals[$year])){
                    $year_totals[$year] = ['interest' => 0, 'principal' => 0];
                }


                $year_totals[$year]['interest'] += $month['interest'];
                $year_totals[$year]['principal'] += $month['principal'];

            }

            foreach ($year_totals as $year => &$year_total) {
                $year_total['principal_balance'] = ($year_totals[$year-1]['principal_balance'] ?? $data['loan_amount']) - $year_totals[$year]['principal'];
            }

            $this->results['year_totals'] = $year_totals;

        }
    }

    private function validate($data) {
        $data_format = array(
            'loan_amount' 	=> 0,
            'term_years' 	=> 0,
            'interest' 		=> 0,
            'terms' 		=> 0
        );

        $validate_data = array_diff_key($data_format,$data);

        if(empty($validate_data)) {
            return true;
        }else{
            echo "<div style='background-color:#ccc;padding:0.5em;'>";
            echo '<p style="color:red;margin:0.5em 0em;font-weight:bold;background-color:#fff;padding:0.2em;">Missing Values</p>';
            foreach ($validate_data as $key => $value) {
                echo ":: Value <b>$key</b> is missing.<br>";
            }
            echo "</div>";
            return false;
        }
    }

    private function calculate()
    {

        if (!$this->interest_free_months && $this->month > $this->interest_free_months) {
            $deno = 1 - 1 / pow((1+ $this->interest),$this->period);
            $this->term_pay = ($this->loan_amount * $this->interest) / $deno;
        }

        $interest = $this->loan_amount * $this->interest;

        if (!$this->interest_free_months && $this->month > $this->interest_free_months) {
            $this->principal = $this->term_pay - $interest;
        }
        $this->balance = $this->loan_amount - $this->principal ?? 0;

        $this->month ++;
        return array (
            'payment' 	=> $this->term_pay,
            'interest' 	=> $interest,
            'principal' => $this->principal,
            'balance' 	=> $this->balance
        );
    }

    public function getSummary()
    {
        $this->calculate();
        $total_pay = $this->term_pay *  $this->period;
        $total_interest = $total_pay - $this->loan_amount;

        return array (
            'total_pay' => $total_pay,
            'total_interest' => $total_interest,
        );
    }

    public function getSchedule ()
    {
        $schedule = array();

        while  ($this->balance >= 0) {
            array_push($schedule, $this->calculate());
            $this->loan_amount = $this->balance;
            $this->period--;
        }

        return $schedule;

    }

}


