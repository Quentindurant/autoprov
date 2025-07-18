// form_submit.js
// JS dédié à la collecte et l'envoi des données du formulaire AutoProvision Yealink

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('autoprov-form');
    if (!form) return;

    form.onsubmit = async function (e) {
        e.preventDefault();

        // Collecte des champs principaux
        const data = {
            mac: document.getElementById('mac').value.trim(),
            label: document.getElementById('label').value.trim(),
            display_name: document.getElementById('display_name').value.trim(),
            user_name: document.getElementById('user_name').value.trim(),
            auth_name: document.getElementById('auth_name').value.trim(),
            password: document.getElementById('password').value.trim(),
            sip_server: document.getElementById('sip_server').value.trim(),
            sip_port: document.getElementById('sip_port').value.trim(),
            lang_gui: document.getElementById('lang_gui') ? document.getElementById('lang_gui').value : 'French',
            lang_wui: document.getElementById('lang_wui') ? document.getElementById('lang_wui').value : 'French',
            admin_password: document.getElementById('admin_password') ? document.getElementById('admin_password').value : 'UGCI8376',
            autoprov_url: document.getElementById('autoprov_url') ? document.getElementById('autoprov_url').value : 'https://autoprov.quentindurant.com/',
            logo_mode: Number(document.getElementById('logo_mode') ? document.getElementById('logo_mode').value : 2),
            timezone: document.getElementById('timezone') ? document.getElementById('timezone').value : '+1',
            timezone_name: document.getElementById('timezone_name') ? document.getElementById('timezone_name').value : 'France(Paris)',
            headset_mode: Number(document.getElementById('headset_mode') ? document.getElementById('headset_mode').value : 1),
            transfer_type: Number(document.getElementById('transfer_type') ? document.getElementById('transfer_type').value : 1),
            linekeys: collectLineKeys(),
            blfs: collectBLFs()
        };

        // Validation stricte des champs obligatoires
        const required = ['mac', 'label', 'display_name', 'user_name', 'auth_name', 'password', 'sip_server', 'sip_port'];
        for (const key of required) {
            if (!data[key] || data[key].trim() === "") {
                alert("Le champ '" + key + "' est obligatoire et ne peut pas être vide.");
                document.getElementById('feedback').innerHTML = '<span class="text-danger">Erreur : champ ' + key + ' manquant ou vide</span>';
                return;
            }
        }
        // Debug : afficher le JSON envoyé
        console.log("DATA ENVOYÉ :", data);

        // Affichage feedback utilisateur
        document.getElementById('feedback').innerHTML = 'Génération en cours...';
        document.getElementById('preview').style.display = 'none';

        try {
            const resp = await fetch('/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await resp.json();
            if (res.success) {
                document.getElementById('feedback').innerHTML = '<span class="text-success">Fichiers générés avec succès !</span>';
                if (res.preview) {
                    document.getElementById('preview').style.display = 'block';
                    document.getElementById('preview').textContent = res.preview;
                }
                if (res.download_url) {
                    document.getElementById('feedback').innerHTML += `<br><a href="${res.download_url}" class="btn btn-success mt-2">Télécharger le .cfg</a>`;
                }
            } else {
                document.getElementById('feedback').innerHTML = '<span class="text-danger">Erreur : ' + res.error + '</span>';
            }
        } catch (err) {
            document.getElementById('feedback').innerHTML = '<span class="text-danger">Erreur lors de la génération</span>';
        }
    };
});

// Fonctions utilitaires pour collecter les touches écran (linekeys) et BLF dynamiquement
function collectLineKeys() {
    const rows = document.querySelectorAll('#linekeys-container .linekey-row');
    return Array.from(rows).map(row => {
        const inputs = row.querySelectorAll('input');
        return {
            label: inputs[0] ? inputs[0].value.trim() : '',
            line: Number(inputs[1] ? inputs[1].value : 1),
            value: inputs[2] ? inputs[2].value.trim() : ''
        };
    }).filter(lk => lk.label !== '');
}

function collectBLFs() {
    const rows = document.querySelectorAll('#blfs-container .blf-row');
    return Array.from(rows).map(row => {
        const inputs = row.querySelectorAll('input');
        return {
            label: inputs[0] ? inputs[0].value.trim() : '',
            line: Number(inputs[1] ? inputs[1].value : 1),
            value: inputs[2] ? inputs[2].value.trim() : '',
            pickup: inputs[3] ? inputs[3].value.trim() : ''
        };
    }).filter(blf => blf.label !== '');
}
