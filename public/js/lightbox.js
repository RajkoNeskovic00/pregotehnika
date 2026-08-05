document.addEventListener('DOMContentLoaded', () => {
    const links = [...document.querySelectorAll('a.lightbox')];
    const overlay = document.getElementById('lightbox');
    if (!overlay || !links.length) return;

    const img = overlay.querySelector('img');
    const prevBtn = overlay.querySelector('.lightbox-prev');
    const nextBtn = overlay.querySelector('.lightbox-next');
    let index = 0;

    function open(i) {
        index = i;
        img.src = links[index].href;
        img.alt = links[index].querySelector('img')?.alt || '';
        overlay.hidden = false;
        document.body.style.overflow = 'hidden';

        const multi = links.length > 1;
        if (prevBtn) prevBtn.hidden = !multi;
        if (nextBtn) nextBtn.hidden = !multi;
    }

    function close() {
        overlay.hidden = true;
        img.src = '';
        document.body.style.overflow = '';
    }

    function show(delta) {
        index = (index + delta + links.length) % links.length;
        open(index);
    }

    links.forEach((a, i) => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            open(i);
        });
    });

    overlay.querySelector('.lightbox-close').addEventListener('click', close);
    if (prevBtn) prevBtn.addEventListener('click', () => show(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => show(1));

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) close();
    });

    document.addEventListener('keydown', (e) => {
        if (overlay.hidden) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft' && links.length > 1) show(-1);
        if (e.key === 'ArrowRight' && links.length > 1) show(1);
    });
});
