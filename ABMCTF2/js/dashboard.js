/**
 * CCTV Dashboard - Camera Controls & Interactions
 */

// ===== State Management =====
const cameraAudio = new Audio('./Camera.mp3');
function playCameraSound() {
    cameraAudio.currentTime = 0;
    cameraAudio.play().catch(e => {});
}

const cameraState = {};
let currentFullscreenCam = null;

['live-1','live-2','live-3','live-4'].forEach(id => {
    cameraState[id] = {
        zoom: 1,
        panX: 0,
        panY: 0,
        filter: 'none'
    };
});

// ===== Clock =====
function updateTime() {
    const now = new Date();
    const fmt = now.getFullYear() + '/' +
        String(now.getMonth()+1).padStart(2,'0') + '/' +
        String(now.getDate()).padStart(2,'0') + ' ' +
        String(now.getHours()).padStart(2,'0') + ':' +
        String(now.getMinutes()).padStart(2,'0') + ':' +
        String(now.getSeconds()).padStart(2,'0');
    
    const el = document.getElementById('systemTime');
    if (el) el.textContent = fmt;
    
    // Update each camera date
    ['live-1','live-2','live-3','live-4'].forEach(id => {
        const dateEl = document.getElementById('date-' + id);
        if (dateEl) dateEl.textContent = fmt;
    });
}
updateTime();
setInterval(updateTime, 1000);

// ===== Zoom Controls =====
function zoomIn(camId) {
    playCameraSound();
    const state = cameraState[camId];
    if (state.zoom < 4) {
        state.zoom = Math.min(4, state.zoom + 0.5);
        applyTransform(camId);
    }
}

function zoomOut(camId) {
    playCameraSound();
    const state = cameraState[camId];
    if (state.zoom > 1) {
        state.zoom = Math.max(1, state.zoom - 0.5);
        // Reset pan if zoomed out to 1
        if (state.zoom === 1) {
            state.panX = 0;
            state.panY = 0;
        }
        applyTransform(camId);
    }
}

// ===== Pan Controls =====
function panCamera(camId, direction) {
    playCameraSound();
    const state = cameraState[camId];
    const step = 30;
    
    switch(direction) {
        case 'up':    state.panY = Math.min(state.panY + step, 200); break;
        case 'down':  state.panY = Math.max(state.panY - step, -200); break;
        case 'left':  state.panX = Math.min(state.panX + step, 200); break;
        case 'right': state.panX = Math.max(state.panX - step, -200); break;
    }
    applyTransform(camId);
}

function resetCamera(camId) {
    playCameraSound();
    const state = cameraState[camId];
    state.zoom = 1;
    state.panX = 0;
    state.panY = 0;
    applyTransform(camId);
}

// ===== Apply Transform =====
function applyTransform(camId) {
    const state = cameraState[camId];
    const video = document.getElementById('video-' + camId);
    if (video) {
        video.style.transform = `scale(${state.zoom}) translate(${state.panX / state.zoom}px, ${state.panY / state.zoom}px)`;
    }
    
    // Also apply to fullscreen if active
    if (currentFullscreenCam === camId) {
        const fsVideo = document.getElementById('fsVideo');
        if (fsVideo) {
            fsVideo.style.transform = `scale(${state.zoom}) translate(${state.panX / state.zoom}px, ${state.panY / state.zoom}px)`;
        }
    }
}

// ===== Filters =====
function setFilter(camId, filterType) {
    playCameraSound();
    const state = cameraState[camId];
    state.filter = filterType;
    
    // Apply to grid video
    const video = document.getElementById('video-' + camId);
    if (video) {
        video.className = 'cam-video';
        const filterOverlay = document.getElementById('filter-' + camId);
        if (filterOverlay) filterOverlay.className = 'filter-overlay';
        
        switch(filterType) {
            case 'invert':
                video.classList.add('filter-invert');
                break;
            case 'green':
                video.classList.add('filter-green');
                if (filterOverlay) filterOverlay.classList.add('night-green');
                break;
            case 'gray':
                video.classList.add('filter-gray');
                break;
        }
    }
    
    // Apply to fullscreen if active
    if (currentFullscreenCam === camId) {
        const fsVideo = document.getElementById('fsVideo');
        const fsFilter = document.getElementById('filter-fullscreen');
        if (fsVideo) {
            fsVideo.className = 'fs-video';
            if (fsFilter) fsFilter.className = 'filter-overlay';
            
            switch(filterType) {
                case 'invert':
                    fsVideo.classList.add('filter-invert');
                    break;
                case 'green':
                    fsVideo.classList.add('filter-green');
                    if (fsFilter) fsFilter.classList.add('night-green');
                    break;
                case 'gray':
                    fsVideo.classList.add('filter-gray');
                    break;
            }
        }
    }
}

// ===== Fullscreen =====
function toggleFullscreen(camId) {
    playCameraSound();
    currentFullscreenCam = camId;
    const modal = document.getElementById('fullscreenModal');
    const fsVideo = document.getElementById('fsVideo');
    const fsLabel = document.getElementById('fsLabel');
    const gridVideo = document.getElementById('video-' + camId);
    
    if (gridVideo && fsVideo) {
        fsVideo.src = gridVideo.src;
        fsVideo.currentTime = gridVideo.currentTime;
        fsVideo.play();
    }
    
    // Camera label
    const camNum = camId.replace('live-','');
    fsLabel.textContent = 'CAM-0' + camNum + ' | ' + camId.toUpperCase() + ' | FULLSCREEN';
    
    // Apply current filter
    const state = cameraState[camId];
    setFilter(camId, state.filter);
    
    // Apply current transform
    if (fsVideo) {
        fsVideo.style.transform = `scale(${state.zoom}) translate(${state.panX / state.zoom}px, ${state.panY / state.zoom}px)`;
    }
    
    modal.classList.add('active');
}

function closeFullscreen() {
    playCameraSound();
    const modal = document.getElementById('fullscreenModal');
    const fsVideo = document.getElementById('fsVideo');
    
    if (fsVideo) {
        fsVideo.pause();
        fsVideo.src = '';
    }
    
    modal.classList.remove('active');
    currentFullscreenCam = null;
}

// Fullscreen PTZ
function panFullscreen(direction) {
    if (currentFullscreenCam) {
        panCamera(currentFullscreenCam, direction);
    }
}

function resetFullscreen() {
    if (currentFullscreenCam) {
        resetCamera(currentFullscreenCam);
    }
}

// ESC to close fullscreen
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && currentFullscreenCam) {
        closeFullscreen();
    }
});

// Double click on camera cell to fullscreen
document.querySelectorAll('.cam-video-container').forEach(container => {
    container.addEventListener('dblclick', () => {
        const cell = container.closest('.camera-cell');
        if (cell) {
            const camId = cell.dataset.cam;
            toggleFullscreen(camId);
        }
    });
});
