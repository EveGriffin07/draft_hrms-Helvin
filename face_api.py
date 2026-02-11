from fastapi import FastAPI, UploadFile, File, Form
from fastapi.responses import JSONResponse
import numpy as np
import cv2
import ast
from typing import List

from insightface.app import FaceAnalysis

app = FastAPI(title="ArcFace HRMS API")

# --- Load model once when server starts ---
face_app = FaceAnalysis(name="buffalo_l")
face_app.prepare(ctx_id=0, det_size=(640, 640))


# --- Helpers ---
def read_image(file_bytes: bytes):
    nparr = np.frombuffer(file_bytes, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    return img


def extract_embedding(img_bgr):
    faces = face_app.get(img_bgr)
    if not faces:
        return None, "No face detected"

    # choose largest face
    faces = sorted(
        faces,
        key=lambda f: (f.bbox[2] - f.bbox[0]) * (f.bbox[3] - f.bbox[1]),
        reverse=True
    )

    emb = faces[0].embedding.astype(np.float32)
    emb = emb / np.linalg.norm(emb)  # normalize
    return emb, None


def cosine_similarity(a: np.ndarray, b: np.ndarray) -> float:
    # embeddings are normalized, so dot product = cosine similarity
    return float(np.dot(a, b))


# --- API ---
@app.post("/enroll")
async def enroll_face(
    employee_id: str = Form(...),
    images: List[UploadFile] = File(...)
):
    embeddings = []
    for image in images:
        img = read_image(await image.read())
        if img is None:
            return JSONResponse({"ok": False, "error": "Invalid image"}, status_code=400)

        emb, error = extract_embedding(img)
        if error:
            return JSONResponse({"ok": False, "error": error}, status_code=400)

        embeddings.append(emb.tolist())

    return {
        "ok": True,
        "employee_id": employee_id,
        "embeddings": embeddings,
    }


def verify_logic(user_id: str, image: UploadFile, stored_embedding: str, threshold: float):
    """
    Shared verify/match logic.
    """
    return {"user_id": user_id, "image": image, "stored_embedding": stored_embedding, "threshold": threshold}


@app.post("/verify")
@app.post("/match")
async def verify_face(
    user_id: str = Form(...),
    image: UploadFile = File(...),
    stored_embedding: str = Form(...),  # JSON string or Python list string
    threshold: float = Form(0.35)
):
    img = read_image(await image.read())
    if img is None:
        return JSONResponse({"ok": False, "error": "Invalid image"}, status_code=400)

    live_emb, error = extract_embedding(img)
    if error:
        return JSONResponse({"ok": False, "error": error}, status_code=400)

    # safer than eval()
    try:
        emb_list = ast.literal_eval(stored_embedding)  # expects "[...]" list
        db_emb = np.array(emb_list, dtype=np.float32)
        db_emb = db_emb / np.linalg.norm(db_emb)
    except Exception:
        return JSONResponse({"ok": False, "error": "Invalid stored embedding format"}, status_code=400)

    score = cosine_similarity(live_emb, db_emb)
    matched = score >= threshold

    return {
        "ok": True,
        "user_id": user_id,
        "score": score,
        "threshold": threshold,
        "matched": matched
    }
