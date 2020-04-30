
let loan_amount;
let original_loan_amount;
let term_years;
let interest;
let terms;
let period;
let principal;
let balance;
let term_pay;
let results;
let interest_free_months;
let month;
let model_total_years = 7;
//Chart
let cashflow_chart;
let profit_chart;

function getInterestForYear(year) {
    if (year > term_years) return 0;
    return results['year_totals'][year]['interest'];
}

function getPrincipalForYear(year) {
    if (year > term_years) return 0;
    return results['year_totals'][year]['principal'];
}

function getPrincipalBalanceForYear(year) {
    if (year > term_years) return 0;
    let principal_balance = results['year_totals'][year]['principal_balance'];
    return principal_balance > 0 ? principal_balance : 0;
}

function calculateLoan() {


    //Calculations
    let income_per_crown = document.getElementById("income_per_crown").value;
    let crowns_per_week = document.getElementById("crowns_per_week").value;
    let loan_amount = document.getElementById("loan_amount").value;
    let loan_term = document.getElementById("term").value;
    let interest = document.getElementById("interest_rate").value / 100;
    let months_volume_impacted = document.getElementById("months_impacted").value;
    let interest_only_for_first_year = document.getElementById("interest_only_for_first_year").checked;
    let no_interest_in_first_year = document.getElementById("no_interest_in_first_year").checked;
    let claim_instant_threshold = document.getElementById("claim_instant_threshold").checked;
    let is_company = document.getElementById("is_company").checked;
    let tax_rate = is_company ? 0.27 : 0.45;
    let payments_per_year = 12;

    let data = {
        'loan_amount' : loan_amount,
        'term_years' 	: loan_term,
        'interest' 		: interest * 100,
        'terms' 		: payments_per_year,
        'interest_only_for_first_year' : interest_only_for_first_year
    }

    execute(data);

    let mside_method_results = getCashflow(
        true,
        30,
        income_per_crown,
        no_interest_in_first_year,
        interest_only_for_first_year,
        crowns_per_week,
        loan_amount,
        loan_term,
        interest,
        payments_per_year,
        months_volume_impacted,
        tax_rate,
        claim_instant_threshold
        );


    let current_method_results = getCashflow(
        false,
        300,
        income_per_crown,
        no_interest_in_first_year,
        interest_only_for_first_year,
        crowns_per_week,
        loan_amount,
        loan_term,
        interest,
        payments_per_year,
        months_volume_impacted,
        tax_rate,
        claim_instant_threshold
    );

    drawCashflowGraph(current_method_results, mside_method_results, loan_term)
    drawProfitGraph(current_method_results, mside_method_results, loan_term)

}


function drawCashflowGraph(current_method_results, mside_method_results, loan_term) {

    let labels = ["1 Year"];


    let datasets = [{
        label: 'Current Method',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
        data: [Math.round(current_method_results[1]['cashflow'])]
    },{
        label: 'MSide Method',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        data: [Math.round(mside_method_results[1]['cashflow'])]
    }];

    var ctx = document.getElementById('cashflow-chart');

    if (cashflow_chart) {
        cashflow_chart.data.datasets = datasets;
        cashflow_chart.update();
    } else {
        cashflow_chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: labels,
                datasets: datasets
            },

            // Configuration options go here
            options:
                {responsive:false,
                    title: {
                        display: true,
                        text: "Cashflow ($)"
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
        });
    }

}




function drawProfitGraph(current_method_results, mside_method_results) {

    let labels = [];
    for (let index = 1; index <= model_total_years; ++index) {
        labels.push(index);
    }


    let datasets = [{
        label: 'Current Method',
        backgroundColor: 'rgba(255, 255, 255, 0)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 4,
        data: current_method_results.flatMap(x => [(x.profit)])
    },{
        label: 'MSide Method',
        backgroundColor: 'rgba(255, 255, 255, 0)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 4,
        data: mside_method_results.flatMap(x => [x.profit])
    }];

    var ctx = document.getElementById('profit-chart');

    if (profit_chart) {
        profit_chart.data.datasets = datasets;
        profit_chart.update();
    } else {
        profit_chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: labels,
                datasets: datasets
            },

            // Configuration options go here
            options:
                {responsive:false,
                    title: {
                        display: true,
                        text: "Cumulative Profit After Tax ($)"
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
        });
    }


}


