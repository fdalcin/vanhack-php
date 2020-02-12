<?php

function makeInvestorPair(...$arrays) {
    $output = [];

    foreach($arrays as $array) {
        foreach($array as $key => $value) {
            $output[$key] = isset($output[$key])
                ? array_merge($output[$key], [$value])
                : [$value];
        }
    }

    return $output;
}

function getInvestorsSortedByDeparture(array $investors): array {
    uasort($investors, 'sortByDeparture');

    return array_values($investors);
}

function sortByDeparture($first, $second) {
    if ($first[1] === $second[1]) {
        return 0;
    }

    if ($first[1] > $second[1]) {
        return 1;
    }

    if ($first[1] < $second[1]) {
        return -1;
    }
}

function getArrival(array $investor): int {
    return $investor[0];
}

function getDeparture(array $investor): int {
    return $investor[1];
}

function countMeetings($arrival, $departure) {
    $investors = getInvestorsSortedByDeparture(
        makeInvestorPair($arrival, $departure)
    );

    $size = count($investors);

    // since no investor was found simply return 0 meetings
    if(!$size) {
        return 0;
    }

    $firstInvestor = $investors[0];
    $meetings[getArrival($firstInvestor)] = 1;

    for($i = 1; $i < $size; $i++) {
        $meetingDay = getArrival($investors[$i]);
        $departureAt = getDeparture($investors[$i]);

        while($meetingDay <= $departureAt) {
            if(!isset($meetings[$meetingDay])) {
                $meetings[$meetingDay] = 1;
                break;
            }

            $meetingDay++;
        }
    }

    return count($meetings);
}

$arrivals = [1, 1, 2];
$departures = [1, 2, 2];

echo countMeetings($arrivals, $departures) . " meetings<br><br>";

$arrivals = [1, 2, 1, 2, 2];
$departures = [3, 2, 1, 3, 3];

echo countMeetings($arrivals, $departures) . " meetings<br><br>";

$arrivals = [1, 2, 3, 3, 3];
$departures = [2, 2, 3, 4, 4];

echo countMeetings($arrivals, $departures) . " meetings<br><br>";

$arrivals = [1, 10, 11];
$departures = [11, 10, 11];

echo countMeetings($arrivals, $departures) . " meetings<br><br>";

$arrivals = [363, 582, 962, 189, 122, 1053, 721, 998, 80, 601, 1007, 1039, 1029, 206, 371, 1275, 1679, 571, 563, 1006, 498, 1127, 1322, 1066, 1575, 1058, 399, 155, 468, 956, 883, 1988, 1216, 314, 54, 2040, 1377, 1838, 195, 1137, 1009, 2405, 1190, 2414, 557, 2391, 186,];
$departures = [386, 994, 1030, 1071, 1088, 1094, 1113, 1168, 1267, 1283, 1297, 1301, 1347, 1393, 1408, 1688, 1913, 1920, 1993, 2005, 2116, 2117, 2125, 2200, 2209, 2231, 2244, 2248, 2256, 2352, 2399, 2413, 2422, 2474, 2482, 2545, 2609, 2657, 2675, 2739, 2761, 2798, 2802, 2804, 2923, 2923, 2976,];

echo countMeetings($arrivals, $departures) . " meetings<br><br>";
