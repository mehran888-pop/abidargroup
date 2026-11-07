/**
 * Docs page: fills every [data-copy] element from the theme dictionary and
 * keeps the language switcher in sync. Mirrors how WordPress outputs the same
 * strings through Abidar_Strings, so the preview text is the real theme copy.
 */
(function () {
  'use strict';

  var DATA = window.ABIDAR_DATA || {};
  var STRINGS = DATA.strings || {};

  var LANGS = {
    fa: { dir: 'rtl', label: 'فارسی', html: 'fa-IR' },
    en: { dir: 'ltr', label: 'English', html: 'en' },
    zh: { dir: 'ltr', label: '中文', html: 'zh-CN' }
  };

  var BAR = {
    fa: 'راهنمای قالب · Theme documentation',
    en: 'Theme documentation · راهنمای قالب',
    zh: '主题文档 · Theme documentation'
  };

  var state = { lang: 'fa' };

  function pick(key, lang) {
    var entry = STRINGS[key];
    if (!entry) return '';
    return entry[lang] || entry.en || entry.fa || '';
  }

  function render() {
    var lang = state.lang;

    document.querySelectorAll('[data-copy]').forEach(function (el) {
      var value = pick(el.getAttribute('data-copy'), lang);
      if (value) el.textContent = value;
    });

    document.documentElement.lang = LANGS[lang].html;
    document.documentElement.dir = LANGS[lang].dir;
    document.body.setAttribute('data-lang', lang);

    document.querySelectorAll('[data-lang]').forEach(function (btn) {
      btn.classList.toggle('is-active', btn.getAttribute('data-lang') === lang);
    });

    document.querySelectorAll('[data-bar-label]').forEach(function (el) {
      el.textContent = BAR[lang];
    });

    try {
      window.localStorage.setItem('abidar-preview-lang', lang);
    } catch (e) {}
  }

  document.querySelectorAll('[data-lang]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var code = btn.getAttribute('data-lang');
      if (!LANGS[code] || code === state.lang) return;
      state.lang = code;
      render();
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.target.matches('input, textarea, select')) return;
    if (e.key === '1') { state.lang = 'fa'; render(); }
    if (e.key === '2') { state.lang = 'en'; render(); }
    if (e.key === '3') { state.lang = 'zh'; render(); }
  });

  /* Smooth in-page navigation for the TOC. */
  document.addEventListener('click', function (e) {
    var link = e.target.closest ? e.target.closest('a[href^="#"]') : null;
    if (!link) return;
    var target = document.querySelector(link.getAttribute('href'));
    if (!target) return;
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    history.replaceState(null, '', link.getAttribute('href'));
  });

  try {
    var saved = window.localStorage.getItem('abidar-preview-lang');
    if (saved && LANGS[saved]) state.lang = saved;
  } catch (e) {}

  render();
})();
