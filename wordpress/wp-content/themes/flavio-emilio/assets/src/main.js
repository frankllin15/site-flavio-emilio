// JS do tema (compilado com esbuild para assets/dist/main.js).
// Carrossel (Embla) entra na fase 5.

function initHeader() {
  const header = document.querySelector('[data-fec-header]');
  if (!header) return;

  const alwaysSolid = header.dataset.alwaysSolid === '1';
  const update = () => {
    if (alwaysSolid || window.scrollY > 20) header.dataset.solid = '1';
    else delete header.dataset.solid;
  };
  update();
  window.addEventListener('scroll', update, { passive: true });
}

function initMobileMenu() {
  const openBtn = document.querySelector('[data-fec-menu-open]');
  const menu = document.querySelector('[data-fec-menu]');
  const overlay = document.querySelector('[data-fec-menu-overlay]');
  if (!openBtn || !menu || !overlay) return;

  const setOpen = (open) => {
    if (open) menu.dataset.open = '1';
    else delete menu.dataset.open;
    menu.toggleAttribute('inert', !open);
    menu.setAttribute('aria-hidden', String(!open));
    overlay.hidden = !open;
    openBtn.setAttribute('aria-expanded', String(open));
    if (open) menu.querySelector('[data-fec-menu-close]')?.focus();
    else openBtn.focus({ preventScroll: true });
  };

  openBtn.addEventListener('click', () => setOpen(true));
  overlay.addEventListener('click', () => setOpen(false));
  menu.querySelector('[data-fec-menu-close]')?.addEventListener('click', () => setOpen(false));
  menu.addEventListener('click', (event) => {
    if (event.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menu.dataset.open) setOpen(false);
  });
}

function initScrollToTop() {
  const button = document.querySelector('[data-fec-to-top]');
  if (!button) return;

  const update = () => {
    button.hidden = window.scrollY <= 300;
  };
  update();
  window.addEventListener('scroll', update, { passive: true });
  button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

initHeader();
initMobileMenu();
initScrollToTop();
