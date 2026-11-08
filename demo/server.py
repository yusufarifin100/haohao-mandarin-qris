import os
import uuid
import time
import hmac
import hashlib
from fastapi import FastAPI, Request, HTTPException
from fastapi.staticfiles import StaticFiles
from fastapi.responses import FileResponse, JSONResponse
from pydantic import BaseModel
from typing import Optional, Dict, Any

app = FastAPI(title="HaoHao Mandarin - Live Demo & QRIS Simulator", description="Interactive Demo for HaoHao Mandarin Platform")

# In-memory orders database for simulation
orders_db: Dict[str, Dict[str, Any]] = {}
SECRET_KEY = os.getenv("HAOHAO_SECRET_KEY", "demo_sandbox_hmac_secret")

class CreateOrderRequest(BaseModel):
    course_name: str
    price: int
    student_name: str
    student_email: str
    student_phone: str

class SimulatePaymentRequest(BaseModel):
    order_id: str
    issuer: str = "BCA Mobile"

@app.post("/api/orders/create")
async def create_order(req: CreateOrderRequest):
    order_id = str(uuid.uuid4())[:8].upper()
    transaction_id = f"QRIS-{order_id}-{int(time.time())}"
    expiry_time = int(time.time()) + (15 * 60) # 15 minutes
    
    # Generate dynamic QRIS string
    qris_string = f"00020101021226670016ID.CO.QRIS.WWW0118936005030000088910215HAOHAO{order_id}520482115303360540{len(str(req.price))}{req.price}5802ID5915HAOHAO MANDARIN6007JAKARTA61051234062200716{transaction_id}6304"
    qr_url = f"https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data={qris_string}"

    orders_db[order_id] = {
        "order_id": order_id,
        "transaction_id": transaction_id,
        "course_name": req.course_name,
        "price": req.price,
        "student_name": req.student_name,
        "student_email": req.student_email,
        "student_phone": req.student_phone,
        "status": "PENDING", # PENDING, COMPLETED, EXPIRED
        "qris_string": qris_string,
        "qr_url": qr_url,
        "expiry_time": expiry_time,
        "issuer": None,
        "created_at": time.strftime("%Y-%m-%d %H:%M:%S")
    }

    return {
        "success": True,
        "order": orders_db[order_id]
    }

@app.get("/api/orders/status/{order_id}")
async def get_order_status(order_id: str):
    order = orders_db.get(order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    # Check expiry
    if order["status"] == "PENDING" and time.time() > order["expiry_time"]:
        order["status"] = "EXPIRED"

    return {
        "success": True,
        "order_id": order_id,
        "status": order["status"],
        "is_paid": order["status"] == "COMPLETED",
        "order": order
    }

@app.post("/api/webhook/simulate")
async def simulate_webhook(req: SimulatePaymentRequest):
    order = orders_db.get(req.order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")

    # Generate HMAC-SHA256 signature
    payload = f'{{"order_id":"{req.order_id}","transaction_status":"SUCCESS","issuer":"{req.issuer}"}}'
    signature = hmac.new(SECRET_KEY.encode('utf-8'), payload.encode('utf-8'), hashlib.sha256).hexdigest()

    # Process status transition (Idempotent)
    order["status"] = "COMPLETED"
    order["issuer"] = req.issuer
    order["paid_at"] = time.strftime("%Y-%m-%d %H:%M:%S")

    return {
        "success": True,
        "message": f"Webhook berhasil diverifikasi via signature HMAC-SHA256. Pembayaran via {req.issuer} dinyatakan LUNAS.",
        "signature_verified": True,
        "order": order
    }

# Download ZIP files endpoint
@app.get("/api/download/{package_name}")
async def download_package(package_name: str):
    root_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    if package_name == "plugin":
        zip_path = os.path.join(root_dir, "dist", "haohao-qris-payment.zip")
    elif package_name == "theme":
        zip_path = os.path.join(root_dir, "dist", "haohao-mandarin-theme.zip")
    else:
        raise HTTPException(status_code=404, detail="Package not found")
        
    if os.path.exists(zip_path):
        return FileResponse(zip_path, filename=os.path.basename(zip_path), media_type="application/zip")
    else:
        raise HTTPException(status_code=404, detail="File package belum di-build. Jalankan build_packages.py terlebih dahulu.")

# Static files mount
static_dir = os.path.join(os.path.dirname(__file__), "static")
os.makedirs(static_dir, exist_ok=True)
app.mount("/static", StaticFiles(directory=static_dir), name="static")

@app.get("/")
async def serve_index():
    index_path = os.path.join(static_dir, "index.html")
    if os.path.exists(index_path):
        return FileResponse(index_path)
    return {"message": "HaoHao Mandarin Simulator Ready"}
