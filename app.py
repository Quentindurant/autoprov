from flask import Flask, request, jsonify, send_from_directory, render_template, redirect, url_for
import subprocess
import sys
import os
import json

# Flask doit pouvoir trouver les templates dans le dossier 'templates' à côté de ce script
app = Flask(__name__, template_folder='templates')

def log_debug(msg):
    with open("debug_autoprov.log", "a", encoding="utf-8") as f:
        f.write(msg + "\n")

@app.route('/generate', methods=['POST'])
def generate():
    data = request.get_json()
    log_debug("DEBUG DATA REÇU : " + str(data))
    # Validation basique (MAC obligatoire)
    if not data or 'mac' not in data:
        log_debug("ERREUR : MAC manquante")
        return jsonify({'success': False, 'error': 'MAC address requise'}), 400

    # Appel du script Python avec tous les paramètres en JSON
    try:
        cmd = [sys.executable, 'generate_config.py', json.dumps(data)]
        output = subprocess.check_output(cmd, stderr=subprocess.STDOUT, text=True)
    except subprocess.CalledProcessError as e:
        log_debug("ERREUR PYTHON : " + e.output)
        return jsonify({'success': False, 'error': e.output}), 500
    except Exception as e:
        log_debug("ERREUR INATTENDUE : " + str(e))
        return jsonify({'success': False, 'error': str(e)}), 500

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

# --- PAGE : Liste des téléphones enregistrés (MACs) ---
@app.route('/phones')
def list_phones():
    """
    Affiche la liste de toutes les MAC pour lesquelles un .cfg existe dans le dossier courant.
    """
    files = [f for f in os.listdir(os.path.dirname(__file__)) if f.endswith('.cfg')]
    macs = [f.replace('.cfg', '') for f in files]
    return render_template('phones.html', macs=macs)

# --- PAGE : Edition d'une config de téléphone ---
@app.route('/edit/<mac>', methods=['GET'])
def edit_phone(mac):
    """
    Affiche un formulaire avec le contenu actuel du .cfg de la MAC (modifiable).
    """
    cfg_filename = f"{mac}.cfg"
    cfg_path = os.path.join(os.path.dirname(__file__), cfg_filename)
    cfg_content = ''
    if os.path.exists(cfg_path):
        with open(cfg_path, encoding='utf-8') as f:
            cfg_content = f.read()
    else:
        cfg_content = '# Fichier inexistant, il sera créé à l\'enregistrement.'
    return render_template('edit_phone.html', mac=mac, cfg_content=cfg_content, message=None)

# --- ACTION : Mise à jour de la config d'un téléphone ---
@app.route('/update/<mac>', methods=['POST'])
def update_phone(mac):
    """
    Reçoit le contenu du formulaire, sauvegarde dans le .cfg correspondant, puis recharge la page.
    """
    cfg_filename = f"{mac}.cfg"
    cfg_path = os.path.join(os.path.dirname(__file__), cfg_filename)
    cfg_content = request.form.get('cfg_content', '')
    with open(cfg_path, 'w', encoding='utf-8') as f:
        f.write(cfg_content)
    message = 'Configuration enregistrée avec succès !'
    return render_template('edit_phone.html', mac=mac, cfg_content=cfg_content, message=message)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
