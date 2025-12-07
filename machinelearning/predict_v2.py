import sys
import json
import pickle
import numpy as np
import pandas as pd
import warnings

# Suppress sklearn warnings
warnings.filterwarnings("ignore")


def load_model():
    """Load trained model and encoders"""
    try:
        with open("rf_model.pkl", "rb") as f:
            model = pickle.load(f)

        with open("encoders.pkl", "rb") as f:
            encoders = pickle.load(f)

        with open("model_metadata.json", "r") as f:
            metadata = json.load(f)

        return model, encoders, metadata
    except Exception as e:
        return {"error": f"Failed to load model: {str(e)}"}


def predict_price(
    rank,
    score_rank,
    koleksi,
    jumlah_skin,
    score_koleksi,
    winrate,
    score_winrate,
    total_match,
    score_total_match,
):
    """
    Predict account price based on features (Raw + Score)

    Parameters:
    - rank: Rank tertinggi (e.g., "Epic III", "Legend I", "Mythic (19 Bintang)")
    - score_rank: Score rank (1-7)
    - koleksi: Koleksi level (e.g., "Kolektor Ternama IV")
    - jumlah_skin: Number of skins
    - score_koleksi: Collector score (1-36)
    - winrate: Overall winrate percentage
    - score_winrate: Winrate score (1-6)
    - total_match: Total matches played
    - score_total_match: Total match score (1-6)
    """
    try:
        # Load model
        model, encoders, metadata = load_model()

        if isinstance(model, dict) and "error" in model:
            return model

        le_rank = encoders["le_rank"]
        le_koleksi = encoders["le_koleksi"]

        # Encode categorical features
        try:
            rank_encoded = le_rank.transform([rank])[0]
        except:
            # If rank not in training data, use most common or default
            rank_encoded = 0

        try:
            koleksi_encoded = le_koleksi.transform([koleksi])[0]
        except:
            # If koleksi not in training data, use 'Unknown'
            try:
                koleksi_encoded = le_koleksi.transform(["Unknown"])[0]
            except:
                koleksi_encoded = 0

        # Prepare features in correct order: 9 features (Raw + Score)
        features = np.array(
            [
                [
                    rank_encoded,
                    float(score_rank),
                    koleksi_encoded,
                    float(jumlah_skin),
                    float(score_koleksi),
                    float(winrate),
                    float(score_winrate),
                    float(total_match),
                    float(score_total_match),
                ]
            ]
        )

        # Make prediction
        prediction = model.predict(features)[0]

        # Return result
        return {
            "success": True,
            "predicted_price": float(prediction),
            "predicted_price_formatted": f"Rp {prediction:,.0f}",
            "input_features": {
                "rank": rank,
                "score_rank": score_rank,
                "koleksi": koleksi,
                "jumlah_skin": jumlah_skin,
                "score_koleksi": score_koleksi,
                "winrate": winrate,
                "score_winrate": score_winrate,
                "total_match": total_match,
                "score_total_match": score_total_match,
            },
        }

    except Exception as e:
        return {"success": False, "error": str(e)}


if __name__ == "__main__":
    # Check if called with arguments (for CLI usage)
    if len(sys.argv) > 1:
        # Parse JSON input from command line or file
        try:
            arg = sys.argv[1]

            # Check if argument is a file reference (starts with @)
            if arg.startswith("@"):
                filename = arg[1:]
                with open(filename, "r") as f:
                    input_data = json.load(f)
            else:
                # Parse from command line argument
                input_data = json.loads(arg)

            result = predict_price(
                rank=input_data.get("rank", ""),
                score_rank=input_data.get("score_rank", 1),
                koleksi=input_data.get("koleksi", "Unknown"),
                jumlah_skin=input_data.get("jumlah_skin", 0),
                score_koleksi=input_data.get("score_koleksi", 0),
                winrate=input_data.get("winrate", 50.0),
                score_winrate=input_data.get("score_winrate", 3),
                total_match=input_data.get("total_match", 0),
                score_total_match=input_data.get("score_total_match", 3),
            )

            # Print JSON output
            print(json.dumps(result))

        except Exception as e:
            print(json.dumps({"success": False, "error": str(e)}))
    else:
        # Example usage
        print("Example prediction:")
        result = predict_price(
            rank="Legend I",
            score_rank=2,
            koleksi="Kolektor Ternama IV",
            jumlah_skin=227,
            score_koleksi=24,
            winrate=66.34,
            score_winrate=5,
            total_match=6523,
            score_total_match=1,
        )
        print(json.dumps(result, indent=2))
