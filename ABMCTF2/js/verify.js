/**
 * 2FA Verification - File Upload & Hash Check via XHR
 */

const fileInput = document.getElementById('fileInput');
const uploadZone = document.getElementById('uploadZone');
const selectedFileEl = document.getElementById('selectedFile');
const verifyBtn = document.getElementById('verifyBtn');
const verifyLoading = document.getElementById('verifyLoading');
const verifyResult = document.getElementById('verifyResult');

let selectedFile = null;

// File selection
fileInput.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        selectedFile = e.target.files[0];
        showSelectedFile();
    }
});

// Drag & Drop
uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
        selectedFile = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        showSelectedFile();
    }
});

function showSelectedFile() {
    if (!selectedFile) return;
    
    selectedFileEl.style.display = 'block';
    selectedFileEl.innerHTML = `
        <strong>FILE:</strong> ${selectedFile.name}<br>
        <strong>SIZE:</strong> ${(selectedFile.size / 1024).toFixed(2)} KB<br>
        <strong>TYPE:</strong> ${selectedFile.type || 'unknown'}
    `;
    verifyBtn.style.display = 'block';
    verifyResult.style.display = 'none';
}

// Verify file via XHR
function verifyFile() {
    if (!selectedFile) return;
    
    verifyBtn.style.display = 'none';
    verifyLoading.style.display = 'block';
    verifyResult.style.display = 'none';
    
    // Create FormData to send the file
    const formData = new FormData();
    formData.append('keyfile', selectedFile);
    
    // Send file via XHR
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './check_hash.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            verifyLoading.style.display = 'none';
            
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.status === 'success') {
                    new Audio('../LoggedIn.mp3').play().catch(e => {});
                    verifyResult.className = 'verify-result success';
                    verifyResult.innerHTML = '✅ AUTHORIZATION VERIFIED — REDIRECTING...';
                    verifyResult.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = './panel.php';
                    }, 2000);
                } else {
                    new Audio('../Invalid-Login.mp3').play().catch(e => {});
                    verifyResult.className = 'verify-result error';
                    verifyResult.innerHTML = '❌ INVALID KEY FILE — HASH MISMATCH';
                    verifyResult.style.display = 'block';
                    verifyBtn.style.display = 'block';
                }
            } catch(err) {
                verifyResult.className = 'verify-result error';
                verifyResult.innerHTML = '❌ VERIFICATION ERROR — TRY AGAIN';
                verifyResult.style.display = 'block';
                verifyBtn.style.display = 'block';
            }
        }
    };
    xhr.send(formData);
}
