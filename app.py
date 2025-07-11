from flask import Flask, request, jsonify
import subprocess
import sys

app = Flask(__name__)

@app.route('/generate', methods=['POST'])
def generate():
    data = request.get_json()
    required = ['mac', 'username', 'extension', 'password', 'sip_server']
    if not all(k in data for k in required):
        return jsonify({'error': 'Données manquantes'}), 400

    cmd = [
        sys.executable, 'generate_config.py',
        data['mac'], data['username'], data['extension'],
        data['password'], data['sip_server']
    ]
    try:
        output = subprocess.check_output(cmd, stderr=subprocess.STDOUT, text=True)
        return jsonify({'success': True, 'output': output})
    except subprocess.CalledProcessError as e:
        return jsonify({'success': False, 'output': e.output}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
