<?php 

function getBitIndexValue(int $index): int {
    return pow(2, $index);
}

function getStepsByBit(array $bits): array {
    $stepsByBit = array_map(
        function($bit, $key) {
            if ($bit === '0') {
                return 0;
            }

            return (getBitIndexValue($key) * 2) -1;
        }, 
        $bits, 
        array_keys($bits)
    );

    return array_values(
        array_filter(
            array_reverse($stepsByBit),
            function($value) {
                return $value;
            }
        )
    );
}

function minOperations($n): int {
    if ($n === 0) {
        return 0;
    }

    $binary = decbin($n);

    if (strlen($binary) === 1) {
        return 1;
    }

    $bits = str_split(strrev($binary));
    $stepsByBit = getStepsByBit($bits);

    $steps = array_map(
        function($step, $key) {
            if ($key % 2 === 0) {
                return $step;
            }

            return $step * -1;
        },
        $stepsByBit,
        array_keys($stepsByBit)
    );

    return array_reduce($steps, function($operations, $step) {
        return $operations += $step;
    }, 0);
}

for($i = 2; $i <= 1000; $i ++) {
    $steps = minOperations($i);
    echo  "Number {$i} in {$steps} steps<br>";
}
