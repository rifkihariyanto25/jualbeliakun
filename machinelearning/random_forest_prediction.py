import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import matplotlib.pyplot as plt
import seaborn as sns

# Load data
print("Loading data...")
df = pd.read_csv("datafixbener.csv")

# Display basic info
print(f"\nData shape: {df.shape}")
print(f"\nFirst few rows:")
print(df.head())
print(f"\nMissing values:")
print(df.isnull().sum())

# ============================================
# 1. DATA CLEANING & PREPROCESSING
# ============================================

print("\n" + "=" * 50)
print("STEP 1: Data Cleaning")
print("=" * 50)

# Clean column names (remove extra spaces)
df.columns = df.columns.str.strip()


# Clean Harga column - remove Rp, commas, dots, spaces and convert to float
def clean_price(price):
    if pd.isna(price) or price == "":
        return np.nan
    price_str = (
        str(price).replace("Rp", "").replace(",", "").replace(".", "").replace(" ", "")
    )
    try:
        return float(price_str)
    except:
        return np.nan


df["Harga_Clean"] = df["Harga"].apply(clean_price)

# Remove rows where Harga is missing (target variable)
print(f"Rows before removing missing Harga: {len(df)}")
df = df.dropna(subset=["Harga_Clean"])
print(f"Rows after removing missing Harga: {len(df)}")

# Handle missing values in features
print("\nHandling missing values...")

# Fill missing score_kolektor with median
if df["score_kolektor"].isnull().sum() > 0:
    median_score = df["score_kolektor"].median()
    df["score_kolektor"].fillna(median_score, inplace=True)
    print(f"Filled score_kolektor with median: {median_score}")

# Fill missing Koleksi with 'Unknown'
if df["Koleksi"].isnull().sum() > 0:
    df["Koleksi"].fillna("Unknown", inplace=True)
    print("Filled missing Koleksi with 'Unknown'")

# ============================================
# 2. FEATURE ENGINEERING
# ============================================

print("\n" + "=" * 50)
print("STEP 2: Feature Engineering")
print("=" * 50)

# Encode categorical variables
le_rank = LabelEncoder()
le_koleksi = LabelEncoder()

df["Rank_Encoded"] = le_rank.fit_transform(df["Rank Tertinggi"].astype(str))
df["Koleksi_Encoded"] = le_koleksi.fit_transform(df["Koleksi"].astype(str))

print(f"\nRank categories: {le_rank.classes_}")
print(f"Koleksi categories: {le_koleksi.classes_}")

# Select features for modeling
features = [
    "Rank_Encoded",
    "score",
    "Koleksi_Encoded",
    "Jumlah Skin",
    "score_kolektor",
    "Winrate keseluruhan",
    "total match",
]

# Remove rows with missing values in features
print(f"\nRows before removing missing features: {len(df)}")
df_clean = df[features + ["Harga_Clean"]].dropna()
print(f"Rows after removing missing features: {len(df_clean)}")

X = df_clean[features]
y = df_clean["Harga_Clean"]

print(f"\nFinal dataset shape: {X.shape}")
print(f"Target variable (Harga) stats:")
print(f"  Min: Rp {y.min():,.0f}")
print(f"  Max: Rp {y.max():,.0f}")
print(f"  Mean: Rp {y.mean():,.0f}")
print(f"  Median: Rp {y.median():,.0f}")

# ============================================
# 3. TRAIN-TEST SPLIT
# ============================================

print("\n" + "=" * 50)
print("STEP 3: Train-Test Split")
print("=" * 50)

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

print(f"Training set size: {len(X_train)}")
print(f"Test set size: {len(X_test)}")

# ============================================
# 4. MODEL TRAINING
# ============================================

print("\n" + "=" * 50)
print("STEP 4: Model Training")
print("=" * 50)

# Create and train Random Forest model
rf_model = RandomForestRegressor(
    n_estimators=100,
    max_depth=10,
    min_samples_split=5,
    min_samples_leaf=2,
    random_state=42,
    n_jobs=-1,
)

print("Training Random Forest model...")
rf_model.fit(X_train, y_train)
print("✓ Model training completed!")

# ============================================
# 5. MODEL EVALUATION
# ============================================

print("\n" + "=" * 50)
print("STEP 5: Model Evaluation")
print("=" * 50)

# Make predictions
y_train_pred = rf_model.predict(X_train)
y_test_pred = rf_model.predict(X_test)

# Calculate metrics
train_mae = mean_absolute_error(y_train, y_train_pred)
test_mae = mean_absolute_error(y_test, y_test_pred)

train_rmse = np.sqrt(mean_squared_error(y_train, y_train_pred))
test_rmse = np.sqrt(mean_squared_error(y_test, y_test_pred))

train_r2 = r2_score(y_train, y_train_pred)
test_r2 = r2_score(y_test, y_test_pred)

print("\n📊 TRAINING SET PERFORMANCE:")
print(f"  MAE:  Rp {train_mae:,.0f}")
print(f"  RMSE: Rp {train_rmse:,.0f}")
print(f"  R² Score: {train_r2:.4f}")

