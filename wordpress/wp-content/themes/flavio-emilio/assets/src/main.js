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

/* ---------------------------------------------------------------------------
 * Blog (docs/plano-blog.md)
 * ------------------------------------------------------------------------ */

/** Barra dourada no topo que acompanha a leitura do texto do post. */
function initReadingProgress() {
  const bar = document.querySelector('[data-fec-progress]');
  const content = document.querySelector('[data-fec-content]');
  if (!bar || !content) return;

  let ticking = false;
  const update = () => {
    const rect = content.getBoundingClientRect();
    const total = rect.height - window.innerHeight * 0.6;
    const read = Math.min(Math.max(-rect.top + window.innerHeight * 0.2, 0), Math.max(total, 1));
    bar.style.transform = `scaleX(${(read / Math.max(total, 1)).toFixed(3)})`;
    ticking = false;
  };
  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  }, { passive: true });
  update();
}

/** Índice lateral: marca a seção que está sendo lida. */
function initTocHighlight() {
  const links = [...document.querySelectorAll('[data-fec-toc] a')];
  if (!links.length || !('IntersectionObserver' in window)) return;

  const byId = new Map(links.map((a) => [decodeURIComponent(a.hash.slice(1)), a]));
  const headings = [...byId.keys()].map((id) => document.getElementById(id)).filter(Boolean);
  const visible = new Set();

  const mark = () => {
    // A seção atual é o último subtítulo que já passou do topo (ou o primeiro visível).
    let current = headings[0];
    for (const h of headings) {
      if (h.getBoundingClientRect().top < window.innerHeight * 0.3) current = h;
    }
    links.forEach((a) => {
      if (byId.get(current.id) === a) a.dataset.active = '1';
      else delete a.dataset.active;
    });
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((e) => (e.isIntersecting ? visible.add(e.target) : visible.delete(e.target)));
    mark();
  }, { rootMargin: '0px 0px -60% 0px' });
  headings.forEach((h) => observer.observe(h));
  window.addEventListener('scroll', mark, { passive: true });
  mark();
}

/** Compartilhar: menu nativo do aparelho (quando existe) e copiar link. */
function initShare() {
  document.querySelectorAll('[data-fec-share]').forEach((box) => {
    const { url, title, text } = box.dataset;
    const status = box.querySelector('[data-fec-share-status]');

    const native = box.querySelector('[data-fec-share-native]');
    if (native && navigator.share) {
      native.hidden = false;
      native.addEventListener('click', async () => {
        try {
          await navigator.share({ title, text, url });
        } catch {
          /* usuário cancelou */
        }
      });
    }

    const copy = box.querySelector('[data-fec-share-copy]');
    const label = box.querySelector('[data-fec-share-copy-label]');
    copy?.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(url);
      } catch {
        // Navegadores sem clipboard API (ou http): seleção manual como alternativa.
        const input = Object.assign(document.createElement('input'), { value: url });
        document.body.append(input);
        input.select();
        document.execCommand('copy');
        input.remove();
      }
      const original = label.textContent;
      label.textContent = 'Link copiado!';
      if (status) status.textContent = 'Link copiado para a área de transferência.';
      setTimeout(() => {
        label.textContent = original;
        if (status) status.textContent = '';
      }, 2500);
    });
  });
}

/** Inscrição na newsletter (template-parts/newsletter.php → /wp-json/fec/v1/newsletter). */
function initNewsletter() {
  document.querySelectorAll('[data-fec-newsletter]').forEach((form) => {
    const status = form.querySelector('[data-fec-newsletter-status]');
    const button = form.querySelector('[type=submit]');
    const startedAt = Date.now();

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const email = form.elements.email;
      const aceite = form.elements.aceite;

      if (!email.value || !email.checkValidity()) {
        status.textContent = 'Confira o e-mail digitado.';
        email.focus();
        return;
      }
      if (!aceite.checked) {
        status.textContent = 'Para se inscrever, marque a caixa de concordância.';
        aceite.focus();
        return;
      }

      button.disabled = true;
      status.textContent = 'Enviando…';
      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            email: email.value.trim(),
            nome: form.elements.nome?.value.trim() || '',
            aceite: true,
            site: form.elements.site.value,
            iniciado: startedAt,
            origem: form.dataset.origem,
          }),
        });
        const data = await res.json();
        status.textContent = data.message || 'Não foi possível concluir. Tente novamente.';
        if (data.ok) {
          form.querySelectorAll('input, button').forEach((el) => { el.disabled = true; });
          form.reset();
        } else {
          button.disabled = false;
        }
      } catch {
        status.textContent = 'Sem conexão no momento. Tente novamente.';
        button.disabled = false;
      }
    });
  });
}

initHeader();
initMobileMenu();
initNewsletter();
initScrollToTop();
initCarousels();
initReadingProgress();
initTocHighlight();
initShare();
