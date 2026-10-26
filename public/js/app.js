(() => {
    'use strict';
    const API = '/api/v1';
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c]));
    async function request(path, method = 'GET', data = null) {
        const opts = { method, headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } };
        if (data !== null) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(data); }
        const res = await fetch(API + path, opts); let body; const ct = res.headers.get('content-type') || '';
        try { body = ct.includes('json') ? await res.json() : await res.text(); } catch { body = null; }
        if (!res.ok) { let msg = body?.errors ? Object.values(body.errors).flat().join(' ') : body?.message; throw new Error(msg || `Request failed (${res.status}).`); }
        return body;
    }
    function notice(el, msg, type = 'info') { if (!el) return; el.textContent = msg; el.hidden = false; el.className = 'notice notice-' + type; }
    function clearNotice(el) { if (el) { el.hidden = true; el.textContent = ''; } }
    function flash(msg) { try { sessionStorage.setItem('bookFlash', msg); } catch { } }
    function showFlash(el) { try { const msg = sessionStorage.getItem('bookFlash'); if (msg) { sessionStorage.removeItem('bookFlash'); notice(el, msg, 'success'); } } catch { } }
    function data(form) { const d = Object.fromEntries(new FormData(form).entries()); if (d.year_published !== undefined && d.year_published !== '') d.year_published = Number(d.year_published); return d; }
    function busy(form, on, label) { const b = form.querySelector('[type=submit]'); if (!b) return; if (!b.dataset.original) b.dataset.original = b.textContent; b.disabled = on; b.textContent = on ? 'Please wait...' : label || b.dataset.original; }
    async function loadBooks() {
        const tbody = document.getElementById('bookTableBody'), n = document.getElementById('bookNotice'); if (!tbody) return;
        const search = (document.getElementById('search')?.value || '').trim();
        tbody.innerHTML = '<tr><td colspan="6" class="empty-state">Loading books...</td></tr>';
        try {
            const rows = await request('/books' + (search ? '?search=' + encodeURIComponent(search) : '')); if (!Array.isArray(rows)) throw new Error('Expected a JSON array from /api/v1/books.');
            if (!rows.length) { tbody.innerHTML = `<tr><td colspan="6" class="empty-state">${search ? 'No books match "' + esc(search) + '".' : 'No books found. Add the first book.'}</td></tr>`; return; }
            tbody.innerHTML = rows.map(b => `<tr><td>${esc(b.id)}</td><td>${esc(b.title)}</td><td>${esc(b.author)}</td><td>${esc(b.genre)}</td><td>${esc(b.year_published)}</td><td class="actions-cell"><a class="btn btn-small btn-secondary" href="/books/${encodeURIComponent(b.id)}">View</a> <a class="btn btn-small" href="/books/${encodeURIComponent(b.id)}/edit">Edit</a> <button class="btn btn-small btn-danger" type="button" data-delete-book="${esc(b.id)}">Delete</button></td></tr>`).join('');
        } catch (e) { tbody.innerHTML = '<tr><td colspan="6" class="empty-state">Could not load books.</td></tr>'; notice(n, e.message, 'error'); }
    }
    async function deleteBook(id, detail = false) { if (!confirm('Delete this book permanently? This cannot be undone.')) return; const n = document.getElementById('bookNotice'); try { const r = await request('/books/' + encodeURIComponent(id), 'DELETE'); if (detail) { flash(r.message || 'Book deleted successfully.'); location.href = '/books'; return; } notice(n, r.message || 'Book deleted successfully.', 'success'); await loadBooks(); } catch (e) { notice(n, e.message, 'error'); } }
    function fill(b) { ['title', 'author', 'genre', 'year_published'].forEach(k => { const el = document.getElementById(k); if (el) el.value = b[k] ?? ''; }); }
    document.addEventListener('DOMContentLoaded', async () => {
        const page = document.body.dataset.page, id = document.body.dataset.bookId;
        if (page === 'books-index') { showFlash(document.getElementById('bookNotice')); loadBooks(); document.getElementById('refreshBooks')?.addEventListener('click', loadBooks); document.getElementById('searchForm')?.addEventListener('submit', e => { e.preventDefault(); loadBooks(); }); document.getElementById('clearSearch')?.addEventListener('click', () => { document.getElementById('search').value = ''; loadBooks(); }); document.getElementById('bookTableBody')?.addEventListener('click', e => { const b = e.target.closest('[data-delete-book]'); if (b) deleteBook(b.dataset.deleteBook); }); }
        if (page === 'books-create') { const f = document.getElementById('bookForm'), n = document.getElementById('bookNotice'); f?.addEventListener('submit', async e => { e.preventDefault(); clearNotice(n); if (!f.reportValidity()) return; busy(f, true, 'Save Book'); try { const r = await request('/books', 'POST', data(f)); flash(r.message || 'Book created successfully.'); location.href = '/books'; } catch (err) { notice(n, err.message, 'error'); busy(f, false, 'Save Book'); } }); }
        if (page === 'books-edit' && id) { const f = document.getElementById('bookForm'), n = document.getElementById('bookNotice'); try { fill(await request('/books/' + encodeURIComponent(id))); const cancel = document.getElementById('cancelEditLink'); if (cancel) cancel.href = '/books/' + encodeURIComponent(id); } catch (e) { notice(n, 'Could not load book: ' + e.message, 'error'); return; } f?.addEventListener('submit', async e => { e.preventDefault(); clearNotice(n); if (!f.reportValidity()) return; busy(f, true, 'Save Changes'); try { const r = await request('/books/' + encodeURIComponent(id), 'PUT', data(f)); flash(r.message || 'Book updated successfully.'); location.href = '/books/' + encodeURIComponent(id); } catch (err) { notice(n, err.message, 'error'); busy(f, false, 'Save Changes'); } }); }
        if (page === 'books-show' && id) { const d = document.getElementById('bookDetails'), n = document.getElementById('bookNotice'); showFlash(n); const edit = document.getElementById('editBookLink'); if (edit) edit.href = '/books/' + encodeURIComponent(id) + '/edit'; document.getElementById('deleteBookButton')?.addEventListener('click', () => deleteBook(id, true)); try { const b = await request('/books/' + encodeURIComponent(id)); d.innerHTML = [['Title', b.title], ['Author', b.author], ['Genre', b.genre], ['Year Published', b.year_published], ['Created', b.created_at ? new Date(b.created_at).toLocaleString() : '-'], ['Last Updated', b.updated_at ? new Date(b.updated_at).toLocaleString() : '-']].map(([k, v]) => `<div class="detail-item"><strong>${esc(k)}</strong><span>${esc(v)}</span></div>`).join(''); } catch (e) { d.innerHTML = '<p class="empty-state">Book details could not be loaded.</p>'; notice(n, e.message, 'error'); } }
    });
})();