print("\n📊 TEST SET PERFORMANCE:")
print(f"  MAE:  Rp {test_mae:,.0f}")
print(f"  RMSE: Rp {test_rmse:,.0f}")
print(f"  R² Score: {test_r2:.4f}")

# ============================================
# 6. FEATURE IMPORTANCE
# ============================================

print("\n" + "=" * 50)
print("STEP 6: Feature Importance Analysis")
print("=" * 50)

# Get feature importances
feature_importance = pd.DataFrame(
    {"Feature": features, "Importance": rf_model.feature_importances_}
).sort_values("Importance", ascending=False)

print("\n🎯 FEATURE IMPORTANCE:")
for idx, row in feature_importance.iterrows():
    print(
        f"  {row['Feature']:<25} {row['Importance']:.4f} {'█' * int(row['Importance'] * 100)}"
    )

# ============================================
# 7. PREDICTION EXAMPLES
# ============================================

print("\n" + "=" * 50)
print("STEP 7: Prediction Examples")
print("=" * 50)

# Show some predictions vs actual
comparison = pd.DataFrame(
    {
        "Actual": y_test.values[:10],
        "Predicted": y_test_pred[:10],
        "Difference": y_test.values[:10] - y_test_pred[:10],
    }
)

print("\n📋 First 10 Predictions:")
for idx, row in comparison.iterrows():
    print(
        f"  Actual: Rp {row['Actual']:>12,.0f} | Predicted: Rp {row['Predicted']:>12,.0f} | Diff: Rp {row['Difference']:>10,.0f}"
    )

# ============================================
# 8. VISUALIZATION
# ============================================

print("\n" + "=" * 50)
print("STEP 8: Creating Visualizations")
print("=" * 50)

# Create figure with subplots
fig, axes = plt.subplots(2, 2, figsize=(15, 12))

# 1. Feature Importance
axes[0, 0].barh(
    feature_importance["Feature"], feature_importance["Importance"], color="steelblue"
)
axes[0, 0].set_xlabel("Importance")
axes[0, 0].set_title("Feature Importance", fontsize=14, fontweight="bold")
axes[0, 0].invert_yaxis()

# 2. Actual vs Predicted (Test Set)
axes[0, 1].scatter(y_test, y_test_pred, alpha=0.6, color="coral")
axes[0, 1].plot([y_test.min(), y_test.max()], [y_test.min(), y_test.max()], "k--", lw=2)
axes[0, 1].set_xlabel("Actual Price (Rp)")
axes[0, 1].set_ylabel("Predicted Price (Rp)")
axes[0, 1].set_title(
    "Actual vs Predicted Prices (Test Set)", fontsize=14, fontweight="bold"
)
axes[0, 1].text(
    0.05,
    0.95,
    f"R² = {test_r2:.4f}",
    transform=axes[0, 1].transAxes,
    fontsize=12,
    verticalalignment="top",
    bbox=dict(boxstyle="round", facecolor="wheat", alpha=0.5),
)

# 3. Residual Plot
residuals = y_test - y_test_pred
axes[1, 0].scatter(y_test_pred, residuals, alpha=0.6, color="green")
axes[1, 0].axhline(y=0, color="k", linestyle="--", lw=2)
axes[1, 0].set_xlabel("Predicted Price (Rp)")
axes[1, 0].set_ylabel("Residuals (Rp)")
axes[1, 0].set_title("Residual Plot", fontsize=14, fontweight="bold")

# 4. Distribution of Predictions
axes[1, 1].hist(y_test, bins=30, alpha=0.5, label="Actual", color="blue")
axes[1, 1].hist(y_test_pred, bins=30, alpha=0.5, label="Predicted", color="red")
axes[1, 1].set_xlabel("Price (Rp)")
axes[1, 1].set_ylabel("Frequency")
axes[1, 1].set_title(
    "Distribution of Actual vs Predicted Prices", fontsize=14, fontweight="bold"
)
axes[1, 1].legend()

plt.tight_layout()
plt.savefig("random_forest_results.png", dpi=300, bbox_inches="tight")
print("✓ Visualization saved as 'random_forest_results.png'")

# ============================================
# 9. SAVE MODEL (Optional)
# ============================================

print("\n" + "=" * 50)
print("STEP 9: Model Summary")
print("=" * 50)

print(
    f"""
🎯 RANDOM FOREST MODEL SUMMARY:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✓ Total samples: {len(df_clean)}
✓ Training samples: {len(X_train)}
✓ Test samples: {len(X_test)}
✓ Number of features: {len(features)}
✓ Number of trees: {rf_model.n_estimators}
✓ Max depth: {rf_model.max_depth}

📊 PERFORMANCE METRICS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Test MAE:  Rp {test_mae:,.0f}
  Test RMSE: Rp {test_rmse:,.0f}
  Test R²:   {test_r2:.4f}

🏆 TOP 3 MOST IMPORTANT FEATURES:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
"""
)

for idx, row in feature_importance.head(3).iterrows():
    print(f"  {idx+1}. {row['Feature']:<25} {row['Importance']:.4f}")

print("\n✅ Analysis completed successfully!")
print("=" * 50)
