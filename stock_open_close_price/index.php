<?php

function createDateTimeFromFormat($string, $format = 'j-F-Y') {
    return DateTime::createFromFormat($format, $string);
}

function getStocks($query) {
    $response = file_get_contents("https://jsonmock.hackerrank.com/api/stocks/search?date={$query}");
    
    return json_decode($response);
}

function getStocksFromRange(array $range) {
    $stocks = [];

    foreach($range as $query) {
        $response = getStocks($query);

        $stocks = array_merge($stocks, $response->data);
    }

    return $stocks;
}

function shouldFilterByYear($months) {
    // defined 6 as minimum months to consider filtering by year
    // this should reduce hits to the api.
    return count($months) > 6;
}

function validateStockDate($date, $startDate, $endDate, $dayOfWeek) {
    $stockDate = createDateTimeFromFormat($date);

    if ($stockDate < $startDate) {
        return false;
    }

    if ($stockDate > $endDate) {
        return false;
    }

    if ($stockDate->format('l') !== $dayOfWeek) {
        return false;
    }

    return true;
}

function getRange($startDate, $endDate) {
    $monthsPerYear = [];
    $range = [];

    $date = clone $startDate;

    while($date <= $endDate) {
        $monthsPerYear[$date->format('Y')][] = $date->format('F-Y');

        $date->modify('+1 month');
    }

    foreach(array_keys($monthsPerYear) as $year) {
        if (shouldFilterByYear($monthsPerYear[$year])) {
            $range[] = $year;
            continue;
        }
        
        foreach($monthsPerYear[$year] as $month) {
            $range[] = $month;
        }
    }

    return $range;
}

function test($firstDate, $secondDate, $dayOfWeek) {
    $startDate = createDateTimeFromFormat($firstDate);
    $endDate = createDateTimeFromFormat($secondDate);

    $stocks = getStocksFromRange(
        getRange($startDate, $endDate)
    );

    $output = "";

    foreach($stocks as $stock) {
        if (! validateStockDate($stock->date, $startDate, $endDate, $dayOfWeek)) {
            continue;
        }

        $output .= "{$stock->date} {$stock->open} {$stock->close}<br>";
    }

    echo $output;
}

test('1-January-2000', '22-February-2000', 'Monday');
echo "<br>";
test('26-March-2001', '15-August-2001', 'Wednesday');
echo "<br>";
test('1-March-2000', '15-August-2001', 'Wednesday');
