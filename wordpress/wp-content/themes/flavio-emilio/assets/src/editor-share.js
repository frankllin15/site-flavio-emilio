/**
 * Caixa "Divulgar este post" na lateral do editor (docs/plano-blog.md, fase B6).
 *
 * LinkedIn e WhatsApp não permitem postagem automática confiável, então a caixa
 * deixa a divulgação a dois cliques: texto pronto (editável), link com UTM por canal e botões
 * que abrem LinkedIn/WhatsApp já preenchidos. Usa só as bibliotecas que o editor já carrega (wp.*).
 *
 * Configuração vinda do PHP (inc/editor.php): window.fecShare = { blog, campanha }.
 */
(function (wp) {
  const { registerPlugin } = wp.plugins;
  const PluginDocumentSettingPanel =
    (wp.editor && wp.editor.PluginDocumentSettingPanel) || wp.editPost.PluginDocumentSettingPanel;
  const { createElement: el, useState, useEffect } = wp.element;
  const { useSelect } = wp.data;
  const { Button, TextareaControl, Notice } = wp.components;

  const cfg = window.fecShare || {};

  /** Link do post com UTM do canal (para medir de onde vêm as visitas no Analytics). */
  const withUtm = (url, source) => {
    const u = new URL(url);
    u.searchParams.set('utm_source', source);
    u.searchParams.set('utm_medium', 'social');
    u.searchParams.set('utm_campaign', cfg.campanha || 'drops-de-carreira');
    return u.toString();
  };

  /** Primeiras duas frases do resumo (ou do texto). */
  const twoSentences = (text) => {
    const clean = (text || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
    const parts = clean.match(/[^.!?]+[.!?]+/g) || [clean];
    return parts.slice(0, 2).join(' ').trim();
  };

  const buildText = (title, summary, link) =>
    `${title}\n\n${summary}\n\nLeia no ${cfg.blog || 'blog'}: ${link}`;

  function CopyButton({ text, label }) {
    const [done, setDone] = useState(false);
    return el(
      Button,
      {
        variant: 'secondary',
        onClick: async () => {
          await navigator.clipboard.writeText(text);
          setDone(true);
          setTimeout(() => setDone(false), 2000);
        },
      },
      done ? 'Copiado!' : label
    );
  }

  function SharePanel() {
    const { status, title, link, excerpt, content } = useSelect((select) => {
      const ed = select('core/editor');
      return {
        status: ed.getEditedPostAttribute('status'),
        title: ed.getEditedPostAttribute('title'),
        link: ed.getPermalink(),
        excerpt: ed.getEditedPostAttribute('excerpt'),
        content: ed.getEditedPostContent(),
      };
    }, []);

    const summary = twoSentences(excerpt || content);
    const auto = (source) => buildText(title, summary, withUtm(link, source));
    const [text, setText] = useState('');
    const [edited, setEdited] = useState(false);

    // Texto acompanha título/resumo enquanto o autor não editar à mão.
    useEffect(() => {
      if (!edited && link) setText(auto('linkedin'));
    }, [title, summary, link]); // eslint-disable-line react-hooks/exhaustive-deps

    if (status !== 'publish') {
      return el(
        PluginDocumentSettingPanel,
        { name: 'fec-share', title: 'Divulgar este post', icon: 'megaphone' },
        el('p', null, 'Depois de publicar, aqui aparece o texto pronto e os botões para divulgar no LinkedIn e no WhatsApp.')
      );
    }

    // O mesmo texto, trocando só o link (UTM) para cada canal.
    const linkedinLink = withUtm(link, 'linkedin');
    const forSource = (source) => text.split(linkedinLink).join(withUtm(link, source));

    const linkedinUrl = 'https://www.linkedin.com/feed/?shareActive=true&text=' + encodeURIComponent(text);
    const whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(forSource('whatsapp'));

    return el(
      PluginDocumentSettingPanel,
      { name: 'fec-share', title: 'Divulgar este post', icon: 'megaphone' },
      el(TextareaControl, {
        label: 'Texto pronto (pode editar)',
        value: text,
        rows: 9,
        onChange: (v) => {
          setText(v);
          setEdited(true);
        },
      }),
      edited &&
        el(
          Button,
          { variant: 'link', onClick: () => { setEdited(false); setText(auto('linkedin')); } },
          'Voltar ao texto automático'
        ),
      el(
        'div',
        { style: { display: 'flex', flexDirection: 'column', gap: '8px', marginTop: '12px' } },
        el(Button, { variant: 'primary', href: linkedinUrl, target: '_blank', rel: 'noopener noreferrer' }, 'Publicar no LinkedIn'),
        el(Button, { variant: 'secondary', href: whatsappUrl, target: '_blank', rel: 'noopener noreferrer' }, 'Enviar no WhatsApp'),
        el(CopyButton, { text, label: 'Copiar texto' })
      ),
      el(
        Notice,
        { status: 'info', isDismissible: false, className: 'fec-share-notice' },
        'Os links levam UTM por canal (linkedin, whatsapp) para medir de onde vêm as visitas.'
      )
    );
  }

  registerPlugin('fec-share', { render: SharePanel });
})(window.wp);
