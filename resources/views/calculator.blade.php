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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

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

        <h1>Title</h1>

        <div class="ct-chart ct-perfect-fourth"></div>


    </div>
</div>
</body>

<script>
    var data = {
        // A labels array that can contain any sort of values
        labels: [0, 1, 2, 3, 4, 5],
        // Our series array that contains series objects or in this case series data arrays
        series: [
            [5, 2, 4, 2, 0, 3]
        ]
    };

    // Create a new line chart object where as first parameter we pass in a selector
    // that is resolving to our chart container element. The Second parameter
    // is the actual data object.
    new Chartist.Line('.ct-chart', data);
</script>
</html>
