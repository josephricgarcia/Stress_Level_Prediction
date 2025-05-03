from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import numpy as np
from tensorflow import keras
from contextlib import asynccontextmanager
from fastapi.middleware.cors import CORSMiddleware
import logging
import joblib

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Model and scaler loading
@asynccontextmanager
async def lifespan(app: FastAPI):
    global model, scaler
    try:
        # Load model and scaler
        model = keras.models.load_model("mlp_model.keras")
        scaler = joblib.load("scaler.save")
        logger.info("Model and scaler loaded successfully")
        logger.info(f"Scaler mean: {scaler.mean_.tolist()}, scale: {scaler.scale_.tolist()}")
    except Exception as e:
        logger.error(f"Loading error: {e}")
        raise RuntimeError("Initialization failed") from e
    yield
    # Cleanup
    del model, scaler

app = FastAPI(lifespan=lifespan)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class StressInput(BaseModel):
    studyhours: float
    hobbyhours: float
    sleephours: float
    socialhours: float
    activehours: float
    gwa: float

    class Config:
        schema_extra = {
            "example": {
                "studyhours": 8.0,
                "hobbyhours": 2.0,
                "sleephours": 6.0,
                "socialhours": 3.0,
                "activehours": 2.0,
                "gwa": 2.5
            }
        }

@app.post("/predict_stress")
async def predict_stress(data: StressInput):
    try:
        # Log raw input
        logger.info(f"Raw input: {data.dict()}")

        # Validate input ranges
        for field, value in data.dict().items():
            if field != 'gwa' and (value < 0 or value > 24):
                raise HTTPException(422, f"{field} must be between 0 and 24")
        if data.gwa < 1.0 or data.gwa > 5.0:
            raise HTTPException(422, "GWA must be between 1.0 and 5.0")

        # Convert and scale input
        raw_input = np.array([
            [data.studyhours, data.hobbyhours, data.sleephours,
             data.socialhours, data.activehours, data.gwa]
        ], dtype=np.float32)
        
        scaled_input = scaler.transform(raw_input)
        logger.info(f"Scaled input: {scaled_input.tolist()}")

        # Validate input shape
        if scaled_input.shape != (1, 6):
            raise HTTPException(422, f"Invalid input shape: {scaled_input.shape}")

        # Predict
        prediction = model.predict(scaled_input, verbose=0)
        logger.info(f"Raw prediction: {prediction.tolist()}")

        # Validate output
        if prediction.shape != (1, 3):
            raise HTTPException(500, f"Unexpected output shape: {prediction.shape}")
        if np.any(np.isnan(prediction)):
            raise HTTPException(500, "Prediction contains NaN values")

        prediction_index = int(np.argmax(prediction[0]))
        confidence = float(np.max(prediction[0]))
        
        # Map prediction to stress level
        stress_levels = {0: "Low", 1: "Medium", 2: "High"}
        stress_level = stress_levels.get(prediction_index, "Unknown")
        
        response = {
            "prediction": prediction_index,
            "stress_level": stress_level,
            "confidence": confidence,
            "raw_output": prediction[0].tolist(),
            "scaled_input": scaled_input[0].tolist()
        }
        logger.info(f"Response: {response}")
        return response
    
    except HTTPException as he:
        logger.error(f"HTTP Exception: {he.detail}")
        raise he
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}", exc_info=True)
        raise HTTPException(500, f"Prediction failed: {str(e)}")

@app.get("/debug_model")
async def debug_model(studyhours: float, hobbyhours: float, sleephours: float, socialhours: float, activehours: float, gwa: float):
    """Debug endpoint to test model predictions"""
    try:
        data = StressInput(
            studyhours=studyhours,
            hobbyhours=hobbyhours,
            sleephours=sleephours,
            socialhours=socialhours,
            activehours=activehours,
            gwa=gwa
        )
        return await predict_stress(data)
    except Exception as e:
        logger.error(f"Debug error: {str(e)}")
        raise HTTPException(500, f"Debug failed: {str(e)}")

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)