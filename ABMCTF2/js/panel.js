/**
 * Chemical Operations Management OS - Panel Interactions
 * Clock, auto-refresh status
 */

// Clock
const alarmAudio = new Audio('../Alarm.mp3');
alarmAudio.loop = true;
let isAlarmActive = document.body.classList.contains('system-alarm');

function checkAlarmSound() {
    if (isAlarmActive) {
        alarmAudio.play().catch(e => {
            const interactionListener = () => {
                if (isAlarmActive) alarmAudio.play();
                document.removeEventListener('click', interactionListener);
            };
            document.addEventListener('click', interactionListener);
        });
    } else {
        alarmAudio.pause();
        alarmAudio.currentTime = 0;
    }
}
checkAlarmSound();

function updateNavTime() {
    const now = new Date();
    const fmt = now.getFullYear() + '/' +
        String(now.getMonth()+1).padStart(2,'0') + '/' +
        String(now.getDate()).padStart(2,'0') + ' ' +
        String(now.getHours()).padStart(2,'0') + ':' +
        String(now.getMinutes()).padStart(2,'0') + ':' +
        String(now.getSeconds()).padStart(2,'0');
    
    const el = document.getElementById('navTime');
    if (el) el.textContent = fmt;
}

updateNavTime();
setInterval(updateNavTime, 1000);

// Auto-refresh sensor data every 10 seconds
setInterval(() => {
    fetch('./api.php')
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok' && data.data) {
                data.data.forEach(sensor => {
                    const elId = 'val-' + sensor.sensor.replace(/\//g, '-');
                    const el = document.getElementById(elId);
                    if (el) {
                        el.textContent = sensor.value;
                        
                        // Update card styling
                        const card = el.closest('.sensor-card');
                        if (card) {
                            if (sensor.status === 'ALARM') {
                                card.classList.remove('safe');
                                card.classList.add('alarm');
                            } else {
                                card.classList.remove('alarm');
                                card.classList.add('safe');
                            }
                        }
                    }
                });
                
                // Update alarm count and system status
                const alarmIndicator = document.querySelector('.alarm-indicator');
                const alarmText = document.querySelector('.alarm-text');
                
                if (data.alarm_count === 0) {
                    isAlarmActive = false;
                    checkAlarmSound();
                    if (alarmIndicator) {
                        alarmIndicator.classList.remove('danger');
                        alarmIndicator.classList.add('safe');
                    }
                    if (alarmText) alarmText.textContent = 'ALL SYSTEMS NOMINAL';
                    document.body.classList.remove('system-alarm');
                    document.body.classList.add('system-safe');
                    
                    // Reload to show flag
                    if (!document.getElementById('flagBanner')) {
                        location.reload();
                    }
                } else {
                    isAlarmActive = true;
                    checkAlarmSound();
                }
            }
        })
        .catch(() => {}); // Silently fail
}, 10000);

// Add subtle micro-animations to sensor cards
document.querySelectorAll('.sensor-card').forEach(card => {
    card.addEventListener('click', () => {
        const sensorKey = card.dataset.sensor;
        if (sensorKey) {
            // Copy API path to clipboard
            const apiPath = './api.php?path=' + sensorKey;
            navigator.clipboard.writeText(apiPath).then(() => {
                // Visual feedback
                card.style.borderColor = '#00d4ff';
                card.style.boxShadow = '0 0 15px rgba(0,212,255,0.2)';
                setTimeout(() => {
                    card.style.borderColor = '';
                    card.style.boxShadow = '';
                }, 1000);
            }).catch(() => {});
        }
    });
});
