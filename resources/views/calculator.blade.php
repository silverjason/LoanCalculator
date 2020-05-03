<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Loan Calculator</title>

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

        .loan-chart {
            display: inline !important;;
        }

        .questions {
            text-align: initial;
            width: 35%;
            margin: auto;
        }

        .graph-container {
            margin-top: 20px;
        }

    </style>



</head>
<body>
<div class="flex-center position-ref full-height">


    <div class="content">

        <h1>Mchairside Cashflow and Profit Calculator</h1>

        <div class="questions">

            <div>
                <span>What do you bill per crown?</span>
                <input id="income_per_crown"  value="1600" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" step="25" type="number">
            </div>
            <div>
                <span>How many crowns do you do per week?</span>
                <input id="crowns_per_week"  value="8" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div>
                <span>What is the lab fee per crown?</span>
                <input id="lab_fee"  value="300" maxlength="3" class="form-control" placeholder="0" min="0" max="999" type="number">
            </div>
            <div>
                <span>Mchairside purchase price</span>
                <input id="loan_amount"  disabled value="99000" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div>
                <span>Loan term (years)</span>
                <input id="term"  value="5" maxlength="7" class="form-control" placeholder="0" min="0" max="7" type="number">
            </div>
            <div>
                <span>Interest rate %</span>
                <input id="interest_rate" class="form-control" type="number" value="3.89" min="0" max="10" maxlength="3" step="0.01" />
            </div>
            <div>
                <span>Months of further impact from COVID-19 (assuming zero revenue)</span>
                <input id="months_impacted"  value="0" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div>
                <span>Are you taking advantage of us paying your interest for 12 months?</span>
                <input id="no_interest_in_first_year" class="form-control" placeholder="0" type="checkbox">
            </div>
            <div>
                <span>Are you purchasing prior to 30 June to claim full deduction?</span>
                <input id="claim_instant_threshold" class="form-control" placeholder="0" type="checkbox">
            </div>
            <div>
                <span>Are you taxed at the corporate or individual rate?</span>

                <select id="tax_type">
                    <option value="corporate_tax" selected>Corporate</option>
                    <option value="individual_tax">Individual</option>
                </select>

            </div>

            <button type="button" onclick="calculateLoan()">Calculate</button>

        </div>

        <div class="graph-container">
            <canvas id="cashflow-chart" class="loan-chart" width="400" height="400" style="margin: auto;"></canvas>
            <canvas id="profit-chart" class="loan-chart" width="400" height="400" style="margin: auto;"></canvas>
        </div>

    </div>

</div>
</body>

</html>
