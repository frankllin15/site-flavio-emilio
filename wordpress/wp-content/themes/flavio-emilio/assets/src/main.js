// JS do tema (compilado com esbuild para assets/dist/main.js).
import EmblaCarousel from 'embla-carousel';

/**
 * Carrossel (components/ui/Carousel.tsx): setas, dots, arrastar/swipe e teclado.
 * Markup gerado por fec_carousel_start()/fec_carousel_end() em inc/template-tags.php.
 * As classes dos dots precisam aparecer literalmente aqui para o Tailwind gerá-las.
 */
// O ponto visível (8px) fica num <span>; o <button> em volta tem 24px de altura e folga lateral,
// para o toque no celular (alvo mínimo de 24px). Os pontos ficam 16px afastados (8px no Next).
const DOT_BUTTON = 'group/dot flex h-6 items-center px-1 cursor-pointer';
const DOT_BASE = 'block h-2 rounded-full transition-all duration-300';
const DOT_ACTIVE = 'w-8 bg-accent-gold-500';
const DOT_INACTIVE = 'w-2 bg-gray-300 group-hover/dot:bg-gray-400';

function initCarousel(root) {
  const viewport = root.querySelector('[data-fec-viewport]');
  const prev = root.querySelector('[data-fec-prev]');
  const next = root.querySelector('[data-fec-next]');
  const dotsEl = root.querySelector('[data-fec-dots]');
  if (!viewport) return;

  const embla = EmblaCarousel(viewport, {
    loop: true,
    align: 'start',
    dragFree: false,
    containScroll: 'trimSnaps',
  });

  let dots = [];

  const buildDots = () => {
    if (!dotsEl) return;
    const snaps = embla.scrollSnapList();
    dotsEl.replaceChildren();
    // Com uma página só não há o que navegar: sem dots.
    dots = snaps.length < 2 ? [] : snaps.map((_, index) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = DOT_BUTTON;
      button.setAttribute('aria-label', `Ir para página ${index + 1}`);
      button.addEventListener('click', () => embla.scrollTo(index));
      button.append(document.createElement('span'));
      dotsEl.append(button);
      return button;
    });
  };

  const update = () => {
    const selected = embla.selectedScrollSnap();
    dots.forEach((button, index) => {
      const active = index === selected;
      button.firstChild.className = `${DOT_BASE} ${active ? DOT_ACTIVE : DOT_INACTIVE}`;
      if (active) button.setAttribute('aria-current', 'true');
      else button.removeAttribute('aria-current');
    });
    if (prev) prev.disabled = !embla.canScrollPrev();
    if (next) next.disabled = !embla.canScrollNext();
  };

  prev?.addEventListener('click', () => embla.scrollPrev());
  next?.addEventListener('click', () => embla.scrollNext());

  // Setas do teclado só quando o foco está dentro deste carrossel
  // (no Next o listener era global e movia todos os carrosséis da página ao mesmo tempo).
  root.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
      event.preventDefault();
      embla.scrollPrev();
    } else if (event.key === 'ArrowRight') {
      event.preventDefault();
      embla.scrollNext();
    }
  });

  embla.on('select', update);
  embla.on('reInit', () => {
    buildDots();
    update();
  });

  buildDots();
  update();
  root.dataset.ready = '1';
}

function initCarousels() {
  document.querySelectorAll('[data-fec-carousel]').forEach(initCarousel);
}

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
initCarousels();
