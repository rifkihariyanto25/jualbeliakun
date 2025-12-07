<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ScoreHelper;

class PredictionController extends Controller
{
    /**
     * Show the price prediction form
     */
    public function index()
    {
        return view('prediction.index');
    }

    /**
     * Predict account price using ML model
     */
    public function predict(Request $request)
    {
        // Validate input - User only inputs 5 fields
        $validator = Validator::make($request->all(), [
            'rank' => 'required|string',
            'koleksi' => 'nullable|string',
            'jumlah_skin' => 'required|integer|min:0',
            'winrate' => 'required|numeric|min:0|max:100',
            'total_match' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Auto-calculate all scores using ScoreHelper
            $scores = ScoreHelper::calculateAllScores([
                'rank' => $request->rank,
                'koleksi' => $request->koleksi,
                'winrate' => (float)$request->winrate,
                'total_match' => (int)$request->total_match,
            ]);

            // Prepare input data with all 9 features (Raw + Score)
            $inputData = [
                'rank' => $request->rank,
                'score_rank' => $scores['score_rank'],
                'koleksi' => $request->koleksi ?? 'Unknown',
                'jumlah_skin' => (int)$request->jumlah_skin,
                'score_koleksi' => $scores['score_koleksi'],
                'winrate' => (float)$request->winrate,
                'score_winrate' => $scores['score_winrate'],
                'total_match' => (int)$request->total_match,
                'score_total_match' => $scores['score_total_match'],
            ];

            // Call Python prediction script
            $result = $this->callPythonPredictor($inputData);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'prediction' => $result['predicted_price'],
                    'formatted_price' => 'Rp ' . number_format($result['predicted_price'], 0, ',', '.'),
                    'input_features' => $result['input_features'],
                    'calculated_scores' => $scores  // Return calculated scores for user info
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Prediction failed'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Call Python prediction script
     */
    private function callPythonPredictor($inputData)
    {
        // Path to Python script (updated to v2)
        $pythonScript = base_path('machinelearning/predict_v2.py');
        $pythonExecutable = 'python'; // or 'python3' depending on system

        // Create temporary JSON file to avoid shell escaping issues
        $tempFile = base_path('machinelearning/temp_input.json');
        file_put_contents($tempFile, json_encode($inputData));

        // Build command - read from temp file
        $command = "cd " . base_path('machinelearning') . " && {$pythonExecutable} predict_v2.py @temp_input.json";

        // Execute command
        $output = shell_exec($command . ' 2>&1');

        // Clean up temp file
        @unlink($tempFile);

        // Extract JSON from output (filter warnings)
        // Look for the last line that starts with { and ends with }
        $lines = explode("\n", trim($output));
        $jsonOutput = null;

        foreach (array_reverse($lines) as $line) {
            $trimmedLine = trim($line);
            if (str_starts_with($trimmedLine, '{') && str_ends_with($trimmedLine, '}')) {
                $jsonOutput = $trimmedLine;
                break;
            }
        }

        if (!$jsonOutput) {
            return [
                'success' => false,
                'error' => 'No valid JSON output found. Raw output: ' . substr($output, 0, 500)
            ];
        }

        // Parse JSON output
        $result = json_decode($jsonOutput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Failed to parse prediction result: ' . json_last_error_msg()
            ];
        }

        return $result;
    }

    /**
     * Get available rank options
     */
    public function getRankOptions()
    {
        $metadataPath = base_path('machinelearning/model_metadata.json');

        if (file_exists($metadataPath)) {
            $metadata = json_decode(file_get_contents($metadataPath), true);
            return response()->json([
                'success' => true,
                'ranks' => $metadata['rank_classes'] ?? [],
                'koleksi' => $metadata['koleksi_classes'] ?? []
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Metadata not found'
        ], 404);
    }
}
