from flask import Flask, request, jsonify, send_from_directory
import subprocess
import sys
import os
import json

app = Flask(__name__)

@app.route('/generate', methods=['POST'])
def generate():
    data = request.get_json()
    # Validation basique (MAC obligatoire)
    if not data or 'mac' not in data:
        return jsonify({'success': False, 'error': 'MAC address requise'}), 400

    # Appel du script Python avec tous les paramètres en JSON
    try:
        cmd = [sys.executable, 'generate_config.py', json.dumps(data)]
        output = subprocess.check_output(cmd, stderr=subprocess.STDOUT, text=True)
    except subprocess.CalledProcessError as e:
        return jsonify({'success': False, 'error': e.output}), 500

    # Lecture du .cfg généré pour preview
    mac_clean = data['mac'].lower().replace(':', '')
    cfg_filename = f"{mac_clean}.cfg"
    cfg_path = os.path.join(os.path.dirname(__file__), cfg_filename)
    preview = ''
    if os.path.exists(cfg_path):
        with open(cfg_path, encoding='utf-8') as f:
            preview = f.read()
    else:
        return jsonify({'success': False, 'error': 'Fichier .cfg non généré'}), 500

    # URL de téléchargement (optionnel)
    download_url = f"/download/{cfg_filename}"

    return jsonify({
        'success': True,
        'preview': preview,
        'download_url': download_url
    })

@app.route('/download/<filename>')
def download(filename):
    # Permet de télécharger le .cfg généré
    return send_from_directory(os.path.dirname(__file__), filename, as_attachment=True)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
