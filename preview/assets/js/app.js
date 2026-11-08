/**
 * Abidar Group — static live preview.
 *
 * A dependency-free mirror of the WordPress theme: the same copy, the same
 * stylesheets, the same section order. Three things are interactive:
 *   1. Language switch (Persian / English / Chinese) — swaps copy, direction and fonts.
 *   2. The Elementor widget inspector — shows every control the matching widget exposes.
 *   3. Working in-page navigation between the theme's pages.
 */
(function () {
  'use strict';

  /* Older engines / test environments may not expose rAF. */
  var raf = window.requestAnimationFrame || function (cb) {
    return setTimeout(function () { cb(Date.now()); }, 16);
  };

  var D = window.ABIDAR_DATA || {};
  var IMG = "../assets/img/";

  /* ————————————————————————————————————————————————
     State
     ———————————————————————————————————————————————— */
  var state = {
    lang: 'fa',
    page: 'home',
    widgetsOpen: false
  };

  var LANGS = {
    fa: { html: 'fa-IR', dir: 'rtl', label: 'فارسی', short: 'FA' },
    en: { html: 'en', dir: 'ltr', label: 'English', short: 'EN' },
    zh: { html: 'zh-CN', dir: 'ltr', label: '中文', short: 'ZH' }
  };

  /* ————————————————————————————————————————————————
     Helpers
     ———————————————————————————————————————————————— */
  function t(key) {
    var entry = D.strings && D.strings[key];
    if (!entry) return key;
    return entry[state.lang] || entry.en || key;
  }

  function loc(obj, key) {
    var v = obj[key];
    if (typeof v === 'string') return v;
    return v[state.lang] || v.en || '';
  }

  function icon(name, size) {
    var paths = (D.icons && D.icons[name]) || D.icons.check;
    return '<svg class="ab-icon" width="' + (size || 24) + '" height="' + (size || 24) + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + paths + '</svg>';
  }

  function esc(str) {
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function arrow() {
    return icon('arrow-right', 16);
  }

  /* Elementor control badges: `name` is the CSS handle shown in the real widget. */
  function elBadge(name) {
    return '<span class="ab-el-badge" title="Elementor control">' + esc(name) + '</span>';
  }

  function sectionHead(opts) {
    var cls = opts.center ? 'ab-section-head ab-section-head--center' : 'ab-section-head ab-section-head--split';
    var html = '<div class="' + cls + ' ab-reveal">';
    html += '<div>';
    if (opts.eyebrow) html += '<span class="ab-eyebrow">' + esc(opts.eyebrow) + '</span>';
    html += '<h2>' + esc(opts.title) + '</h2>';
    if (opts.text) html += '<p>' + esc(opts.text) + '</p>';
    html += '</div>';
    if (opts.link) {
      html += '<a class="ab-link-arrow" href="#" data-page="' + opts.link.page + '"><span>' + esc(t('view_all')) + '</span>' + arrow() + '</a>';
    }
    html += '</div>';
    return html;
  }

  /* ————————————————————————————————————————————————
     Sections (1:1 with the PHP section renderers + Elementor widgets)
     ———————————————————————————————————————————————— */

  function secHero() {
    return ''
      + '<section class="ab-hero" id="top">'
      + '  <div class="ab-hero__bg"><img src="' + IMG + 'hero-mine.jpg" alt="" fetchpriority="high"></div>'
      + '  <div class="ab-container ab-hero__inner">'
      + '    <div class="ab-hero__content">'
      + '      <span class="ab-eyebrow ab-eyebrow--light ab-reveal">' + esc(t('hero_eyebrow')) + '</span>'
      + '      <h1 class="ab-hero__title ab-reveal">' + esc(t('hero_title')) + '</h1>'
      + '      <p class="ab-hero__text ab-reveal">' + esc(t('hero_text')) + '</p>'
      + '      <div class="ab-hero__actions ab-reveal">'
      + '        <a class="ab-btn ab-btn--accent ab-btn--lg" href="#" data-page="projects"><span>' + esc(t('hero_cta1')) + '</span>' + arrow() + '</a>'
      + '        <a class="ab-btn ab-btn--light ab-btn--lg" href="#contact">' + icon('phone', 16) + '<span>' + esc(t('hero_cta2')) + '</span></a>'
      + '      </div>'
      + '    </div>'
      + '    <div class="ab-hero__stats ab-reveal">'
      + '      <div class="ab-hero__stat"><span class="ab-hero__stat-num">248+</span><span class="ab-hero__stat-label">' + esc(t('hero_stat1')) + '</span></div>'
      + '      <div class="ab-hero__stat"><span class="ab-hero__stat-num">22</span><span class="ab-hero__stat-label">' + esc(t('hero_stat2')) + '</span></div>'
      + '      <div class="ab-hero__stat"><span class="ab-hero__stat-num">412</span><span class="ab-hero__stat-label">' + esc(t('hero_stat3')) + '</span></div>'
      + '      <div class="ab-hero__stat"><span class="ab-hero__stat-num">9</span><span class="ab-hero__stat-label">' + esc(t('hero_stat4')) + '</span></div>'
      + '    </div>'
      + '  </div>'
      + '  <span class="ab-hero__scroll" aria-hidden="true">' + esc(t('scroll')) + '</span>'
      + '</section>';
  }

  function secMarquee() {
    var items = ['Sarvestan Cement', 'National Gas Co.', 'Zagros Minerals', 'Shanghai Stone Co.', 'Petrop Pars', 'Alborz Kansar', 'Persian Gulf Terminal', 'Abidar Stone'];
    return ''
      + '<div class="ab-section ab-section--tight">'
      + '  <div class="ab-container"><p class="ab-center ab-muted" style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.25rem">' + esc(t('clients_title')) + '</p></div>'
      + '  <div class="ab-marquee"><div class="ab-marquee__track">'
      + items.map(function (name) {
        return '<span class="ab-marquee__item">' + icon('mountain', 20) + esc(name) + '</span>';
      }).join('')
      + '  </div></div>'
      + '</div>';
  }

  function secServices() {
    var cards = (D.services || []).map(function (s, i) {
      return ''
        + '<article class="ab-card ab-service ab-reveal" style="--ab-i:' + i + '">'
        + '  <span class="ab-service__icon">' + icon(s.icon) + '</span>'
        + '  <span class="ab-service__num">0' + (i + 1) + '</span>'
        + '  <h3 class="ab-service__title">' + esc(loc(s, 'title')) + '</h3>'
        + '  <p class="ab-service__text">' + esc(loc(s, 'text')) + '</p>'
        + '  <ul class="ab-service__list">'
        + s.items.map(function (it) {
          return '<li>' + icon('check', 15) + '<span>' + esc(it[state.lang] || it.en) + '</span></li>';
        }).join('')
        + '  </ul>'
        + '  <a class="ab-service__more" href="#services">' + esc(t('learn_more')) + arrow() + '</a>'
        + '</article>';
    }).join('');

    return ''
      + '<section class="ab-section ab-section--surface" id="services">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('services_eyebrow'), title: t('services_title'), text: t('services_text'), link: { page: 'services' } })
      + '    <div class="ab-grid ab-grid--4 ab-stagger">' + cards + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secAbout() {
    var points = [t('about_point1'), t('about_point2'), t('about_point3')];
    return ''
      + '<section class="ab-section" id="about">'
      + '  <div class="ab-container"><div class="ab-about">'
      + '    <div class="ab-about__media ab-reveal">'
      + '      <img src="' + IMG + 'about.jpg" alt="">'
      + '      <div class="ab-badge"><span class="ab-badge__num">' + esc(t('about_badge_num')) + '</span><span class="ab-badge__text">' + esc(t('about_badge_txt')) + '</span></div>'
      + '    </div>'
      + '    <div class="ab-reveal">'
      + '      <span class="ab-eyebrow">' + esc(t('about_eyebrow')) + '</span>'
      + '      <h2>' + esc(t('about_title')) + '</h2>'
      + '      <p class="ab-lead">' + esc(t('about_text')) + '</p>'
      + '      <ul class="ab-checklist">'
      + points.map(function (p) {
        return '<li><span class="ab-checklist__icon">' + icon('check', 13) + '</span><span>' + esc(p) + '</span></li>';
      }).join('')
      + '      </ul>'
      + '      <a class="ab-btn ab-btn--primary" href="#" data-page="about"><span>' + esc(t('learn_more')) + '</span>' + arrow() + '</a>'
      + '      <div class="ab-signature">'
      + '        <span class="ab-quote__avatar">RA</span>'
      + '        <span><span class="ab-signature__name">' + esc(t('about_sig')) + '</span><br><span class="ab-signature__role">' + esc(t('about_sig_role')) + '</span></span>'
      + '      </div>'
      + '    </div>'
      + '  </div></div>'
      + '</section>';
  }

  function secCounters() {
    var items = (D.stats || []).map(function (s, i) {
      return ''
        + '<div class="ab-counter ab-reveal" style="--ab-i:' + i + '">'
        + '  <span class="ab-counter__icon">' + icon(s.icon, 26) + '</span>'
        + '  <span class="ab-counter__num"><span data-counter="' + esc(s.number) + '">0</span><span>' + esc(s.suffix) + '</span></span>'
        + '  <span class="ab-counter__label">' + esc(loc(s, 'label')) + '</span>'
        + '</div>';
    }).join('');

    return ''
      + '<section class="ab-section ab-section--dark ab-section--pattern" style="--ab-pattern:url(' + IMG + 'pattern-dark.jpg)" id="numbers">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('numbers_eyebrow'), title: t('numbers_title') })
      + '    <div class="ab-counters ab-stagger">' + items + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secProjects() {
    var projects = (D.projects || []).slice(0, 6);
    var cats = {};
    projects.forEach(function (p) {
      cats[slug(loc(p, 'category'))] = loc(p, 'category');
    });

    var filters = '<div class="ab-filters" data-filter-group data-filter-target="#preview-projects">'
      + '<button class="ab-filter is-active" type="button" data-filter="*">' + esc(t('all')) + '</button>'
      + Object.keys(cats).map(function (k) {
        return '<button class="ab-filter" type="button" data-filter="' + esc(k) + '">' + esc(cats[k]) + '</button>';
      }).join('')
      + '</div>';

    var cards = projects.map(function (p, i) {
      return ''
        + '<a class="ab-project ab-reveal ' + (i === 0 ? 'ab-project--feature' : '') + '" href="#projects" data-cat="' + esc(slug(loc(p, 'category'))) + '">'
        + '  <img src="' + IMG + p.image + '" alt="' + esc(loc(p, 'title')) + '" loading="lazy">'
        + '  <span class="ab-project__arrow">' + icon('arrow-up-right', 18) + '</span>'
        + '  <div class="ab-project__body">'
        + '    <span class="ab-project__cat">' + esc(loc(p, 'category')) + '</span>'
        + '    <h3 class="ab-project__title">' + esc(loc(p, 'title')) + '</h3>'
        + '    <p class="ab-project__excerpt">' + esc(loc(p, 'excerpt')) + '</p>'
        + '  </div>'
        + '</a>';
    }).join('');

    return ''
      + '<section class="ab-section ab-section--surface" id="projects">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('projects_eyebrow'), title: t('projects_title'), text: t('projects_text'), link: { page: 'projects' } })
      + filters
      + '    <div class="ab-projects-grid" id="preview-projects">' + cards + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secProcess() {
    var steps = [
      ['process_step1', 'process_step1_t'],
      ['process_step2', 'process_step2_t'],
      ['process_step3', 'process_step3_t'],
      ['process_step4', 'process_step4_t']
    ];
    return ''
      + '<section class="ab-section ab-section--surface" id="process">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('process_eyebrow'), title: t('process_title'), center: true })
      + '    <div class="ab-process ab-stagger">'
      + steps.map(function (s, i) {
        return '<div class="ab-step ab-reveal" style="--ab-i:' + i + '">'
          + '<span class="ab-step__dot">0' + (i + 1) + '</span>'
          + '<h3 class="ab-step__title">' + esc(t(s[0])) + '</h3>'
          + '<p class="ab-step__text">' + esc(t(s[1])) + '</p>'
          + '</div>';
      }).join('')
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  function secTeam() {
    var cards = (D.team || []).map(function (m, i) {
      return ''
        + '<article class="ab-team-card ab-reveal" style="--ab-i:' + i + '">'
        + '  <div class="ab-team-card__media">'
        + '    <img src="' + IMG + m.image + '" alt="' + esc(loc(m, 'name')) + '" loading="lazy">'
        + '    <div class="ab-team-card__social">'
        + '      <a href="#team" aria-label="LinkedIn">' + icon('linkedin', 15) + '</a>'
        + '      <a href="mailto:' + esc(m.email) + '" aria-label="Email">' + icon('mail', 15) + '</a>'
        + '    </div>'
        + '  </div>'
        + '  <div class="ab-team-card__body">'
        + '    <h3 class="ab-team-card__name">' + esc(loc(m, 'name')) + '</h3>'
        + '    <p class="ab-team-card__role">' + esc(loc(m, 'role')) + '</p>'
        + '  </div>'
        + '</article>';
    }).join('');

    return ''
      + '<section class="ab-section" id="team">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('team_eyebrow'), title: t('team_title'), text: t('team_text'), center: true })
      + '    <div class="ab-grid ab-grid--4 ab-stagger">' + cards + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secTestimonials() {
    var cards = (D.testimonials || []).map(function (q, i) {
      var stars = '';
      for (var s = 0; s < 5; s++) stars += icon('star', 14);
      return ''
        + '<figure class="ab-quote ab-reveal" style="--ab-i:' + i + '">'
        + '  <span class="ab-quote__mark">' + icon('quote', 30) + '</span>'
        + '  <div class="ab-quote__stars">' + stars + '</div>'
        + '  <blockquote class="ab-quote__text">' + esc(loc(q, 'quote')) + '</blockquote>'
        + '  <figcaption class="ab-quote__author">'
        + '    <span class="ab-quote__avatar">' + esc(q.initials) + '</span>'
        + '    <span><span class="ab-quote__name">' + esc(loc(q, 'name')) + '</span><br><span class="ab-quote__role">' + esc(loc(q, 'role')) + '</span></span>'
        + '  </figcaption>'
        + '</figure>';
    }).join('');

    return ''
      + '<section class="ab-section ab-section--surface" id="testimonials">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('testimonials_eyebrow'), title: t('testimonials_title'), center: true })
      + '    <div class="ab-testimonials ab-stagger">' + cards + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secCta() {
    return ''
      + '<section class="ab-section" id="cta">'
      + '  <div class="ab-container">'
      + '    <div class="ab-cta ab-reveal">'
      + '      <div class="ab-cta__bg"><img src="' + IMG + 'cta.jpg" alt=""></div>'
      + '      <div class="ab-cta__inner">'
      + '        <div>'
      + '          <span class="ab-eyebrow">' + esc(t('cta_eyebrow')) + '</span>'
      + '          <h2 class="ab-cta__title">' + esc(t('cta_title')) + '</h2>'
      + '          <p class="ab-cta__text">' + esc(t('cta_text')) + '</p>'
      + '        </div>'
      + '        <div class="ab-cta__actions">'
      + '          <a class="ab-btn ab-btn--accent ab-btn--lg" href="#contact"><span>' + esc(t('cta_button')) + '</span>' + arrow() + '</a>'
      + '          <a class="ab-cta__phone" href="tel:+982188001234">' + icon('phone', 15) + '<span>' + esc(t('cta_phone')) + '</span><strong dir="ltr">+98 21 8800 1234</strong></a>'
      + '        </div>'
      + '      </div>'
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  function secContact() {
    var rows = [
      ['pin', t('address_label'), t('address'), ''],
      ['phone', t('phone_label'), '+98 21 8800 1234', 'tel:+982188001234'],
      ['mail', t('email_label'), 'info@abidargroup.com', 'mailto:info@abidargroup.com'],
      ['clock', t('hours_label'), t('hours'), '']
    ];

    return ''
      + '<section class="ab-section" id="contact">'
      + '  <div class="ab-container"><div class="ab-contact">'
      + '    <div class="ab-reveal">'
      + '      <span class="ab-eyebrow">' + esc(t('contact_eyebrow')) + '</span>'
      + '      <h2>' + esc(t('contact_title')) + '</h2>'
      + '      <p>' + esc(t('contact_text')) + '</p>'
      + '      <div style="margin-top:2rem">'
      + rows.map(function (r) {
        return '<div class="ab-contact-row">'
          + '<span class="ab-contact-row__icon">' + icon(r[0]) + '</span>'
          + '<span class="ab-contact-row__body"><span class="ab-contact-row__label">' + esc(r[1]) + '</span>'
          + (r[3] ? '<a class="ab-contact-row__value" href="' + esc(r[3]) + '">' + esc(r[2]) + '</a>' : '<span class="ab-contact-row__value">' + esc(r[2]) + '</span>')
          + '</span></div>';
      }).join('')
      + '      </div>'
      + '    </div>'
      + '    <div class="ab-reveal">' + contactForm() + '</div>'
      + '  </div></div>'
      + '</section>';
  }

  function contactForm() {
    function field(id, label, type, required) {
      return '<div class="ab-field"><label for="' + id + '">' + esc(label) + '</label>'
        + '<input id="' + id + '" name="' + id + '" type="' + type + '"' + (required ? ' required' : '') + '>'
        + '<span class="ab-field__error" style="display:none">' + esc(t('form_required')) + '</span></div>';
    }
    return ''
      + '<form class="ab-form ab-card" style="padding:clamp(1.5rem,3vw,2.5rem)" data-preview-form novalidate>'
      + '  <div class="ab-form__msg" role="status" aria-live="polite"></div>'
      + '  <div class="ab-form__row">' + field('name', t('form_name'), 'text', true) + field('email', t('form_email'), 'email', true) + '</div>'
      + '  <div class="ab-form__row">' + field('phone', t('form_phone'), 'tel', false) + field('company', t('form_company'), 'text', false) + '</div>'
      + '  <div class="ab-field"><label for="subject">' + esc(t('form_subject')) + '</label><input id="subject" name="subject" type="text"></div>'
      + '  <div class="ab-field"><label for="message">' + esc(t('form_message')) + '</label><textarea id="message" name="message" required></textarea>'
      + '    <span class="ab-field__error" style="display:none">' + esc(t('form_required')) + '</span></div>'
      + '  <div class="ab-form__foot">'
      + '    <label class="ab-check"><input type="checkbox" required><span>' + esc(t('form_consent')) + '</span></label>'
      + '    <button class="ab-btn ab-btn--primary" type="submit"><span>' + esc(t('form_submit')) + '</span>' + icon('send', 16) + '</button>'
      + '  </div>'
      + '</form>';
  }

  function secAccordion() {
    var items = [
      ['faq_q1', 'faq_a1'],
      ['faq_q2', 'faq_a2'],
      ['faq_q3', 'faq_a3'],
      ['faq_q4', 'faq_a4']
    ];
    return ''
      + '<section class="ab-section ab-section--surface" id="faq">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('faq_eyebrow'), title: t('faq_title'), center: true })
      + '    <div class="ab-accordion" data-single="true">'
      + items.map(function (it, i) {
        return '<div class="ab-accordion__item ' + (i === 0 ? 'is-open' : '') + '">'
          + '<button class="ab-accordion__btn" type="button" aria-expanded="' + (i === 0) + '"><span>' + esc(t(it[0])) + '</span>'
          + '<span class="ab-accordion__icon">' + icon('plus', 14) + '</span></button>'
          + '<div class="ab-accordion__panel"' + (i === 0 ? ' style="max-height:400px"' : '') + '>'
          + '<div class="ab-accordion__panel-inner">' + esc(t(it[1])) + '</div></div>'
          + '</div>';
      }).join('')
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  function secPosts() {
    var posts = [
      { img: 'hero-mine.jpg', cat: 'Mining', title: { fa: 'چگونه مدل‌سازی زمین‌شناسی بهره‌وری معدن را افزایش می‌دهد', en: 'How geological modelling lifts mine productivity', zh: '地质建模如何提升矿山生产率' } },
      { img: 'project2.jpg', cat: 'Oil & Gas', title: { fa: 'ایمنی در پروژه‌های خط لوله: سه درس از میدان', en: 'Pipeline safety: three lessons from the field', zh: '管道安全：来自现场的三大经验' } },
      { img: 'stone.jpg', cat: 'Stone', title: { fa: 'راهنمای انتخاب سنگ طبیعی برای پروژه‌های صادراتی', en: 'Choosing natural stone for export projects', zh: '出口项目天然石材选择指南' } }
    ];

    var cards = posts.map(function (p, i) {
      return ''
        + '<article class="ab-card ab-post-card ab-reveal" style="--ab-i:' + i + '">'
        + '  <a class="ab-post-card__media" href="#news" tabindex="-1">'
        + '    <img src="' + IMG + p.img + '" alt="" loading="lazy">'
        + '    <span class="ab-post-card__date">1405/08/12</span>'
        + '  </a>'
        + '  <div class="ab-post-card__body">'
        + '    <span class="ab-post-meta"><span class="ab-post-meta__cat">' + esc(p.cat) + '</span></span>'
        + '    <h3 class="ab-post-card__title"><a href="#news">' + esc(p.title[state.lang] || p.title.en) + '</a></h3>'
        + '    <div class="ab-post-card__foot"><a class="ab-link-arrow" href="#news"><span>' + esc(t('read_more')) + '</span>' + arrow() + '</a><span>4 ' + esc(t('min_read')) + '</span></div>'
        + '  </div>'
        + '</article>';
    }).join('');

    return ''
      + '<section class="ab-section" id="news">'
      + '  <div class="ab-container">'
      + sectionHead({ eyebrow: t('latest_news'), title: t('latest_news'), text: t('latest_news_text'), link: { page: 'blog' } })
      + '    <div class="ab-posts-grid ab-stagger">' + cards + '</div>'
      + '  </div>'
      + '</section>';
  }

  function secLangStrip() {
    return ''
      + '<section class="ab-section ab-section--tight ab-section--surface" id="languages">'
      + '  <div class="ab-container ab-center">'
      + '    <p class="ab-muted" style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;margin-bottom:.85rem">' + esc(t('nav_search') === 'Search' ? 'Available in three languages' : t('nav_search')) + '</p>'
      + '    <div class="ab-lang ab-lang--inline" style="justify-content:center;display:flex">'
      + '      <ul class="ab-lang__menu">'
      + Object.keys(LANGS).map(function (code) {
        return '<li class="ab-lang__item ' + (state.lang === code ? 'is-current' : '') + '">'
          + '<a href="#" data-lang="' + code + '"><span class="ab-lang__flag">' + (code === 'fa' ? '🇮🇷' : code === 'en' ? '🇬🇧' : '🇨🇳') + '</span>'
          + '<span class="ab-lang__label">' + LANGS[code].label + '</span></a></li>';
      }).join('')
      + '      </ul>'
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  /* ————————————————————————————————————————————————
     Header & footer (mirror template-parts/header|footer/default.php)
     ———————————————————————————————————————————————— */
  function renderHeader() {
    var navItems = [
      ['nav_home', 'home'],
      ['nav_about', 'about'],
      ['nav_services', 'services'],
      ['nav_projects', 'projects'],
      ['nav_team', 'team'],
      ['nav_blog', 'blog'],
      ['nav_contact', 'contact']
    ];

    var nav = navItems.map(function (item) {
      var current = state.page === item[1];
      return '<li class="menu-item ab-nav__item' + (current ? ' current-menu-item' : '') + '">'
        + '<a class="ab-nav__link" href="#" data-page="' + item[1] + '"' + (current ? ' aria-current="page"' : '') + '><span>' + esc(t(item[0])) + '</span></a></li>';
    }).join('');

    var mobileNav = navItems.map(function (item) {
      return '<li class="ab-nav__item"><a class="ab-nav__link" href="#" data-page="' + item[1] + '"><span>' + esc(t(item[0])) + '</span></a></li>';
    }).join('');

    var langMenu = Object.keys(LANGS).map(function (code) {
      return '<li class="ab-lang__item ' + (state.lang === code ? 'is-current' : '') + '">'
        + '<a href="#" data-lang="' + code + '"><span class="ab-lang__label">' + LANGS[code].short + '</span></a></li>';
    }).join('');

    return ''
      + '<a class="ab-skip" href="#main">Skip to content</a>'
      + '<header class="ab-header ab-header--classic" data-header>'
      + '  <div class="ab-topbar"><div class="ab-container ab-topbar__inner">'
      + '    <div class="ab-topbar__left"><span class="ab-topbar__note">' + esc(t('topbar_note')) + '</span></div>'
      + '    <div class="ab-topbar__right">'
      + '      <a class="ab-topbar__link" href="tel:+982188001234">' + icon('phone', 14) + '<span dir="ltr">+98 21 8800 1234</span></a>'
      + '      <a class="ab-topbar__link" href="mailto:info@abidargroup.com">' + icon('mail', 14) + '<span>info@abidargroup.com</span></a>'
      + '      <ul class="ab-social ab-social--mini">'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="LinkedIn">' + icon('linkedin', 14) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="Instagram">' + icon('instagram', 14) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="Telegram">' + icon('telegram', 14) + '</a></li>'
      + '      </ul>'
      + '    </div>'
      + '  </div></div>'
      + '  <div class="ab-header__bar"><div class="ab-container ab-header__inner">'
      + '    <div class="ab-header__brand">'
      + '      <a class="ab-logo" href="#top"><span class="ab-logo__mark">' + icon('mountain', 28) + '</span>'
      + '      <span class="ab-logo__text"><span class="ab-logo__name">Abidar Group</span><span class="ab-logo__tag">Mining · Oil · Stone</span></span></a>'
      + '    </div>'
      + '    <nav class="ab-nav" aria-label="Primary"><ul class="ab-nav__list">' + nav + '</ul></nav>'
      + '    <div class="ab-header__actions">'
      + '      <div class="ab-lang ab-lang--dropdown ab-header__lang"><button class="ab-lang__toggle" type="button" aria-expanded="false">' + icon('globe', 14) + '<span>' + LANGS[state.lang].short + '</span>' + icon('chevron-down', 12) + '</button>'
      + '        <ul class="ab-lang__menu">' + langMenu + '</ul></div>'
      + '      <button class="ab-icon-btn ab-search-toggle" type="button" aria-label="Search" data-search-toggle>' + icon('search', 18) + '</button>'
      + '      <a class="ab-btn ab-btn--primary ab-header__cta" href="#contact"><span>' + esc(t('nav_quote')) + '</span>' + arrow() + '</a>'
      + '      <button class="ab-burger" type="button" aria-label="Menu" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>'
      + '    </div>'
      + '  </div></div>'
      + '</header>'
      + '<div class="ab-mobile-nav" data-mobile-nav hidden>'
      + '  <div class="ab-mobile-nav__head"><a class="ab-logo" href="#top"><span class="ab-logo__mark">' + icon('mountain', 24) + '</span><span class="ab-logo__text"><span class="ab-logo__name">Abidar Group</span></span></a>'
      + '  <button class="ab-mobile-nav__close" type="button" data-menu-toggle aria-label="Close">' + icon('x', 20) + '</button></div>'
      + '  <nav><ul class="ab-mobile-nav__list">' + mobileNav + '</ul></nav>'
      + '  <div class="ab-mobile-nav__footer">'
      + '    <div class="ab-lang ab-lang--inline" style="justify-content:center;display:flex"><ul class="ab-lang__menu">' + langMenu + '</ul></div>'
      + '    <a class="ab-btn ab-btn--primary ab-btn--block" href="tel:+982188001234">' + icon('phone', 16) + '<span dir="ltr">+98 21 8800 1234</span></a>'
      + '  </div>'
      + '</div>';
  }

  function renderFooter() {
    var services = (D.services || []).map(function (s) {
      return '<li><a href="#" data-page="services">' + esc(loc(s, 'title')) + '</a></li>';
    }).join('');

    return ''
      + '<footer class="ab-footer" id="footer">'
      + '  <div class="ab-footer__newsletter"><div class="ab-container ab-footer__newsletter-inner">'
      + '    <div><h3 class="ab-footer__newsletter-title">' + esc(t('footer_newsletter')) + '</h3><p>' + esc(t('footer_newsletter_text')) + '</p></div>'
      + '    <form class="ab-newsletter" data-preview-newsletter>'
      + '      <input type="email" required placeholder="' + esc(t('newsletter_placeholder')) + '">'
      + '      <button class="ab-btn ab-btn--accent" type="submit">' + esc(t('newsletter_button')) + '</button>'
      + '      <span class="ab-newsletter__msg" role="status"></span>'
      + '    </form>'
      + '  </div></div>'
      + '  <div class="ab-footer__main"><div class="ab-container ab-footer__grid">'
      + '    <div class="ab-footer__col ab-footer__col--brand">'
      + '      <a class="ab-logo" href="#top"><span class="ab-logo__mark">' + icon('mountain', 28) + '</span><span class="ab-logo__text"><span class="ab-logo__name">Abidar Group</span><span class="ab-logo__tag">Mining · Oil · Stone</span></span></a>'
      + '      <p class="ab-footer__about">' + esc(t('footer_about')) + '</p>'
      + '      <ul class="ab-social">'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="LinkedIn">' + icon('linkedin', 16) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="Instagram">' + icon('instagram', 16) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="Telegram">' + icon('telegram', 16) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="WhatsApp">' + icon('whatsapp', 16) + '</a></li>'
      + '        <li><a class="ab-social__link" href="#" data-demo="Social profile" aria-label="YouTube">' + icon('youtube', 16) + '</a></li>'
      + '      </ul>'
      + '    </div>'
      + '    <div class="ab-footer__col"><h4 class="ab-footer__title">' + esc(t('footer_links')) + '</h4>'
      + '      <ul class="ab-footer__list">'
      + '        <li><a href="#" data-page="about">' + esc(t('nav_about')) + '</a></li>'
      + '        <li><a href="#" data-page="projects">' + esc(t('nav_projects')) + '</a></li>'
      + '        <li><a href="#" data-page="team">' + esc(t('nav_team')) + '</a></li>'
      + '        <li><a href="#" data-page="blog">' + esc(t('nav_blog')) + '</a></li>'
      + '        <li><a href="#" data-page="contact">' + esc(t('nav_contact')) + '</a></li>'
      + '      </ul></div>'
      + '    <div class="ab-footer__col"><h4 class="ab-footer__title">' + esc(t('footer_services')) + '</h4><ul class="ab-footer__list">' + services + '</ul></div>'
      + '    <div class="ab-footer__col"><h4 class="ab-footer__title">' + esc(t('footer_contact')) + '</h4>'
      + '      <div class="ab-contact-row"><span class="ab-contact-row__icon">' + icon('pin') + '</span><span class="ab-contact-row__body"><span class="ab-contact-row__label">' + esc(t('address_label')) + '</span><span class="ab-contact-row__value">' + esc(t('address')) + '</span></span></div>'
      + '      <div class="ab-contact-row"><span class="ab-contact-row__icon">' + icon('phone') + '</span><span class="ab-contact-row__body"><span class="ab-contact-row__label">' + esc(t('phone_label')) + '</span><a class="ab-contact-row__value" href="tel:+982188001234" dir="ltr">+98 21 8800 1234</a></span></div>'
      + '      <div class="ab-contact-row"><span class="ab-contact-row__icon">' + icon('mail') + '</span><span class="ab-contact-row__body"><span class="ab-contact-row__label">' + esc(t('email_label')) + '</span><a class="ab-contact-row__value" href="mailto:info@abidargroup.com">info@abidargroup.com</a></span></div>'
      + '    </div>'
      + '  </div></div>'
      + '  <div class="ab-footer__bottom"><div class="ab-container ab-footer__bottom-inner">'
      + '    <p class="ab-footer__copy">' + esc(t('copyright')) + '</p>'
      + '    <ul class="ab-footer__legal"><li><a href="#" data-demo="' + esc(t('privacy')) + '">' + esc(t('privacy')) + '</a></li><li><a href="#" data-demo="' + esc(t('terms')) + '">' + esc(t('terms')) + '</a></li></ul>'
      + '  </div></div>'
      + '</footer>'
      + '<button class="ab-to-top" type="button" aria-label="Back to top" data-to-top>' + icon('arrow-up', 18) + '</button>';
  }

  /* ————————————————————————————————————————————————
     Widget inspector data (mirrors the real Elementor controls)
     ———————————————————————————————————————————————— */
  var WIDGETS = [
    { id: 'ab-hero', name: { fa: 'هیرو اسلایدر', en: 'Hero slider', zh: '主视觉横幅' }, icon: 'eicon-slider-full-screen', controls: [
      { t: 'Eyebrow', v: 'text', handle: 'eyebrow' },
      { t: 'Headline', v: 'textarea', handle: 'title' },
      { t: 'Description', v: 'textarea', handle: 'text' },
      { t: 'Primary button + URL', v: 'text+url', handle: 'btn1_text / btn1_url' },
      { t: 'Secondary button + URL', v: 'text+url', handle: 'btn2_text / btn2_url' },
      { t: 'Background image', v: 'media', handle: 'bg_image' },
      { t: 'Overlay / height / Ken Burns', v: 'style', handle: 'overlay_color, min_height, kenburns' },
      { t: 'Statistics repeater', v: 'repeater', handle: 'stats[]' }
    ]},
    { id: 'ab-services', name: { fa: 'شبکه خدمات', en: 'Services grid', zh: '服务网格' }, icon: 'eicon-icon-box', controls: [
      { t: 'Eyebrow / Title / Text', v: 'heading', handle: 'head_*' },
      { t: 'Card style (icon / image)', v: 'select', handle: 'layout' },
      { t: 'Columns (responsive)', v: 'select', handle: 'columns' },
      { t: 'Services repeater (icon, image, title, text, link)', v: 'repeater', handle: 'items[]' },
      { t: 'Card background, padding, radius, shadow', v: 'style', handle: 'card_*' },
      { t: 'Icon colour & background', v: 'color', handle: 'icon_color, icon_bg' }
    ]},
    { id: 'ab-about', name: { fa: 'درباره ما (دو ستونه)', en: 'About split', zh: '关于我们分栏' }, icon: 'eicon-image-box', controls: [
      { t: 'Eyebrow / Title / Text', v: 'heading', handle: 'head_*' },
      { t: 'Image', v: 'media', handle: 'image' },
      { t: 'Checklist (one per line)', v: 'textarea', handle: 'points' },
      { t: 'Badge number & text', v: 'text', handle: 'badge_num, badge_text' },
      { t: 'Signature name & role', v: 'text', handle: 'sig_name, sig_role' },
      { t: 'Image radius, badge background', v: 'style', handle: 'image_radius, badge_bg' }
    ]},
    { id: 'ab-counters', name: { fa: 'شمارنده‌های متحرک', en: 'Animated counters', zh: '动态计数器' }, icon: 'eicon-counter', controls: [
      { t: 'Eyebrow / Title', v: 'heading', handle: 'head_*' },
      { t: 'Dark section + texture', v: 'media', handle: 'dark, bg_image' },
      { t: 'Columns', v: 'select', handle: 'columns' },
      { t: 'Counters repeater (icon, number, suffix, label)', v: 'repeater', handle: 'items[]' },
      { t: 'Number / label / icon colours', v: 'color', handle: 'number_color, label_color, icon_color' }
    ]},
    { id: 'ab-projects', name: { fa: 'نمایش پروژه‌ها', en: 'Projects showcase', zh: '项目展示' }, icon: 'eicon-gallery-grid', controls: [
      { t: 'Source (demo / Projects CPT)', v: 'select', handle: 'source' },
      { t: 'Number of projects', v: 'number', handle: 'count' },
      { t: 'Category filter', v: 'switch', handle: 'show_filter' },
      { t: 'Feature first project', v: 'switch', handle: 'feature' },
      { t: 'Card radius, min height', v: 'style', handle: 'card_radius, card_min_height' },
      { t: 'Overlay strength, title colour', v: 'style', handle: 'overlay, title_color' }
    ]},
    { id: 'ab-process', name: { fa: 'مراحل کار', en: 'Process steps', zh: '工作流程' }, icon: 'eicon-number-field', controls: [
      { t: 'Steps repeater (icon, title, text)', v: 'repeater', handle: 'steps[]' },
      { t: 'Numbers instead of icons', v: 'switch', handle: 'show_numbers' },
      { t: 'Dot background / colour', v: 'color', handle: 'dot_bg, dot_color' },
      { t: 'Connector line colour', v: 'color', handle: 'line_color' }
    ]},
    { id: 'ab-team', name: { fa: 'تیم ما', en: 'Team grid', zh: '团队网格' }, icon: 'eicon-person', controls: [
      { t: 'Source (demo / Team CPT)', v: 'select', handle: 'source' },
      { t: 'Columns (responsive)', v: 'select', handle: 'columns' },
      { t: 'Members repeater (photo, name, role, email, LinkedIn)', v: 'repeater', handle: 'members[]' },
      { t: 'Photo radius & aspect ratio', v: 'select', handle: 'photo_radius, ratio' },
      { t: 'Name / role colours, alignment', v: 'style', handle: 'name_color, role_color, align' }
    ]},
    { id: 'ab-testimonials', name: { fa: 'نظرات کارفرمایان', en: 'Testimonials', zh: '客户评价' }, icon: 'eicon-testimonial', controls: [
      { t: 'Testimonials repeater (quote, name, role, avatar, rating)', v: 'repeater', handle: 'items[]' },
      { t: 'Columns', v: 'select', handle: 'columns' },
      { t: 'Card background, quote colour, radius', v: 'style', handle: 'card_bg, quote_color, card_radius' },
      { t: 'Stars colour', v: 'color', handle: 'stars_color' }
    ]},
    { id: 'ab-cta', name: { fa: 'دعوت به اقدام', en: 'Call to action', zh: '行动号召' }, icon: 'eicon-call-to-action', controls: [
      { t: 'Eyebrow / Title / Text', v: 'heading', handle: 'eyebrow, title, text' },
      { t: 'Button label + URL', v: 'text+url', handle: 'btn_text, btn_url' },
      { t: 'Phone number', v: 'text', handle: 'phone' },
      { t: 'Background image', v: 'media', handle: 'bg_image' },
      { t: 'Overlay gradient, padding, radius', v: 'style', handle: 'overlay_from, padding, radius' }
    ]},
    { id: 'ab-contact', name: { fa: 'بلوک تماس', en: 'Contact block', zh: '联系区块' }, icon: 'eicon-mail', controls: [
      { t: 'Address / Phone / Email / Hours', v: 'text', handle: 'address, phone, email, hours' },
      { t: 'Show form', v: 'switch', handle: 'show_form' },
      { t: 'Show map + embed URL', v: 'switch', handle: 'show_map, map_url' },
      { t: 'Form background, input & button colours', v: 'color', handle: 'form_bg, input_bg, btn_bg' }
    ]},
    { id: 'ab-marquee', name: { fa: 'نوار لوگوی مشتریان', en: 'Client marquee', zh: '客户跑马灯' }, icon: 'eicon-carousel', controls: [
      { t: 'Caption', v: 'text', handle: 'label' },
      { t: 'Speed (s per loop)', v: 'slider', handle: 'speed' },
      { t: 'Partners repeater (logo, name)', v: 'repeater', handle: 'items[]' },
      { t: 'Item colour, opacity, gap', v: 'style', handle: 'item_color, item_opacity, gap' }
    ]},
    { id: 'ab-posts', name: { fa: 'آخرین مطالب', en: 'Latest posts', zh: '最新文章' }, icon: 'eicon-post-list', controls: [
      { t: 'Number of posts', v: 'number', handle: 'count' },
      { t: 'Category slug', v: 'text', handle: 'category' },
      { t: 'Card background, radius, title colour', v: 'style', handle: 'card_bg, card_radius, title_color' }
    ]},
    { id: 'ab-accordion', name: { fa: 'آکاردئون سؤالات', en: 'FAQ accordion', zh: '问答手风琴' }, icon: 'eicon-accordion', controls: [
      { t: 'Questions repeater (question, answer WYSIWYG)', v: 'repeater', handle: 'items[]' },
      { t: 'Single open at a time', v: 'switch', handle: 'single' },
      { t: 'Item background, question & answer colours', v: 'color', handle: 'item_bg, q_color, a_color' }
    ]},
    { id: 'ab-lang-switcher', name: { fa: 'تغییر زبان', en: 'Language switcher', zh: '语言切换' }, icon: 'eicon-globe', controls: [
      { t: 'Style (inline pills / dropdown)', v: 'select', handle: 'style' },
      { t: 'Show flags', v: 'switch', handle: 'show_flags' },
      { t: 'Alignment', v: 'select', handle: 'align' },
      { t: 'Item & active colours, radius, typography', v: 'style', handle: 'item_color, active_bg, active_color, item_radius' }
    ]},
    { id: 'ab-page-title', name: { fa: 'عنوان صفحه و مسیر', en: 'Page title & breadcrumb', zh: '页面标题与面包屑' }, icon: 'eicon-page-title', controls: [
      { t: 'Use current page title', v: 'switch', handle: 'use_page_title' },
      { t: 'Custom title / Subtitle / Eyebrow', v: 'text', handle: 'custom_title, subtitle, eyebrow' },
      { t: 'Breadcrumb', v: 'switch', handle: 'show_breadcrumb' },
      { t: 'Background image & min height', v: 'media', handle: 'bg, height' }
    ]}
  ];

  var VALUE_LABEL = {
    text: 'Text', textarea: 'Textarea', heading: '3 controls', select: 'Select', switch: 'Switcher',
    media: 'Media', repeater: 'Repeater', color: 'Colour', slider: 'Slider', number: 'Number',
    style: 'Style tab', url: 'URL', 'text+url': 'Text + URL'
  };

  function renderWidgetPanel() {
    var cards = WIDGETS.map(function (w) {
      var controls = w.controls.map(function (c) {
        return '<li class="ab-wc"><span class="ab-wc__label">' + esc(c.t) + '</span>'
          + '<span class="ab-wc__meta"><span class="ab-wc__type ab-wc__type--' + esc(c.v) + '">' + esc(VALUE_LABEL[c.v] || c.v) + '</span>'
          + '<code>' + esc(c.handle) + '</code></span></li>';
      }).join('');

      return '<article class="ab-widget-card">'
        + '<header class="ab-widget-card__head">'
        + '<span class="ab-widget-card__icon ' + esc(w.icon) + '"></span>'
        + '<div><h3>' + esc(w.name[state.lang] || w.name.en) + '</h3><code>' + esc(w.id) + '</code></div>'
        + '<span class="ab-widget-card__count">' + w.controls.length + '</span>'
        + '</header>'
        + '<ul class="ab-widget-card__controls">' + controls + '</ul>'
        + '<footer class="ab-widget-card__foot">' + esc(t('learn_more')) + ' → Elementor → Abidar</footer>'
        + '</article>';
    }).join('');

    return ''
      + '<aside class="ab-widgets' + (state.widgetsOpen ? ' is-open' : '') + '" id="widgetPanel" aria-hidden="' + (!state.widgetsOpen) + '">'
      + '  <div class="ab-widgets__head">'
      + '    <div>'
      + '      <p class="ab-widgets__eyebrow">Elementor · Abidar</p>'
      + '      <h2>' + (state.lang === 'fa' ? '۱۵ المان اختصاصی' : state.lang === 'zh' ? '15 个专属小工具' : '15 dedicated widgets') + '</h2>'
      + '      <p class="ab-widgets__sub">' + (state.lang === 'fa'
        ? 'هر المان با کنترل کامل استایل: رنگ، تایپوگرافی، فاصله، حاشیه، پس‌زمینه و ریسپانسیو.'
        : state.lang === 'zh'
          ? '每个小工具都提供完整样式控制：颜色、字体、间距、边框、背景与响应式。'
          : 'Every widget exposes full style control: colour, typography, spacing, border, background and responsiveness.') + '</p>'
      + '    </div>'
      + '    <button type="button" class="ab-widgets__close" id="widgetClose" aria-label="Close">' + icon('x', 20) + '</button>'
      + '  </div>'
      + '  <div class="ab-widgets__grid">' + cards + '</div>'
      + '  <div class="ab-widgets__note">'
      + '    <strong>Header &amp; Footer</strong> — ' + (state.lang === 'fa'
        ? 'با Templates → Theme Builder در المنتور قابل ساخت است و به‌صورت خودکار جای هدر و فوتر قالب را می‌گیرد.'
        : state.lang === 'zh'
          ? '可在 Elementor 的 Templates → Theme Builder 中构建，自动替换主题默认页眉页脚。'
          : 'Build them in Templates → Theme Builder and they automatically replace the theme header and footer.') + ''
      + '  </div>'
      + '</aside>';
  }

  /* ————————————————————————————————————————————————
     Page composition
     ———————————————————————————————————————————————— */
  function homeSections() {
    return [
      secHero(), secMarquee(), secSectors(), secServices(), secCapabilities(),
      secCounters(), secFeatured(), secProjects(), secProcess(), secAbout(),
      secTeam(), secTestimonials(), secPosts(), secAccordion(), secCta()
    ].join('');
  }


  /* ——— Sector list (new) ——————————————————————————— */
  function secSectors() {
    var rows = [
      { num: '01', title: t('service_mining'), text: t('service_mining_text'), tag: t('sector_tag_mining'), img: 'project1.jpg' },
      { num: '02', title: t('service_oil'), text: t('service_oil_text'), tag: t('sector_tag_oil'), img: 'oil-gas.jpg' },
      { num: '03', title: t('service_stone'), text: t('service_stone_text'), tag: t('sector_tag_stone'), img: 'stone.jpg' },
      { num: '04', title: t('service_engineering'), text: t('service_engineering_text'), tag: t('sector_tag_engineering'), img: 'about.jpg' }
    ];

    return ''
      + '<section class="ab-section ab-sectors">'
      + '  <div class="ab-container">'
      + '    <div class="ab-section-head ab-section-head--split ab-reveal">'
      + '      <div><span class="ab-eyebrow">' + esc(t('sectors_eyebrow')) + '</span>'
      + '        <h2>' + esc(t('sectors_title')) + '</h2></div>'
      + '      <p>' + esc(t('sectors_text')) + '</p>'
      + '    </div>'
      + '    <div class="ab-sectors__list ab-stagger">'
      + rows.map(function (r) {
        return ''
          + '<a class="ab-sector" href="#" data-page="services">'
          + '  <span class="ab-sector__media"><img src="' + IMG + r.img + '" alt="" loading="lazy"></span>'
          + '  <span class="ab-sector__body">'
          + '    <span class="ab-sector__num">' + r.num + '</span>'
          + '    <span class="ab-sector__text">'
          + '      <span class="ab-sector__title">' + esc(r.title) + '</span>'
          + '      <span class="ab-sector__desc">' + esc(r.text) + '</span>'
          + '    </span>'
          + '    <span class="ab-sector__tag">' + esc(r.tag) + '</span>'
          + '    <span class="ab-sector__arrow">' + icon('arrow-up-right') + '</span>'
          + '  </span>'
          + '</a>';
      }).join('')
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  /* ——— Capability band (new) ——————————————————————— */
  function secCapabilities() {
    var items = [
      { icon: 'excavator', value: '12', unit: '', title: t('cap_1'), text: t('cap_1_text') },
      { icon: 'package', value: '1.8', unit: 'M', title: t('cap_2'), text: t('cap_2_text') },
      { icon: 'shield', value: '0.5', unit: '', title: t('cap_3'), text: t('cap_3_text') },
      { icon: 'clock', value: '24', unit: '/7', title: t('cap_4'), text: t('cap_4_text') }
    ];

    return ''
      + '<section class="ab-section ab-section--dark ab-caps">'
      + '  <div class="ab-caps__bg" aria-hidden="true"><img src="' + IMG + 'hero-mine.jpg" alt="" loading="lazy"></div>'
      + '  <div class="ab-container">'
      + '    <div class="ab-caps__head ab-reveal">'
      + '      <span class="ab-eyebrow ab-eyebrow--light">' + esc(t('cap_eyebrow')) + '</span>'
      + '      <h2>' + esc(t('cap_title')) + '</h2>'
      + '    </div>'
      + '    <div class="ab-caps__grid ab-stagger">'
      + items.map(function (it) {
        return ''
          + '<div class="ab-cap">'
          + '  <span class="ab-cap__icon">' + icon(it.icon) + '</span>'
          + '  <span class="ab-cap__value">' + esc(it.value) + '<span class="ab-cap__unit">' + esc(it.unit) + '</span></span>'
          + '  <span class="ab-cap__title">' + esc(it.title) + '</span>'
          + '  <span class="ab-cap__text">' + esc(it.text) + '</span>'
          + '</div>';
      }).join('')
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  /* ——— Featured project (new) ————————————————————— */
  function secFeatured() {
    var facts = [
      { label: t('client'), value: t('featured_client') },
      { label: t('location'), value: t('featured_location') },
      { label: t('duration'), value: t('featured_duration') },
      { label: t('sector'), value: t('service_mining') }
    ];

    return ''
      + '<section class="ab-section ab-featured">'
      + '  <div class="ab-container">'
      + '    <div class="ab-featured__grid">'
      + '      <div class="ab-featured__media ab-reveal">'
      + '        <img src="' + IMG + 'project1.jpg" alt="" loading="lazy">'
      + '        <a class="ab-featured__play" href="#" data-page="projects" aria-label="' + esc(t('view_project')) + '">'
      + icon('arrow-up-right') + '</a>'
      + '      </div>'
      + '      <div class="ab-featured__body">'
      + '        <span class="ab-eyebrow">' + esc(t('featured_eyebrow')) + '</span>'
      + '        <h2 class="ab-featured__title">' + esc(t('featured_title')) + '</h2>'
      + '        <p class="ab-lead">' + esc(t('featured_text')) + '</p>'
      + '        <dl class="ab-featured__facts">'
      + facts.map(function (f) {
        return '<div class="ab-fact"><dt>' + esc(f.label) + '</dt><dd>' + esc(f.value) + '</dd></div>';
      }).join('')
      + '        </dl>'
      + '        <a class="ab-btn ab-btn--primary" href="#" data-page="projects"><span>' + esc(t('view_project')) + '</span>' + icon('arrow-right') + '</a>'
      + '      </div>'
      + '    </div>'
      + '  </div>'
      + '</section>';
  }

  function pageHero(title, sub, eyebrow) {
    return ''
      + '<section class="ab-page-hero" style="--ab-hero-bg:url(' + IMG + 'pattern-dark.jpg)">'
      + '  <div class="ab-container">'
      + '    <span class="ab-eyebrow ab-eyebrow--light">' + esc(eyebrow) + '</span>'
      + '    <h1 class="ab-page-hero__title">' + esc(title) + '</h1>'
      + (sub ? '<p class="ab-page-hero__sub">' + esc(sub) + '</p>' : '')
      + '    <nav class="ab-breadcrumb"><ol>'
      + '      <li><a href="#" data-page="home">' + esc(t('nav_home')) + '</a></li>'
      + '      <li aria-current="page">' + esc(title) + '</li>'
      + '    </ol></nav>'
      + '  </div>'
      + '</section>';
  }

  function servicesPage() {
    var cards = (D.services || []).map(function (s, i) {
      return ''
        + '<article class="ab-card ab-service ab-service--image ab-reveal" style="--ab-i:' + i + '">'
        + '  <div class="ab-service__media"><img src="' + IMG + s.image + '" alt="" loading="lazy"><span class="ab-service__icon">' + icon(s.icon) + '</span></div>'
        + '  <div class="ab-service__body">'
        + '    <h3 class="ab-service__title">' + esc(loc(s, 'title')) + '</h3>'
        + '    <p class="ab-service__text">' + esc(loc(s, 'text')) + '</p>'
        + '    <ul class="ab-service__list">'
        + s.items.map(function (it) {
          return '<li>' + icon('check', 15) + '<span>' + esc(it[state.lang] || it.en) + '</span></li>';
        }).join('')
        + '    </ul>'
        + '  </div>'
        + '</article>';
    }).join('');

    return pageHero(t('nav_services'), t('services_text'), t('services_eyebrow'))
      + '<section class="ab-section"><div class="ab-container"><div class="ab-grid ab-grid--2 ab-stagger">' + cards + '</div></div></section>'
      + secProcess()
      + secAccordion()
      + secCta();
  }

  function projectsPage() {
    var cards = (D.projects || []).map(function (p, i) {
      return ''
        + '<article class="ab-card ab-reveal" style="--ab-i:' + i + ';overflow:hidden">'
        + '  <div class="ab-post-card__media"><img src="' + IMG + p.image + '" alt="" loading="lazy"><span class="ab-project__cat" style="position:absolute;bottom:.9rem;inset-inline-start:.9rem">' + esc(loc(p, 'category')) + '</span></div>'
        + '  <div class="ab-post-card__body">'
        + '    <h3 class="ab-post-card__title">' + esc(loc(p, 'title')) + '</h3>'
        + '    <p class="ab-post-card__excerpt">' + esc(loc(p, 'excerpt')) + '</p>'
        + '    <div class="ab-post-card__foot" style="gap:1.25rem;flex-wrap:wrap">'
        + '      <span>' + icon('pin', 13) + ' ' + esc(loc(p, 'location')) + '</span>'
        + '      <span>' + icon('calendar', 13) + ' ' + esc(p.year) + '</span>'
        + '      <span style="color:var(--ab-primary)">' + esc(loc(p, 'status')) + '</span>'
        + '    </div>'
        + '  </div>'
        + '</article>';
    }).join('');

    return pageHero(t('nav_projects'), t('projects_text'), t('projects_eyebrow'))
      + '<section class="ab-section"><div class="ab-container"><div class="ab-posts-grid ab-stagger">' + cards + '</div></div></section>'
      + secCounters() + secCta();
  }

  function aboutPage() {
    return pageHero(t('nav_about'), t('about_text'), t('about_eyebrow'))
      + secAbout()
      + secCounters()
      + secProcess()
      + secTeam()
      + secTestimonials()
      + secCta();
  }

  function teamPage() {
    return pageHero(t('nav_team'), t('team_text'), t('team_eyebrow'))
      + secTeam()
      + secTestimonials()
      + secCta();
  }

  function contactPage() {
    return pageHero(t('nav_contact'), t('contact_text'), t('contact_eyebrow'))
      + secContact()
      + secAccordion()
      + '<section class="ab-section ab-section--tight"><div class="ab-container"><div class="ab-map"><iframe title="map" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=51.38%2C35.72%2C51.44%2C35.76&amp;layer=mapnik"></iframe></div></div></section>';
  }

  function blogPage() {
    var posts = [
      { img: 'hero-mine.jpg', fa: 'چگونه مدل‌سازی زمین‌شناسی بهره‌وری معدن را افزایش می‌دهد', en: 'How geological modelling lifts mine productivity', zh: '地质建模如何提升矿山生产率' },
      { img: 'project2.jpg', fa: 'ایمنی در پروژه‌های خط لوله: سه درس از میدان', en: 'Pipeline safety: three lessons from the field', zh: '管道安全：来自现场的三大经验' },
      { img: 'stone.jpg', fa: 'راهنمای انتخاب سنگ طبیعی برای پروژه‌های صادراتی', en: 'Choosing natural stone for export projects', zh: '出口项目天然石材选择指南' },
      { img: 'oil-gas.jpg', fa: 'بازرسی تأسیسات نفتی بدون توقف عملیات', en: 'Inspecting oil facilities without stopping operations', zh: '不停产条件下检测石油设施' },
      { img: 'project3.jpg', fa: 'خط برش اسلب: از بلوک تا محصول نهایی', en: 'Slab cutting lines: from block to finished product', zh: '板材切割线：从荒料到成品' },
      { img: 'project1.jpg', fa: 'نوار نقاله در برابر حمل جاده‌ای: مقایسه هزینه', en: 'Conveyor vs road haulage: a cost comparison', zh: '输送带与公路运输：成本对比' }
    ];

    var cards = posts.map(function (p, i) {
      return ''
        + '<article class="ab-card ab-post-card ab-reveal" style="--ab-i:' + i + '">'
        + '  <a class="ab-post-card__media" href="#" tabindex="-1"><img src="' + IMG + p.img + '" alt="" loading="lazy"><span class="ab-post-card__date">1405/08/' + (10 + i) + '</span></a>'
        + '  <div class="ab-post-card__body">'
        + '    <h3 class="ab-post-card__title"><a href="#">' + esc(p[state.lang] || p.en) + '</a></h3>'
        + '    <div class="ab-post-card__foot"><a class="ab-link-arrow" href="#"><span>' + esc(t('read_more')) + '</span>' + arrow() + '</a><span>5 ' + esc(t('min_read')) + '</span></div>'
        + '  </div>'
        + '</article>';
    }).join('');

    return pageHero(t('blog_title'), t('blog_text'), t('blog_eyebrow'))
      + '<section class="ab-section"><div class="ab-container"><div class="ab-posts-grid ab-stagger">' + cards + '</div>'
      + '<nav class="ab-pagination" style="display:flex;justify-content:center;margin-top:2.5rem"><div class="nav-links">'
      + '<span class="page-numbers current">1</span><a class="page-numbers" href="#">2</a><a class="page-numbers" href="#">3</a>'
      + '</div></nav></div></section>';
  }

  function renderPage() {
    switch (state.page) {
      case 'services': return servicesPage();
      case 'projects': return projectsPage();
      case 'about': return aboutPage();
      case 'team': return teamPage();
      case 'contact': return contactPage();
      case 'blog': return blogPage();
      default: return homeSections();
    }
  }

  /* ————————————————————————————————————————————————
     Render
     ———————————————————————————————————————————————— */
  function render(scrollTop) {
    var app = document.getElementById('app');
    var y = window.scrollY;

    app.innerHTML = renderHeader()
      + '<main id="main" class="ab-main">' + renderPage() + '</main>'
      + renderFooter()
      + renderWidgetPanel();

    document.documentElement.lang = LANGS[state.lang].html;
    document.documentElement.dir = LANGS[state.lang].dir;
    document.documentElement.className = 'ab-locale-' + state.lang;
    document.body.className = 'ab-theme ab-sticky-header ab-transparent-header ab-lang-' + state.lang + ' ab-dir-' + LANGS[state.lang].dir;

    bind();
    initBehaviour();

    if (!scrollTop) {
      window.scrollTo(0, Math.min(y, document.body.scrollHeight));
    } else {
      window.scrollTo(0, 0);
    }
  }

  function slug(str) {
    return String(str).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-\u0600-\u06FF]/g, '');
  }

  /* ————————————————————————————————————————————————
     Behaviour (a trimmed mirror of assets/js/main.js)
     ———————————————————————————————————————————————— */
  function initBehaviour() {
    revealInit();
    countersInit();
    accordionInit();
    filterInit();
    formsInit();
    toTopInit();
    langDropdownInit();
    mobileNavInit();
    smoothAnchors();
    marqueeClone();
  }

  function revealInit() {
    var items = document.querySelectorAll('.ab-reveal');
    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
      });
    }, { rootMargin: '0px 0px -5% 0px', threshold: 0.05 });
    items.forEach(function (el) { io.observe(el); });
  }

  function countersInit() {
    var counters = document.querySelectorAll('[data-counter]');
    var animate = function (el) {
      var target = parseFloat(el.getAttribute('data-counter')) || 0;
      var decimals = (el.getAttribute('data-counter').split('.')[1] || '').length;
      var start = null;
      function step(ts) {
        if (!start) start = ts;
        var p = Math.min((ts - start) / 1500, 1);
        var eased = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);
        el.textContent = (target * eased).toFixed(decimals);
        if (p < 1) raf(step);
      }
      raf(step);
    };

    if (!('IntersectionObserver' in window)) { counters.forEach(animate); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) { animate(e.target); io.unobserve(e.target); } });
    }, { threshold: 0.4 });
    counters.forEach(function (el) { io.observe(el); });
  }

  function accordionInit() {
    document.querySelectorAll('.ab-accordion').forEach(function (acc) {
      var single = acc.getAttribute('data-single') !== 'false';
      acc.querySelectorAll('.ab-accordion__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var item = btn.closest('.ab-accordion__item');
          var panel = item.querySelector('.ab-accordion__panel');
          var open = item.classList.contains('is-open');
          if (single) {
            acc.querySelectorAll('.ab-accordion__item.is-open').forEach(function (other) {
              if (other === item) return;
              other.classList.remove('is-open');
              var p = other.querySelector('.ab-accordion__panel');
              if (p) p.style.maxHeight = '0px';
              var b = other.querySelector('.ab-accordion__btn');
              if (b) b.setAttribute('aria-expanded', 'false');
            });
          }
          item.classList.toggle('is-open', !open);
          btn.setAttribute('aria-expanded', open ? 'false' : 'true');
          panel.style.maxHeight = open ? '0px' : panel.scrollHeight + 40 + 'px';
        });
      });
    });
  }

  function filterInit() {
    document.querySelectorAll('[data-filter-group]').forEach(function (group) {
      var target = document.querySelector(group.getAttribute('data-filter-target'));
      if (!target) return;
      group.querySelectorAll('.ab-filter').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var value = btn.getAttribute('data-filter');
          group.querySelectorAll('.ab-filter').forEach(function (b) { b.classList.remove('is-active'); });
          btn.classList.add('is-active');
          target.querySelectorAll('[data-cat]').forEach(function (card) {
            var show = value === '*' || card.getAttribute('data-cat') === value;
            card.style.display = show ? '' : 'none';
          });
        });
      });
    });
  }

  function formsInit() {
    document.querySelectorAll('[data-preview-form]').forEach(function (form) {
      var msg = form.querySelector('.ab-form__msg');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var valid = true;
        form.querySelectorAll('[required]').forEach(function (field) {
          var wrap = field.closest('.ab-field');
          var error = wrap ? wrap.querySelector('.ab-field__error') : null;
          var ok = field.type === 'checkbox' ? field.checked : field.value.trim() !== '';
          if (ok && field.type === 'email') ok = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(field.value.trim());
          if (wrap && wrap.classList.contains('ab-field')) wrap.classList.toggle('ab-field--error', !ok);
          if (error) error.style.display = ok ? 'none' : 'block';
          if (!ok) valid = false;
        });
        if (!valid) return;
        msg.className = 'ab-form__msg is-success';
        msg.textContent = t('form_success');
        form.reset();
      });
    });

    document.querySelectorAll('[data-preview-newsletter]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var input = form.querySelector('input');
        var msg = form.querySelector('.ab-newsletter__msg');
        if (!input || !msg) return;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(input.value.trim())) {
          msg.textContent = t('form_email_invalid');
          return;
        }
        msg.textContent = t('form_success');
        form.reset();
      });
    });
  }

  function toTopInit() {
    var btn = document.querySelector('[data-to-top]');
    if (!btn) return;
    btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    var onScroll = function () { btn.classList.toggle('is-visible', window.scrollY > 600); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  function langDropdownInit() {
    document.querySelectorAll('.ab-lang--dropdown').forEach(function (wrap) {
      var toggle = wrap.querySelector('.ab-lang__toggle');
      if (!toggle) return;
      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = wrap.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      document.addEventListener('click', function () { wrap.classList.remove('is-open'); });
    });
  }

  function mobileNavInit() {
    var drawer = document.querySelector('[data-mobile-nav]');
    var toggles = document.querySelectorAll('[data-menu-toggle]');
    var burger = document.querySelector('.ab-burger');
    if (!drawer) return;
    var open = false;

    function setOpen(next) {
      open = next;
      drawer.hidden = false;
      void drawer.offsetWidth;
      drawer.classList.toggle('is-open', open);
      if (burger) {
        burger.classList.toggle('is-active', open);
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      }
      document.documentElement.style.overflow = open ? 'hidden' : '';
      if (!open) setTimeout(function () { if (!open) drawer.hidden = true; }, 400);
    }

    toggles.forEach(function (btn) { btn.addEventListener('click', function () { setOpen(!open); }); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && open) setOpen(false); });
  }

  /* Demo-only links show a short hint instead of jumping nowhere. */
  var toastTimer = null;
  function toast(message) {
    var el = document.getElementById('abToast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'abToast';
      el.className = 'ab-toast';
      el.setAttribute('role', 'status');
      el.setAttribute('aria-live', 'polite');
      document.body.appendChild(el);
    }
    el.textContent = message;
    el.classList.add('is-visible');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { el.classList.remove('is-visible'); }, 2200);
  }

  /* Delegated: in-page anchors work on every page. If the anchor lives on the
     homepage (services / projects / contact), we go home first. */
  document.addEventListener('click', function (e) {
    var link = e.target.closest ? e.target.closest('a[href^="#"]') : null;
    if (!link) return;
    var href = link.getAttribute('href');

    if (href === '#' || href.length < 2) {
      if (link.hasAttribute('data-demo')) {
        e.preventDefault();
        toast(link.getAttribute('data-demo'));
      }
      return;
    }

    var target = document.querySelector(href);

    if (!target) {
      if (state.page === 'home') return;
      e.preventDefault();
      state.page = 'home';
      render(true);
      setTimeout(function () {
        var el = document.querySelector(href);
        if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' });
      }, 80);
      return;
    }

    e.preventDefault();
    var top = target.getBoundingClientRect().top + window.scrollY - 90;
    window.scrollTo({ top: top, behavior: 'smooth' });

    var drawer = document.querySelector('.ab-mobile-nav.is-open');
    if (drawer) {
      var close = document.querySelector('.ab-mobile-nav__close');
      if (close) close.click();
    }
  });

  function smoothAnchors() {}

  function marqueeClone() {
    document.querySelectorAll('.ab-marquee__track').forEach(function (track) {
      if (track.getAttribute('data-cloned')) return;
      track.setAttribute('data-cloned', '1');
      track.innerHTML += track.innerHTML;
    });
  }

  /* ————————————————————————————————————————————————
     Global bindings
     ———————————————————————————————————————————————— */
  function bind() {
    document.querySelectorAll('[data-lang]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var code = el.getAttribute('data-lang');
        if (!LANGS[code] || code === state.lang) return;
        state.lang = code;
        syncBar();
        render(false);
      });
    });

    document.querySelectorAll('[data-page]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        state.page = el.getAttribute('data-page');
        render(true);
      });
    });

    var widgetToggle = document.getElementById('widgetToggle');
    var widgetClose = document.getElementById('widgetClose');

    if (widgetToggle) {
      widgetToggle.addEventListener('click', function () {
        state.widgetsOpen = true;
        syncBar();
        render(false);
      });
    }
    if (widgetClose) {
      widgetClose.addEventListener('click', function () {
        state.widgetsOpen = false;
        syncBar();
        render(false);
      });
    }

    document.querySelectorAll('[data-search-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var panel = document.querySelector('.ab-search-panel');
        if (panel) panel.toggleAttribute('hidden');
      });
    });
  }

  var BAR_COPY = {
    fa: { label: 'قالب وردپرس شرکتی · پیش‌نمایش زنده', widgets: 'المان‌های المنتور', docs: 'راهنمای قالب' },
    en: { label: 'Corporate WordPress theme · live preview', widgets: 'Elementor widgets', docs: 'Theme docs' },
    zh: { label: '企业 WordPress 主题 · 实时预览', widgets: 'Elementor 小工具', docs: '主题文档' }
  };

  function syncBar() {
    var copy = BAR_COPY[state.lang];
    document.querySelectorAll('[data-bar-label]').forEach(function (el) { el.textContent = copy.label; });
    document.querySelectorAll('[data-bar-widgets]').forEach(function (el) { el.textContent = copy.widgets; });
    document.querySelectorAll('[data-bar-docs]').forEach(function (el) { el.textContent = copy.docs; });
    document.querySelectorAll('.ab-preview-bar__langs [data-lang]').forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-lang') === state.lang);
    });
    document.documentElement.dir = LANGS[state.lang].dir;
  }

  /* Keyboard: E toggles the widget panel, 1/2/3 switch language. */
  document.addEventListener('keydown', function (e) {
    if (e.target.matches('input, textarea, select')) return;
    if (e.key === 'e' || e.key === 'E') {
      state.widgetsOpen = !state.widgetsOpen;
      syncBar();
      render(false);
    }
    if (e.key === 'Escape' && state.widgetsOpen) {
      state.widgetsOpen = false;
      syncBar();
      render(false);
    }
    if (e.key === '1') { state.lang = 'fa'; syncBar(); render(false); }
    if (e.key === '2') { state.lang = 'en'; syncBar(); render(false); }
    if (e.key === '3') { state.lang = 'zh'; syncBar(); render(false); }
  });

  /* Sticky header on scroll. */
  (function stickyHeader() {
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      raf(function () {
        var header = document.querySelector('[data-header]');
        if (header) header.classList.toggle('is-sticky', window.scrollY > 220);
        ticking = false;
      });
    }, { passive: true });
  })();

  render(true);
  syncBar();
})();
