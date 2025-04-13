<?php

function generateCompanyCode($companyName) {
    $companyName = strtoupper(preg_replace('/[^A-Za-z]/', '', $companyName)); // Remove non-alphabetic characters and uppercase
    $vowels = ['A', 'E', 'I', 'O', 'U'];
    $vowelChars = [];
    $consonantChars = [];

    foreach (str_split($companyName) as $char) {
        if (in_array($char, $vowels)) {
            $vowelChars[] = $char;
        } else {
            $consonantChars[] = $char;
        }
    }

    $code = '';
    $vowelIndex = 0;
    $consonantIndex = 0;

    for ($i = 0; $i < 4; $i++) {
        if ($vowelIndex < count($vowelChars)) {
            $code .= $vowelChars[$vowelIndex];
            $vowelIndex++;
        } elseif ($consonantIndex < count($consonantChars)) {
            $code .= $consonantChars[$consonantIndex];
            $consonantIndex++;
        } else {
            // If both vowel and consonant lists are exhausted, add 'X' or some other filler.
            $code .= 'X';
        }
    }

    return $code;
}

// Example usage:
$companyName1 = "Example Company";
$companyName2 = "Innovation Tech";
$companyName3 = "ABC Corporation";
$companyName4 = "Data Solutions";
$companyName5 = "My Amazing Company";
$companyName6 = "The Quick Brown Fox";

echo "Company Name: " . $companyName1 . ", Code: " . generateCompanyCode($companyName1) . "<br>";
echo "Company Name: " . $companyName2 . ", Code: " . generateCompanyCode($companyName2) . "<br>";
echo "Company Name: " . $companyName3 . ", Code: " . generateCompanyCode($companyName3) . "<br>";
echo "Company Name: " . $companyName4 . ", Code: " . generateCompanyCode($companyName4) . "<br>";
echo "Company Name: " . $companyName5 . ", Code: " . generateCompanyCode($companyName5) . "<br>";
echo "Company Name: " . $companyName6 . ", Code: " . generateCompanyCode($companyName6) . "<br>";

?>