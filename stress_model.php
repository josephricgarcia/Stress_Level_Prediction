<?php
/**
 * Stress Prediction Functions for Infinity Free
 * This file replaces the Flask API and works without Python
 */

/**
 * Main prediction function - can be called directly or via API
 */
function predictStress($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa) {
    // Load model parameters from JSON file (we'll create this)
    $modelParams = null;
    if (file_exists('model_params.json')) {
        $modelParams = json_decode(file_get_contents('model_params.json'), true);
    }

    // If we have model parameters, use them for prediction
    if ($modelParams) {
        return predictWithModel($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa, $modelParams);
    } else {
        // Fallback to rule-based prediction if model params not available
        return predictWithRules($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa);
    }
}

// If called as API endpoint (via curl or direct request)
if (php_sapi_name() !== 'cli' && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is an API call by looking for JSON input or specific headers
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    
    if (strpos($contentType, 'application/json') !== false) {
        header('Content-Type: application/json');
        
        // Get POST data
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        $studyhours = floatval($input['studyhours'] ?? 0);
        $hobbyhours = floatval($input['hobbyhours'] ?? 0);
        $sleephours = floatval($input['sleephours'] ?? 0);
        $socialhours = floatval($input['socialhours'] ?? 0);
        $activehours = floatval($input['activehours'] ?? 0);
        $gwa = floatval($input['gwa'] ?? 0);

        $prediction = predictStress($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa);
        echo json_encode($prediction);
        exit;
    }
}

/**
 * Predict using model parameters (if available)
 */
function predictWithModel($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa, $modelParams) {
    // Scale features using stored scaler parameters
    $mean = $modelParams['scaler']['mean'] ?? [0, 0, 0, 0, 0, 0];
    $scale = $modelParams['scaler']['scale'] ?? [1, 1, 1, 1, 1, 1];
    
    $features = [$studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa];
    $scaled = [];
    for ($i = 0; $i < 6; $i++) {
        if ($scale[$i] != 0) {
            $scaled[] = ($features[$i] - $mean[$i]) / $scale[$i];
        } else {
            $scaled[] = 0;
        }
    }
    
    $modelType = $modelParams['model_type'] ?? 'unknown';
    $labels = $modelParams['labels'] ?? ['Low', 'Moderate', 'High'];
    
    // For linear models (Logistic Regression, Linear Regression, etc.)
    if (isset($modelParams['weights'])) {
        $score = 0;
        $score += $scaled[0] * ($modelParams['weights']['studyhours'] ?? 0);
        $score += $scaled[1] * ($modelParams['weights']['hobbyhours'] ?? 0);
        $score += $scaled[2] * ($modelParams['weights']['sleephours'] ?? 0);
        $score += $scaled[3] * ($modelParams['weights']['socialhours'] ?? 0);
        $score += $scaled[4] * ($modelParams['weights']['activehours'] ?? 0);
        $score += $scaled[5] * ($modelParams['weights']['gwa'] ?? 0);
        $score += ($modelParams['bias'] ?? 0);
        
        // Map score to stress level
        if ($score < -0.5) {
            $stress_level = 'Low';
            $confidence = max(0.7, min(0.95, abs($score) / 3));
        } elseif ($score < 0.5) {
            $stress_level = 'Moderate';
            $confidence = max(0.6, min(0.9, abs($score)));
        } else {
            $stress_level = 'High';
            $confidence = max(0.7, min(0.95, $score / 3));
        }
        
        return [
            'stress_level' => $stress_level,
            'confidence' => $confidence
        ];
    }
    // For tree-based models (Random Forest, Decision Tree, etc.)
    elseif (isset($modelParams['feature_importances'])) {
        // Use feature importances to weight the rule-based prediction
        $importances = $modelParams['feature_importances'];
        return predictWithRules($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa);
    }
    // Fallback to rule-based
    else {
        return predictWithRules($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa);
    }
}

/**
 * Rule-based prediction fallback
 * This uses stress logic to predict based on input features
 */
function predictWithRules($studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa) {
    $stress_score = 0;
    
    // Study hours: too much or too little increases stress
    if ($studyhours > 10) $stress_score += 3;
    elseif ($studyhours > 8) $stress_score += 2;
    elseif ($studyhours < 4) $stress_score += 1;
    
    // Hobby hours: more hobbies reduce stress
    if ($hobbyhours < 1) $stress_score += 2;
    elseif ($hobbyhours < 2) $stress_score += 1;
    else $stress_score -= 1;
    
    // Sleep hours: insufficient sleep increases stress
    if ($sleephours < 5) $stress_score += 3;
    elseif ($sleephours < 6) $stress_score += 2;
    elseif ($sleephours < 7) $stress_score += 1;
    elseif ($sleephours >= 8) $stress_score -= 1;
    
    // Social hours: both extremes can be stressful
    if ($socialhours < 1) $stress_score += 2;
    elseif ($socialhours > 6) $stress_score += 1;
    elseif ($socialhours >= 2 && $socialhours <= 4) $stress_score -= 1;
    
    // Active hours: moderate activity reduces stress
    if ($activehours < 0.5) $stress_score += 2;
    elseif ($activehours >= 1 && $activehours <= 3) $stress_score -= 1;
    elseif ($activehours > 4) $stress_score += 1;
    
    // GWA: lower grades increase stress
    if ($gwa > 3.0) $stress_score += 2;
    elseif ($gwa > 2.5) $stress_score += 1;
    elseif ($gwa < 1.5) $stress_score -= 1;
    
    // Determine stress level based on total score
    if ($stress_score <= 3) {
        $stress_level = 'Low';
        $confidence = max(0.7, 0.7 + ($stress_score / 30));
    } elseif ($stress_score <= 7) {
        $stress_level = 'Moderate';
        $confidence = max(0.6, 0.6 + (($stress_score - 3) / 20));
    } else {
        $stress_level = 'High';
        $confidence = max(0.7, 0.7 + (($stress_score - 7) / 30));
    }
    
    return [
        'stress_level' => $stress_level,
        'confidence' => min(0.95, $confidence) // Cap confidence at 95%
    ];
}
?>