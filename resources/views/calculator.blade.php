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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        body, input, textarea, button, select {
            font-size: 16px;
            font-family: Karla,sans-serif;
            font-style: normal;
            font-weight: 400;
            /* color: #666; */
            line-height: 1.375;
        }

        .content {
            width: 70%;
            margin: auto;
            padding: 50px;
            max-width: 800px;
        }

        .questions {
            text-align: initial;
            width: 41%;
            margin: auto;
        }

        .graph-container {
            margin: 30px;
        }

        #profit-chart {
            padding-top: 10px;
        }

        .question {
            height: 28px;
        }

        .chart-legend ul {
            list-style: none;
            background: black;
            color: white;
        }

        .chart-legend li {
            display: inline-block;
        }

        .legend {
            margin: auto;
            display: block;
            width: 700px;
        }

        li {
            text-align: initial;
        }

        ul {
            list-style-type: none;
        }

        h2 {
            font-size: x-large;
            font-weight: 300;
            margin: 0;
        }

        h3 {
            font-weight: 100;
            margin: 0;
        }

        #profit-heading {
            word-break: break-word;
            width: 270px;
        }

        .results {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
        }

        .result-heading {
            color: rgb(3, 109, 255);
            word-break: break-word;
            width: 130px;
            font-size: medium;
        }

        .result-value {
            font-size: xx-large;
        }

        .result {
            margin-right: 40px;
        }

    </style>



</head>
<body>
<div class="flex-center position-ref full-height">


    <div class="content">

        <h1>Mchairside Cashflow and Profit Calculator</h1>

        <div class="questions">

            <div class="question">
                <span>What do you bill per crown?</span>
                <input id="income_per_crown"  value="1600" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" step="25" type="number">
            </div>
            <div class="question">
                <span>How many crowns do you do per week?</span>
                <input id="crowns_per_week"  value="5" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div class="question">
                <span>What is the lab fee per crown?</span>
                <input id="lab_fee"  value="300" maxlength="3" class="form-control" placeholder="0" min="0" max="999" type="number">
            </div>
            <div class="question">
                <span>Mchairside purchase price</span>
                <input id="loan_amount"  disabled value="99000" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div class="question">
                <span>Loan term (years)</span>
                <input id="term"  value="5" maxlength="7" class="form-control" placeholder="0" min="0" max="7" type="number">
            </div>
            <div class="question">
                <span>Interest rate %</span>
                <input id="interest_rate" class="form-control" type="number" value="3.89" min="0" max="10" maxlength="3" step="0.01" />
            </div>
            <div class="question">
                <span>Months of further impact from COVID-19 (assuming zero revenue)</span>
                <input id="months_impacted"  value="0" maxlength="8" class="form-control" placeholder="0" min="0" max="99999999" type="number">
            </div>
            <div class="question">
                <span>Are you taking advantage of us paying your interest for 12 months?</span>
                <input id="no_interest_in_first_year" class="form-control" placeholder="0" type="checkbox">
            </div>
            <div class="question">
                <span>Are you purchasing prior to 30 June to claim full deduction?</span>
                <input id="claim_instant_threshold" class="form-control" placeholder="0" type="checkbox">
            </div>
            <div class="question">
                <span>Are you taxed at the corporate or individual rate?</span>

                <select id="tax_type">
                    <option value="corporate_tax" selected>Corporate</option>
                    <option value="individual_tax">Individual</option>
                </select>

            </div>

            <button type="button" onclick="calculateLoan()">Calculate</button>

        </div>

        <div class="graph-container">
            <canvas id="cashflow-chart" class="loan-chart" width="700" height="400" style="margin: auto;"></canvas>
            <div id="cash_legend" class="legend"></div>
        </div>

        <h2 id="profit-heading">Cumulative Annual Profit Comparison</h2>
        <h3>for 7 years</h3>

        <div class="graph-container">
            <canvas id="profit-chart" class="loan-chart" width="700" height="400" style="margin: auto;"></canvas>
        </div>

        <div class="results">
            <div class="result">
                <div class="result-heading">Cumulative profit with Mchairside</div>
                <div class="result-value">$1000000</div>
            </div>

            <div class="result">
                <div class="result-heading">Cumulative profit with Mchairside</div>
                <div class="result-value">$1000000</div>
            </div>

        </div>


    </div>

</div>
</body>

</html>
