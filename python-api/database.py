import mysql.connector
from dotenv import load_dotenv
import os

load_dotenv()

def get_db():
    return mysql.connector.connect(
        host=os.getenv("DB_HOST", "127.0.0.1"),
        user=os.getenv("DB_USER", "root"),
        password=os.getenv("DB_PASSWORD", ""),
        database=os.getenv("DB_NAME", "afet"),  # .env ile eşleşmeli
        port=int(os.getenv("DB_PORT", 3306)),
        use_pure=True  # TCP/IP kullanmak için
    )
