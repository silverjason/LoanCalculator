
let loan_amount;
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



function getInterestForYear(year) {
    return results['year_totals'][year]['interest'];
}

function getPrincipalForYear(year) {
    return results['year_totals'][year]['principal'];
}

function getPrincipalBalanceForYear(year) {
    let principal_balance = results['year_totals'][year]['principal_balance'];
    return principal_balance > 0 ? principal_balance : 0;
}

function execute(data) {

    debugger;

    if(validate(data)) {

        loan_amount = data['loan_amount'];
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

            let current_year = intval((index/12) + 1);

            if (!year_totals[current_year]) {
                year_totals[current_year] = {'interest': 0, 'principal': 0};
            }

            year_totals[current_year]['interest'] = current_month['interest'];
            year_totals[current_year]['principal'] = current_month['principal'];

        }

        for (index = 0; index < year_totals.length; ++index) {

            if (year_totals[index-1]['principal_balance']) {

                year_totals[index]['principal_balance'] = (year_totals[index-1]['principal_balance']) - year_totals[index]['principal'];

            } else {
                year_totals[index]['principal_balance'] = loan_amount - year_totals[index]['principal'];

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

    if (!interest_free_months && month > interest_free_months) {
        let deno = 1 - 1 / pow((1+ interest), period);
        term_pay = (loan_amount * interest) / deno;
    }

    let _interest = loan_amount * interest;

    if (!interest_free_months && month > interest_free_months) {
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
