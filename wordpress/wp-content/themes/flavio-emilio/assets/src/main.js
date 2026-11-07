// JS do tema (compilado com esbuild para assets/dist/main.js).
// Carrossel (Embla), voltar ao topo etc. entram na fase 5.

function initHeader() {
  const header = document.querySelector('[data-fec-header]');
  if (!header) return;

  const alwaysSolid = header.dataset.fixed === '1';
  const update = () => {
    if (alwaysSolid || window.scrollY > 20) header.dataset.solid = '1';
    else delete header.dataset.solid;
  };
  update();
  window.addEventListener('scroll', update, { passive: true });
}

function initMobileMenu() {
  const toggle = document.querySelector('[data-fec-menu-toggle]');
  const menu = document.getElementById('fec-mobile-menu');
  if (!toggle || !menu) return;

  const setOpen = (open) => {
    menu.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
  };

  toggle.addEventListener('click', () => setOpen(menu.hidden));
  menu.addEventListener('click', (event) => {
    if (event.target.closest('a')) setOpen(false);
  });
}

initHeader();
initMobileMenu();
