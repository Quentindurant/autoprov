// Script drag & drop ultra simple pour BLF et Linekeys
function makeDraggable(containerId, rowClass) {
    const container = document.getElementById(containerId);
    let dragSrcEl = null;
    function handleDragStart(e) {
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
        this.classList.add('dragElem');
    }
    function handleDragOver(e) {
        if (e.preventDefault) e.preventDefault();
        this.classList.add('over');
        e.dataTransfer.dropEffect = 'move';
        return false;
    }
    function handleDragLeave(e) {
        this.classList.remove('over');
    }
    function handleDrop(e) {
        if (e.stopPropagation) e.stopPropagation();
        if (dragSrcEl !== this) {
            // On échange les valeurs des inputs entre dragSrcEl et this
            const srcInputs = dragSrcEl.querySelectorAll('input, select, textarea');
            const dstInputs = this.querySelectorAll('input, select, textarea');
            for (let i = 0; i < srcInputs.length; i++) {
                // Sauvegarde temporaire
                const tmp = srcInputs[i].value;
                srcInputs[i].value = dstInputs[i].value;
                dstInputs[i].value = tmp;
            }
        }
        this.classList.remove('over');
        return false;
    }
    function handleDragEnd(e) {
        this.classList.remove('over');
        this.classList.remove('dragElem');
    }
    function addDnDEvents(elem) {
        elem.addEventListener('dragstart', handleDragStart, false);
        elem.addEventListener('dragover', handleDragOver, false);
        elem.addEventListener('dragleave', handleDragLeave, false);
        elem.addEventListener('drop', handleDrop, false);
        elem.addEventListener('dragend', handleDragEnd, false);
    }
    function refreshDnD() {
        let rows = container.querySelectorAll('.' + rowClass);
        rows.forEach(row => {
            row.setAttribute('draggable', 'true');
            addDnDEvents(row);
        });
    }
    refreshDnD();
    // Pour ajout dynamique
    const observer = new MutationObserver(refreshDnD);
    observer.observe(container, { childList: true });
}

document.addEventListener('DOMContentLoaded', function() {
    makeDraggable('blfs-container', 'blf-row');
    makeDraggable('linekeys-container', 'linekey-row');
});