function getCashflow(is_mside_method,
                     expense_new_per_crown,
                     income_per_crown,
                     no_interest_in_first_year,
                     interest_only_for_first_year,
                     crowns_per_week,
                     loan_amount,
                     loan_term,
                     interest,
                     payments_per_year,
                     months_volume_impacted,
                     tax_rate,
                     claim_instant_threshold) {

    let year = 1;
    let year_results = [];

    while (year <= model_total_years) {

        year_results[year] = [];

        //1
        let gross_profit_per_crown = income_per_crown - expense_new_per_crown;
        let total_crowns_per_year = 52 * crowns_per_week;

        let reduced_total_crowns_first_year = total_crowns_per_year;
        if (year === 1 && months_volume_impacted > 0) {
            reduced_total_crowns_first_year = total_crowns_per_year / 12 * months_volume_impacted;
        }


        //2
        let income = income_per_crown * reduced_total_crowns_first_year;
        let direct_expense = expense_new_per_crown * reduced_total_crowns_first_year;
        let gross_profit = income - direct_expense;
        //insert EBIT and Profit before tax

        let interest_expense = 0;
        if (is_mside_method) {
            interest_expense = no_interest_in_first_year && year === 1 ? 0 : getInterestForYear(year); // free for first year and then = sum of interest payments for that year
        }

        let profit_before_tax = gross_profit - interest_expense;

        let tax_expense = null;
        if (year === 1) {
            let write_off = claim_instant_threshold ? loan_amount : 0;
            tax_expense = Math.abs((profit_before_tax - write_off) * tax_rate);
        } else {
            tax_expense = profit_before_tax * tax_rate;
        }
        let profit_after_tax = Math.round(profit_before_tax - tax_expense);

        if (year === 1) {
            year_results[year]['profit'] = profit_after_tax;
        } else {
            year_results[year]['profit'] = year_results[year-1]['profit'] + profit_after_tax;
        }

        //4
        let cashflow_from_financing = 0;
        if (is_mside_method) {
            cashflow_from_financing = year === 1 && interest_only_for_first_year ? 0 : getPrincipalForYear(year);
        }

        let cashflow = profit_after_tax - cashflow_from_financing;
        year_results[year]['cashflow'] = cashflow;

        //3
        let prev_year_cash = 0;
        if (year > 1) {
            prev_year_cash = year_results[year-1]['cash'] ;
        }

        let cash = prev_year_cash + cashflow;
        year_results[year]['cash'] = cash;
        let assets = cash + loan_amount;

        let borrowings = 0;
        if (is_mside_method) {
            borrowings = getPrincipalBalanceForYear(year);
        }

        let net_assets = assets - borrowings;

        year ++;
    }

    return year_results;
}


function execute(data) {


    if(validate(data)) {

        loan_amount = data['loan_amount'];
        original_loan_amount = data['loan_amount'];
        term_years = data['term_years'];
        interest = data['interest'];
        terms = data['terms'];

        if (data['interest_only_for_first_year']) {
            interest_free_months = 12;
        }

        month = 0;

        terms = terms === 0 ? 1 : terms;

        period = terms * term_years;
        interest = (interest/100) / terms;

        results = {
            'inputs': data,
            'summary': getSummary(),
            'schedule': getSchedule()
        };

        year_totals = {};

        for (index = 0; index < results['schedule'].length; ++index) {
            let current_month = results['schedule'][index];

            let current_year = parseInt((index/12) + 1);

            if (!year_totals[current_year]) {
                year_totals[current_year] = {'interest': 0, 'principal': 0};
            }

            year_totals[current_year]['interest'] += current_month['interest'];
            year_totals[current_year]['principal'] += current_month['principal'];

        }


        for (year = 1; year <= term_years; ++year) {

            if (year === 1) {
                year_totals[year]['principal_balance'] = original_loan_amount - year_totals[year]['principal'];
            } else {
                year_totals[year]['principal_balance'] = (year_totals[year-1]['principal_balance']) - year_totals[year]['principal'];
            }
        }

        results['year_totals'] = year_totals;

    }
}

function validate(data) {

    if (!data['loan_amount'] || !data['term_years'] || !data['interest'] || !data['terms']) {
        return false;
    }
    return true;
}

function calculate() {

    if (!interest_free_months || month > interest_free_months) {
        let deno = 1 - 1 / Math.pow((1+ interest), period);
        term_pay = (loan_amount * interest) / deno;
    }

    let _interest = loan_amount * interest;

    if (!interest_free_months || month > interest_free_months) {
        principal = term_pay - _interest;
    }

    balance = loan_amount;
    if (principal) {
        balance = balance - principal;
    }

    month ++;
    return {
        'payment': term_pay,
        'interest': _interest,
        'principal':principal,
        'balance': balance
    };

}

function getSummary()  {

    calculate();
    let total_pay = term_pay * period;
    let total_interest = total_pay - loan_amount;

    return {
        'total_pay': total_pay,
        'total_interest': total_interest,
    };
}

 function getSchedule() {

    let schedule = [];

    while (balance >= 0) {
        schedule.push(calculate())
        loan_amount = balance;
        period--;
    }

    return schedule;

}
