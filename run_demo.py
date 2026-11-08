import uvicorn
import os
import sys

# Ensure UTF-8 output on Windows terminal
if sys.platform == 'win32':
    sys.stdout.reconfigure(encoding='utf-8')

if __name__ == "__main__":
    print("=" * 65)
    print("🚀 HaoHao Mandarin - Live Demo & QRIS Payment Simulator")
    print("🌐 Buka di Browser: http://localhost:8080")
    print("👨‍💻 Developed by Ahmad Yusuf Arifin")
    print("=" * 65)
    uvicorn.run("demo.server:app", host="0.0.0.0", port=8080, reload=True)
