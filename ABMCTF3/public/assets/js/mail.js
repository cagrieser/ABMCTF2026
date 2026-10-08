document.addEventListener('DOMContentLoaded', () => {
    let currentFolder = 'inbox';
    let activeMailId = null;
    
    // Initial fetch
    fetchMails(currentFolder);
    
    // Fetch every 30 seconds to simulate live incoming mails
    setInterval(() => {
        fetchMails(currentFolder, true);
    }, 30000);
    
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', applySearch);
    }

    function applySearch() {
        const query = searchInput ? searchInput.value.toLowerCase() : '';
        document.querySelectorAll('.mail-item').forEach(el => {
            const subject = el.querySelector('.mail-subject')?.textContent.toLowerCase() || '';
            const sender = el.querySelector('.mail-sender')?.textContent.toLowerCase() || '';
            const preview = el.querySelector('.mail-preview')?.textContent.toLowerCase() || '';
            
            if (subject.includes(query) || sender.includes(query) || preview.includes(query)) {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        });
    }
    
    // TODO: Remove this telemetry config before production release!
    // Internal Endpoint: /api/v1/internal/telemetry
	// Application/JSON ( POST ) Parameters : mail_id ( Mail ID ) & auth_token ( Telemetry )
    const _INTERNAL_TELEMETRY_TOKEN = "FLIGHTCORE_SYS_ADM_99127x!"; 

    document.querySelectorAll('#nav-menu a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('#nav-menu a').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            currentFolder = link.getAttribute('data-folder');
            
            // Update panel header
            const headerTitle = document.querySelector('.mail-list-panel .panel-header h3');
            if(headerTitle) {
                headerTitle.textContent = link.textContent.trim().replace(/^[^\w]+/, '') + ' Folder';
            }
            
            activeMailId = null; // Reset active mail when switching folder
            document.getElementById('mail-content').innerHTML = '<div class="empty-state"><p>Select an email to view its secure contents.</p></div>';
            
            document.getElementById('mail-list').innerHTML = '<div class="empty-state">Loading...</div>';
            fetchMails(currentFolder);
        });
    });

    function fetchMails(folder, isBackgroundRefresh = false) {
        fetch(`api/v1/mails.php?folder=${folder}`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    renderMails(data.data, isBackgroundRefresh);
                    if(data.counts) updateCounts(data.counts);
                }
            })
            .catch(err => console.error('Error fetching mails:', err));
    }

    function updateCounts(counts) {
        Object.keys(counts).forEach(folder => {
            const countEl = document.getElementById(`count-${folder}`);
            if(countEl) {
                if(counts[folder].unread > 0) {
                    countEl.textContent = counts[folder].unread;
                    countEl.className = 'nav-count has-unread';
                } else if(counts[folder].total > 0) {
                    countEl.textContent = counts[folder].total;
                    countEl.className = 'nav-count has-total';
                } else {
                    countEl.style.display = 'none';
                }
            }
        });
    }

    function renderMails(mails, isBackgroundRefresh) {
        const inbox = document.getElementById('mail-list');
        
        if (!isBackgroundRefresh) {
            inbox.innerHTML = '';
        } else {
            // Remove empty state if present
            const emptyState = inbox.querySelector('.empty-state');
            if (emptyState) emptyState.remove();
        }
        
        let hasNewMails = false;

        mails.forEach((mail, index) => {
            let mailEl = inbox.querySelector(`.mail-item[data-id="${mail.id}"]`);
            
            if (!mailEl) {
                if (isBackgroundRefresh) {
                    hasNewMails = true;
                }
                mailEl = document.createElement('div');
                mailEl.className = `mail-item ${mail.is_recalled ? 'recalled' : ''} ${!mail.is_read ? 'unread' : ''}`;
                mailEl.dataset.id = mail.id;
                
                mailEl.innerHTML = `
                    <div class="mail-header">
                        <span class="mail-sender">${escapeHTML(mail.sender)}</span>
                        <span class="mail-time">${escapeHTML(mail.timestamp.substring(11, 16))}</span>
                    </div>
                    <div class="mail-subject">${escapeHTML(mail.subject)}</div>
                    <div class="mail-preview">${escapeHTML(mail.preview.substring(0, 50))}...</div>
                `;
                
                mailEl.addEventListener('click', () => {
                    document.querySelectorAll('.mail-item').forEach(el => el.classList.remove('active'));
                    mailEl.classList.add('active');
                    mailEl.classList.remove('unread', 'new-arrival');
                    activeMailId = mail.id;

                    if (!mail.is_read) {
                        fetch(`api/v1/mark_read.php?id=${mail.id}`);
                        mail.is_read = true;
                    }

                    renderMailContent(mail);
                });

                if (isBackgroundRefresh) {
                    mailEl.classList.add('new-arrival');
                }

                if (inbox.children[index]) {
                    inbox.insertBefore(mailEl, inbox.children[index]);
                } else {
                    inbox.appendChild(mailEl);
                }
            } else {
                // Update states
                if (!mail.is_read) {
                    mailEl.classList.add('unread');
                } else {
                    mailEl.classList.remove('unread');
                }
                if (mail.id === activeMailId) {
                    mailEl.classList.add('active');
                } else {
                    mailEl.classList.remove('active');
                }
            }
        });

        // Re-apply search filter if user is currently searching
        applySearch();

        // Play Notification Sound if new mails arrived during background refresh
        if (hasNewMails) {
            const audio = new Audio('Notification.mp3');
            audio.play().catch(e => console.log('Audio playback blocked by browser', e));
        }
    }

    function renderMailContent(mail) {
        const contentArea = document.getElementById('mail-content');
        if (mail.is_recalled) {
            contentArea.innerHTML = `
                <div class="mail-view-header">
                    <h2 style="color: var(--danger); font-style: italic;">${escapeHTML(mail.subject)}</h2>
                </div>
                <div class="recalled-warning">
                    <h3><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> Administrative Action / Security Filter</h3>
                    <p>HATA: Bu e-posta idari bir kararla veya güvenlik kalkanı (WAF) tarafından engellenerek geri çekilmiştir.</p>
                </div>
            `;
        } else {
            let ccHtml = mail.cc ? `<div class="meta-row"><span class="meta-label">Cc:</span><span class="meta-value">${escapeHTML(mail.cc)}</span></div>` : '';
            let bccHtml = mail.bcc ? `<div class="meta-row"><span class="meta-label">Bcc:</span><span class="meta-value">${escapeHTML(mail.bcc)}</span></div>` : '';
            
            contentArea.innerHTML = `
                <div class="mail-view-header">
                    <h2>${escapeHTML(mail.subject)}</h2>
                </div>
                <div class="mail-meta">
                    <div class="avatar">${mail.sender.charAt(0).toUpperCase()}</div>
                    <div class="meta-details">
                        <div class="meta-row">
                            <span class="meta-label">From:</span>
                            <span class="meta-value"><strong>${escapeHTML(mail.sender)}</strong></span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">To:</span>
                            <span class="meta-value">${escapeHTML(mail.to)}</span>
                        </div>
                        ${ccHtml}
                        ${bccHtml}
                        <div class="meta-time">${escapeHTML(mail.timestamp)}</div>
                    </div>
                </div>
                <div class="mail-body">
                    <p>${escapeHTML(mail.preview).replace(/\\n/g, '<br>')}</p>
                    <div class="signature">--<br>FlightCore Secure Comms System</div>
                </div>
            `;
        }
    }

    function escapeHTML(str) {
        if (!str) return '';
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    }
});
