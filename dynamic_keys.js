// dynamic_keys.js
// Fonctions globales pour l'ajout dynamique de touches écran (linekeys) et BLF
function addLinekey() {
    const c = document.getElementById('linekeys-container');
    const idx = c.children.length + 1;
    const row = document.createElement('div');
    row.setAttribute('class', 'linekey-row'); // Force la classe
    row.innerHTML = `
        <input type="text" placeholder="Label" name="linekey_label_${idx}" required>
        <input type="number" placeholder="Ligne" name="linekey_line_${idx}" value="1" min="1">
        <input type="number" placeholder="Valeur" name="linekey_value_${idx}" value="${idx}">
        <span class="remove-btn" onclick="this.parentNode.remove()">&times;</span>
    `;
    c.appendChild(row);
}
function addBLF() {
    const c = document.getElementById('blfs-container');
    const idx = c.children.length + 1;
    const row = document.createElement('div');
    row.setAttribute('class', 'blf-row'); // Force la classe
    row.innerHTML = `
        <input type="text" placeholder="Label" name="blf_label_${idx}" required>
        <input type="number" placeholder="Ligne" name="blf_line_${idx}" value="1" min="1">
        <input type="number" placeholder="Valeur" name="blf_value_${idx}">
        <input type="text" placeholder="Extension" name="blf_extension_${idx}">
        <span class="remove-btn" onclick="this.parentNode.remove()">&times;</span>
    `;
    c.appendChild(row);
}
window.addLinekey = addLinekey;
window.addBLF = addBLF;
