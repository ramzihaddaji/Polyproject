/**
 * =====================================================
 * CinéTrack — script.js
 * Fonctionnalités JS : navbar, recherche live, filtres,
 * image upload preview, password toggle & strength
 * =====================================================
 */

document.addEventListener('DOMContentLoaded', () => {

    // ======================================================
    // 1. NAVBAR — scroll effect + dropdown user + burger
    // ======================================================

    const navbar   = document.getElementById('navbar');
    const userMenu = document.getElementById('nav-user-menu');
    const userBtn  = document.getElementById('nav-user-btn');
    const dropdown = document.getElementById('nav-dropdown');
    const burger   = document.getElementById('nav-burger');
    const navLinks = document.getElementById('nav-links');
    const overlay  = document.getElementById('nav-overlay');

    // Effet scroll navbar
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    }

    // Dropdown utilisateur
    if (userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = userMenu.classList.toggle('active');
            userBtn.setAttribute('aria-expanded', isOpen);
        });

        // Fermer si clic ailleurs
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove('active');
                userBtn && userBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Fermer avec Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                userMenu.classList.remove('active');
                userBtn && userBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Burger mobile
    if (burger && navLinks && overlay) {
        burger.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');
            overlay.classList.toggle('open', isOpen);

            // Animation burger → X
            burger.classList.toggle('open', isOpen);
            const spans = burger.querySelectorAll('span');
            if (isOpen) {
                spans[0].style.transform = 'translateY(7px) rotate(45deg)';
                spans[1].style.opacity   = '0';
                spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
            } else {
                spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
            }
        });

        overlay.addEventListener('click', () => {
            navLinks.classList.remove('open');
            overlay.classList.remove('open');
            const spans = burger.querySelectorAll('span');
            spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
        });
    }

    // ======================================================
    // 2. RECHERCHE LIVE (catalogue côté client)
    // ======================================================
    const searchInput  = document.getElementById('search-input');
    const filmsGrid    = document.getElementById('films-grid');

    if (searchInput && filmsGrid) {
        let debounceTimer;

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = searchInput.value.toLowerCase().trim();
                const cards = filmsGrid.querySelectorAll('.film-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.getAttribute('data-title') || '';
                    const match = !query || title.includes(query);
                    card.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                // Afficher/masquer message vide
                let emptyMsg = filmsGrid.querySelector('.live-empty');
                if (visibleCount === 0) {
                    if (!emptyMsg) {
                        emptyMsg = document.createElement('div');
                        emptyMsg.className = 'empty-state live-empty';
                        emptyMsg.style.gridColumn = '1 / -1';
                        emptyMsg.innerHTML = `
                            <div class="empty-state__icon">🔍</div>
                            <h3>Aucun résultat</h3>
                            <p>Aucun film ne correspond à « ${escapeHtml(query)} »</p>
                        `;
                        filmsGrid.appendChild(emptyMsg);
                    }
                } else if (emptyMsg) {
                    emptyMsg.remove();
                }
            }, 250);
        });
    }

    // ======================================================
    // 3. RECHERCHE LIVE ADMIN TABLE
    // ======================================================
    const adminSearch = document.getElementById('admin-search');
    const adminTable  = document.getElementById('admin-films-table');

    if (adminSearch && adminTable) {
        adminSearch.addEventListener('input', () => {
            const query = adminSearch.value.toLowerCase();
            adminTable.querySelectorAll('.admin-table__row').forEach(row => {
                const title = row.getAttribute('data-title') || '';
                row.style.display = title.includes(query) ? '' : 'none';
            });
        });
    }

    // ======================================================
    // 4. TOGGLE MOT DE PASSE (afficher/masquer)
    // ======================================================
    document.querySelectorAll('.form-toggle-pw').forEach(btn => {
        btn.addEventListener('click', () => {
            const wrap  = btn.closest('.form-input-wrap');
            const input = wrap ? wrap.querySelector('input') : null;
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
            } else {
                input.type = 'password';
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
            }
        });
    });

    // ======================================================
    // 5. INDICATEUR FORCE MOT DE PASSE
    // ======================================================
    const pwInput     = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');

    if (pwInput && strengthBar) {
        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            let score = 0;
            if (val.length >= 6)              score++;
            if (val.length >= 10)             score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60'];
            const widths  = ['20%', '40%', '60%', '80%', '100%'];

            strengthBar.style.width      = val.length ? widths[score - 1] || '5%' : '0%';
            strengthBar.style.background = val.length ? colors[score - 1] || '#e74c3c' : 'transparent';
        });
    }

    // Vérification confirmation mot de passe
    const confirmInput = document.getElementById('confirm');
    if (pwInput && confirmInput) {
        const checkMatch = () => {
            if (confirmInput.value && confirmInput.value !== pwInput.value) {
                confirmInput.style.borderColor = '#e74c3c';
            } else {
                confirmInput.style.borderColor = '';
            }
        };
        confirmInput.addEventListener('input', checkMatch);
        pwInput.addEventListener('input', checkMatch);
    }

    // ======================================================
    // 6. UPLOAD IMAGE — aperçu instantané
    // ======================================================
    const fileInput   = document.getElementById('image');
    const previewImg  = document.getElementById('image-preview');
    const uploadZone  = document.getElementById('file-upload-zone');

    if (fileInput && previewImg) {
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewImg.style.maxHeight = '200px';
                previewImg.style.margin = '0.75rem auto 0';
                previewImg.style.borderRadius = '8px';
                previewImg.style.objectFit = 'cover';
                const placeholder = document.getElementById('file-placeholder');
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    // Glisser-déposer sur la zone d'upload
    if (uploadZone && fileInput) {
        uploadZone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover'].forEach(ev => {
            uploadZone.addEventListener(ev, (e) => {
                e.preventDefault();
                uploadZone.style.borderColor = 'var(--or)';
                uploadZone.style.background  = 'rgba(229,185,62,0.04)';
            });
        });

        ['dragleave', 'drop'].forEach(ev => {
            uploadZone.addEventListener(ev, (e) => {
                e.preventDefault();
                uploadZone.style.borderColor = '';
                uploadZone.style.background  = '';
            });
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // ======================================================
    // 7. STAR PICKER — retour visuel interactif
    // ======================================================
    const starPicker = document.getElementById('star-picker');
    if (starPicker) {
        const labels = starPicker.querySelectorAll('label');
        labels.forEach(label => {
            label.addEventListener('mouseenter', () => {
                label.style.transform = 'scale(1.2)';
            });
            label.addEventListener('mouseleave', () => {
                label.style.transform = '';
            });
        });
    }

    // ======================================================
    // 8. SMOOTH SCROLL vers #catalogue
    // ======================================================
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ======================================================
    // 9. NOTIFICATIONS FLASH (auto-dismiss)
    // ======================================================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity    = '0';
            alert.style.transform  = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ======================================================
    // 10. UTILITAIRES
    // ======================================================
    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, m => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[m]));
    }

});
