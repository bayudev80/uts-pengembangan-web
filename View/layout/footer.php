    </main>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <strong>Sewa Jas</strong> &bull; Sistem Persewaan & Manajemen Jas Modern</p>
    </footer>

    <!-- Dropdown menu akun -->
    <script>
        (function () {
            const menu = document.getElementById('userMenu');
            if (!menu) return;
            const toggle = document.getElementById('userMenuToggle');
            const panel = document.getElementById('userMenuPanel');

            function setOpen(open) {
                panel.hidden = !open;
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                menu.classList.toggle('open', open);
            }

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                setOpen(panel.hidden);
            });
            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) setOpen(false);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !panel.hidden) {
                    setOpen(false);
                    toggle.focus();
                }
            });
        })();
    </script>

    <!-- Auto-dismiss notifikasi dan proteksi input angka -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.classList.add('fade-out');
                    setTimeout(function () {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }, 3000);
            });

            // Cegah pengetikan huruf pada input angka
            document.querySelectorAll('input[inputmode="numeric"]').forEach(function (input) {
                input.addEventListener('keypress', function (e) {
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            });

            // Fitur pencarian dan paginasi (slide data maks 5 per halaman)
            document.querySelectorAll('.card').forEach(function (card) {
                const table = card.querySelector('table');
                const paginationEl = card.querySelector('.table-pagination');
                if (!table || !paginationEl) return;

                const tbody = table.querySelector('tbody');
                if (!tbody) return;

                const allRows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.querySelector('.empty-state'));
                if (allRows.length === 0) {
                    paginationEl.style.display = 'none';
                    return;
                }

                const searchInput = card.querySelector('.table-search-input');
                const pageSize = parseInt(paginationEl.getAttribute('data-page-size') || '5', 10);
                const pageStartEl = paginationEl.querySelector('.page-start');
                const pageEndEl = paginationEl.querySelector('.page-end');
                const pageTotalEl = paginationEl.querySelector('.page-total');
                const prevBtn = paginationEl.querySelector('.btn-prev');
                const nextBtn = paginationEl.querySelector('.btn-next');
                const pageNumbersEl = paginationEl.querySelector('.page-numbers');

                let filteredRows = [...allRows];
                let currentPage = 1;

                // Baris notifikasi saat pencarian nihil
                let emptySearchRow = tbody.querySelector('.search-empty-row');
                if (!emptySearchRow) {
                    emptySearchRow = document.createElement('tr');
                    emptySearchRow.className = 'search-empty-row';
                    emptySearchRow.style.display = 'none';
                    const colCount = table.querySelectorAll('thead th').length || 5;
                    emptySearchRow.innerHTML = `<td colspan="${colCount}" style="text-align:center; padding: 2.5rem 1rem; color: var(--text-muted);">
                        <div style="font-size: 1.5rem; margin-bottom: 0.35rem; opacity: 0.6;"><i class="bi bi-search"></i></div>
                        <strong style="color: var(--text-heading);">Tidak ada data yang cocok</strong>
                        <p style="font-size: 0.8rem; margin-top: 4px; margin-bottom: 0;">Silakan coba kata kunci pencarian yang lain.</p>
                    </td>`;
                    tbody.appendChild(emptySearchRow);
                }

                function render() {
                    const totalItems = filteredRows.length;
                    const totalPages = Math.ceil(totalItems / pageSize) || 1;

                    if (currentPage > totalPages) currentPage = totalPages;
                    if (currentPage < 1) currentPage = 1;

                    const start = (currentPage - 1) * pageSize;
                    const end = Math.min(start + pageSize, totalItems);

                    allRows.forEach(r => {
                        r.style.display = 'none';
                        r.classList.remove('row-visible');
                    });

                    if (totalItems === 0) {
                        emptySearchRow.style.display = '';
                        if (pageStartEl) pageStartEl.textContent = '0';
                        if (pageEndEl) pageEndEl.textContent = '0';
                        if (pageTotalEl) pageTotalEl.textContent = '0';
                        if (prevBtn) prevBtn.disabled = true;
                        if (nextBtn) nextBtn.disabled = true;
                        if (pageNumbersEl) pageNumbersEl.innerHTML = '';
                        return;
                    }

                    emptySearchRow.style.display = 'none';

                    for (let i = start; i < end; i++) {
                        filteredRows[i].style.display = '';
                        filteredRows[i].classList.add('row-visible');
                    }

                    if (pageStartEl) pageStartEl.textContent = start + 1;
                    if (pageEndEl) pageEndEl.textContent = end;
                    if (pageTotalEl) pageTotalEl.textContent = totalItems;

                    if (prevBtn) prevBtn.disabled = (currentPage === 1);
                    if (nextBtn) nextBtn.disabled = (currentPage === totalPages);

                    if (pageNumbersEl) {
                        pageNumbersEl.innerHTML = '';
                        for (let p = 1; p <= totalPages; p++) {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn-page' + (p === currentPage ? ' active' : '');
                            btn.textContent = p;
                            btn.addEventListener('click', () => {
                                currentPage = p;
                                render();
                            });
                            pageNumbersEl.appendChild(btn);
                        }
                    }
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        if (currentPage > 1) {
                            currentPage--;
                            render();
                        }
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        const totalPages = Math.ceil(filteredRows.length / pageSize) || 1;
                        if (currentPage < totalPages) {
                            currentPage++;
                            render();
                        }
                    });
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        const q = this.value.toLowerCase().trim();
                        filteredRows = !q ? [...allRows] : allRows.filter(r => r.textContent.toLowerCase().includes(q));
                        currentPage = 1;
                        render();
                    });
                }

                render();
            });
        });
    </script>

</body>
</html>
