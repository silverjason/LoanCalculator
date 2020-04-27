<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <script src="/calculator.js"></script>

    {{--    Chart.js--}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .content {
            text-align: center;
        }


    </style>



</head>
<body>
<div class="flex-center position-ref full-height">


    <div class="content">

        <h1>Loan Calculator</h1>

        <div>
            <span>Income Per Crown</span>
            <input id="income_per_crown"  value="1600" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Crowns Per Week</span>
            <input id="crowns_per_week"  value="8" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Loan Amount</span>
            <input id="loan_amount"  value="100000" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Term (years)</span>
            <input id="term"  value="5" maxlength="7" class="form-control" placeholder="0" min="0" max="7" type="number">
        </div>
        <div>
            <span>Interest %</span>
            <input id="interest_rate"  value="4.5" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Payments Per Year</span>
            <input id="payments_per_year"  value="12" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Months Impacted by Covid</span>
            <input id="months_impacted"  value="0" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
        </div>
        <div>
            <span>Interest Only for 1st Year</span>
            <input id="interest_only_for_first_year"  class="form-control" placeholder="0" type="checkbox">
        </div>
        <div>
            <span>Interest Free for 1st Year</span>
            <input id="no_interest_in_first_year" class="form-control" placeholder="0" type="checkbox">
        </div>
        <div>
            <span>Claim threshold</span>
            <input id="claim_instant_threshold" class="form-control" placeholder="0" type="checkbox">
        </div>
        <div>
            <span>Tax as Company</span>
            <input id="is_company" class="form-control" placeholder="0" type="checkbox">
        </div>

        <button type="button" onclick="calculateLoan()">Calculate</button>

        <div id="graph-container">
            <canvas id="myChart" width="700" height="500" style="margin: auto;"></canvas>

        </div>


    </div>
</div>
</body>

</html>
