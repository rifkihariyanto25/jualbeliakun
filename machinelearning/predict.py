import sys
import json
import pickle
import numpy as np
import pandas as pd


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
    rank, score, koleksi, jumlah_skin, score_kolektor, winrate, total_match
):
    """
    Predict account price based on features

    Parameters:
    - rank: Rank tertinggi (e.g., "Epic III", "Legend I", "Mythic (19 Bintang)")
    - score: Score rank (1-4)
    - koleksi: Koleksi level (e.g., "Kolektor Ternama IV")
    - jumlah_skin: Number of skins
    - score_kolektor: Collector score
    - winrate: Overall winrate percentage
    - total_match: Total matches played
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
            koleksi_encoded = le_koleksi.transform(["Unknown"])[0]

        # Prepare features in correct order
        features = np.array(
            [
                [
                    rank_encoded,
                    float(score),
                    koleksi_encoded,
                    float(jumlah_skin),
                    float(score_kolektor),
                    float(winrate),
                    float(total_match),
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
                "score": score,
                "koleksi": koleksi,
                "jumlah_skin": jumlah_skin,
                "score_kolektor": score_kolektor,
                "winrate": winrate,
                "total_match": total_match,
            },
        }

    except Exception as e:
        return {"success": False, "error": str(e)}


if __name__ == "__main__":
    # Check if called with arguments (for CLI usage)
    if len(sys.argv) > 1:
        # Parse JSON input from command line
        try:
            input_data = json.loads(sys.argv[1])

            result = predict_price(
                rank=input_data.get("rank", ""),
                score=input_data.get("score", 1),
                koleksi=input_data.get("koleksi", "Unknown"),
                jumlah_skin=input_data.get("jumlah_skin", 0),
                score_kolektor=input_data.get("score_kolektor", 0),
                winrate=input_data.get("winrate", 50.0),
                total_match=input_data.get("total_match", 0),
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
            score=2,
            koleksi="Kolektor Ternama IV",
            jumlah_skin=227,
            score_kolektor=24,
            winrate=66.34,
            total_match=6523,
        )
        print(json.dumps(result, indent=2))
