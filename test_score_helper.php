<?php

require 'vendor/autoload.php';
require 'app/Helpers/ScoreHelper.php';

use App\Helpers\ScoreHelper;

// Test data
$testData = [
    'rank' => 'Legend I',
    'koleksi' => 'Kolektor Ternama IV',
    'winrate' => 66.34,
    'total_match' => 6523
];

echo "Testing ScoreHelper...\n";
echo "Input:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

$scores = ScoreHelper::calculateAllScores($testData);

echo "Calculated Scores:\n";
echo json_encode($scores, JSON_PRETTY_PRINT) . "\n\n";

echo "Individual scores:\n";
echo "- score_rank: {$scores['score_rank']}\n";
echo "- score_koleksi: {$scores['score_koleksi']}\n";
echo "- score_winrate: {$scores['score_winrate']}\n";
echo "- score_total_match: {$scores['score_total_match']}\n";
