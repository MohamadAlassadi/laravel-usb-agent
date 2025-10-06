
import socket
import json
import os
import base64

USB_PATH = r"F:\\"
HOST = "0.0.0.0"
PORT = 9000

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind((HOST, PORT))
server.listen(1)

print(f"USB Agent running on {HOST}:{PORT}")

while True:
    conn, addr = server.accept()
    print(f"📡 Connection from {addr}")

    # استقبال البيانات حتى نهاية الرسالة
    data = b""
    while True:
        chunk = conn.recv(4096)
        if not chunk:
            break
        data += chunk
        if b'\n' in chunk:
            break

    data = data.decode().strip()
    if not data:
        conn.close()
        continue

    try:
        request = json.loads(data)
        command = request.get("command")
        payload = request.get("payload", {})

        if command == "list":
            files = os.listdir(USB_PATH)
            response = {"success": True, "files": files}

        elif command == "upload":
            filename = payload["filename"]
            content = base64.b64decode(payload["content"])
            filepath = os.path.join(USB_PATH, filename)
            with open(filepath, "wb") as f:
                f.write(content)
            response = {"success": True, "message": f"{filename} uploaded successfully"}

        elif command == "download":
            filename = payload["filename"]
            filepath = os.path.join(USB_PATH, filename)
            if os.path.exists(filepath):
                with open(filepath, "rb") as f:
                    encoded = base64.b64encode(f.read()).decode()
                response = {"success": True, "filename": filename, "content": encoded}
            else:
                response = {"success": False, "message": "File not found"}

        else:
            response = {"success": False, "message": "Unknown command"}

    except json.JSONDecodeError as e:
        response = {"success": False, "message": f"JSON decode error: {str(e)}"}
    except Exception as e:
        response = {"success": False, "message": str(e)}

    conn.sendall((json.dumps(response) + "\n").encode())
    conn.close()
