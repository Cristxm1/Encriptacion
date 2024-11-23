function toggleExportOptions() {
    const exportSection = document.getElementById('exportSection');
    exportSection.style.display = document.getElementById('exportCheckbox').checked ? 'block' : 'none';
}

function clearTextArea() {
    const messageArea = document.getElementById('message');
    messageArea.value = '';
    enableTextArea();
}

function disableTextArea() {
    const messageArea = document.getElementById('message');
    messageArea.value = '';
    messageArea.disabled = true;
}

function enableTextArea() {
    const messageArea = document.getElementById('message');
    messageArea.disabled = false;
}

function copyResult() {
    const resultText = document.getElementById('resultText');
    resultText.select();
    document.execCommand('copy');
    
    // Bootstrap Toast notification
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    toastContainer.innerHTML = `
        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>Texto copiado al portapapeles
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    document.body.appendChild(toastContainer);
    
    const toastElement = toastContainer.querySelector('.toast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toastContainer);
    });
}

function checkFileImport() {
    const fileInput = document.getElementById('importFile');
    const messageArea = document.getElementById('message');
    if (fileInput.files.length > 0) {
        disableTextArea();
    } else {
        enableTextArea();
    }
}

window.onload = function() {
    if (document.getElementById('importFile').files.length === 0) {
        enableTextArea();
    }
}