from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import joblib
import numpy as np

app = FastAPI()

# Load model and label encoder
model = joblib.load('stress_rf_model.pkl')
label_encoder = joblib.load('label_encoder.pkl')

# Define input data model
class StressInput(BaseModel):
    studyhours: float
    hobbyhours: float
    sleephours: float
    socialhours: float
    activehours: float
    gwa: float

@app.post("/predict_stress")
async def predict_stress(data: StressInput):
    try:
        # Prepare input data
        input_data = np.array([[
            data.studyhours,
            data.hobbyhours,
            data.sleephours,
            data.socialhours,
            data.activehours,
            data.gwa
        ]])

        # Make prediction
        pred = model.predict(input_data)[0]
        confidence = model.predict_proba(input_data)[0].max()
        stress_level = label_encoder.inverse_transform([pred])[0]

        return {
            "stress_level": stress_level,
            "confidence": float(confidence)
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))