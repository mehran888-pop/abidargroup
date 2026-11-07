/**
 * Abidar Group — front-end behaviour.
 * Dependency free, progressively enhanced, ~4kb gzipped.
 */
(function () {
  'use strict';

  var data = window.abidarData || {};
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function onReady(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  /* ——— Sticky header ——————————————————————————————— */
  function initStickyHeader() {
    var header = document.querySelector('[data-header]');
    if (!header || !data.stickyHeader) return;

    var offset = header.offsetTop;
    var ticking = false;

    function update() {
      var y = window.pageYOffset || document.documentElement.scrollTop;
      header.classList.toggle('is-sticky', y > offset + 140);
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(update);
        ticking = true;
      }
    }, { passive: true });

    update();
  }

  /* ——— Mobile drawer ————————————————————————————— */
  function initMobileNav() {
    var drawer = document.querySelector('[data-mobile-nav]');
    var toggles = document.querySelectorAll('[data-menu-toggle]');
    var burger = document.querySelector('.ab-burger');
    if (!drawer || !toggles.length) return;

    var isOpen = false;

    function setOpen(open) {
      isOpen = open;
      drawer.hidden = false;
      // Force a reflow so the transition runs from the off-canvas position.
      void drawer.offsetWidth;
      drawer.classList.toggle('is-open', open);
      if (burger) {
        burger.classList.toggle('is-active', open);
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      }
      document.documentElement.style.overflow = open ? 'hidden' : '';

      if (!open) {
        window.setTimeout(function () {
          if (!isOpen) drawer.hidden = true;
        }, 400);
      } else {
        var firstLink = drawer.querySelector('a, button');
        if (firstLink) firstLink.focus({ preventScroll: true });
      }
    }

    toggles.forEach(function (btn) {
      btn.addEventListener('click', function () {
        setOpen(!isOpen);
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && isOpen) setOpen(false);
    });
  }

  /* ——— Search panel —————————————————————————————— */
  function initSearch() {
    var panel = document.querySelector('[data-search-panel]');
    var toggles = document.querySelectorAll('[data-search-toggle]');
    if (!panel || !toggles.length) return;

    toggles.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var hidden = panel.hasAttribute('hidden');
        if (hidden) {
          panel.removeAttribute('hidden');
          var input = panel.querySelector('input[type="search"]');
          if (input) input.focus();
        } else {
          panel.setAttribute('hidden', '');
        }
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !panel.hasAttribute('hidden')) {
        panel.setAttribute('hidden', '');
      }
    });
  }

  /* ——— Language dropdown ————————————————————————— */
  function initLangSwitcher() {
    document.querySelectorAll('.ab-lang--dropdown').forEach(function (wrap) {
      var toggle = wrap.querySelector('.ab-lang__toggle');
      if (!toggle) return;

      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = wrap.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });

      document.addEventListener('click', function () {
        wrap.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') wrap.classList.remove('is-open');
      });
    });
  }

  /* ——— Scroll reveal ————————————————————————————— */
  function initReveal() {
    var items = document.querySelectorAll('.ab-reveal');
    if (!items.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach(function (el) { io.observe(el); });
  }

  /* ——— Animated counters —————————————————————————— */
  function initCounters() {
    var counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    function animate(el) {
      var target = parseFloat(el.getAttribute('data-counter')) || 0;
      var decimals = (el.getAttribute('data-counter').split('.')[1] || '').length;
      var duration = 1600;
      var start = null;

      if (reduceMotion) {
        el.textContent = target.toFixed(decimals);
        return;
      }

      function step(ts) {
        if (!start) start = ts;
        var progress = Math.min((ts - start) / duration, 1);
        // easeOutExpo
        var eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        el.textContent = (target * eased).toFixed(decimals);
        if (progress < 1) window.requestAnimationFrame(step);
      }

      window.requestAnimationFrame(step);
    }

    if (!('IntersectionObserver' in window)) {
      counters.forEach(animate);
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animate(entry.target);
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });

    counters.forEach(function (el) { io.observe(el); });
  }

  /* ——— Accordion ——————————————————————————————————— */
  function initAccordion() {
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

  /* ——— Project / post filters ————————————————————— */
  function initFilters() {
    document.querySelectorAll('[data-filter-group]').forEach(function (group) {
      var targetSel = group.getAttribute('data-filter-target');
      var target = targetSel ? document.querySelector(targetSel) : null;
      if (!target) return;

      group.querySelectorAll('.ab-filter').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var value = btn.getAttribute('data-filter');
          group.querySelectorAll('.ab-filter').forEach(function (b) { b.classList.remove('is-active'); });
          btn.classList.add('is-active');

          target.querySelectorAll('[data-cat]').forEach(function (card) {
            var cats = card.getAttribute('data-cat').split(' ');
            var show = value === '*' || cats.indexOf(value) !== -1;
            card.style.display = show ? '' : 'none';
          });
        });
      });
    });
  }

  /* ——— Contact form (AJAX) ——————————————————————— */
  function initForms() {
    document.querySelectorAll('[data-ab-form]').forEach(function (form) {
      var msg = form.querySelector('.ab-form__msg');

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Honeypot.
        var honey = form.querySelector('input[name="ab_website"]');
        if (honey && honey.value) return;

        var valid = true;
        form.querySelectorAll('[required]').forEach(function (field) {
          var wrap = field.closest('.ab-field');
          var error = wrap ? wrap.querySelector('.ab-field__error') : null;
          var ok = field.value.trim() !== '';
          if (ok && field.type === 'email') {
            ok = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(field.value.trim());
          }
          if (wrap) wrap.classList.toggle('ab-field--error', !ok);
          if (error) error.style.display = ok ? 'none' : 'block';
          if (!ok) valid = false;
        });

        if (!valid) return;

        var btn = form.querySelector('[type="submit"]');
        var original = btn ? btn.innerHTML : '';
        if (btn) {
          btn.disabled = true;
          btn.innerHTML = '<span>' + ((data.i18n && data.i18n.loading) || '…') + '</span>';
        }

        var body = new FormData(form);
        body.append('action', 'abidar_contact');
        body.append('nonce', data.nonce || '');

        fetch(data.ajaxUrl || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: body,
          credentials: 'same-origin'
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (msg) {
              msg.className = 'ab-form__msg ' + (res.success ? 'is-success' : 'is-error');
              msg.textContent = (res.data && res.data.message) || '';
            }
            if (res.success) form.reset();
          })
          .catch(function () {
            if (msg) {
              msg.className = 'ab-form__msg is-error';
              msg.textContent = (data.i18n && data.i18n.error) || 'Error';
            }
          })
          .finally(function () {
            if (btn) {
              btn.disabled = false;
              btn.innerHTML = original;
            }
          });
      });
    });

    document.querySelectorAll('[data-newsletter]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var input = form.querySelector('input[type="email"]');
        var msg = form.querySelector('.ab-newsletter__msg');
        if (!input || !msg) return;

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(input.value.trim())) {
          msg.textContent = (data.i18n && data.i18n.error) || 'Error';
          return;
        }

        var body = new FormData();
        body.append('action', 'abidar_newsletter');
        body.append('nonce', data.nonce || '');
        body.append('email', input.value.trim());

        fetch(data.ajaxUrl || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: body,
          credentials: 'same-origin'
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            msg.textContent = (res.data && res.data.message) || '';
            if (res.success) form.reset();
          })
          .catch(function () {
            msg.textContent = (data.i18n && data.i18n.error) || 'Error';
          });
      });
    });
  }

  /* ——— Back to top ————————————————————————————————— */
  function initToTop() {
    var btn = document.querySelector('[data-to-top]');
    if (!btn) return;

    var ticking = false;
    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(function () {
        btn.classList.toggle('is-visible', window.pageYOffset > 600);
        ticking = false;
      });
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* ——— Preloader ———————————————————————————————————— */
  function initPreloader() {
    var pre = document.querySelector('.ab-preloader');
    if (!pre) return;
    window.setTimeout(function () {
      pre.classList.add('is-done');
      window.setTimeout(function () { pre.remove(); }, 600);
    }, 350);
  }

  /* ——— Marquee duplication ————————————————————————— */
  function initMarquee() {
    document.querySelectorAll('.ab-marquee__track').forEach(function (track) {
      if (track.getAttribute('data-cloned')) return;
      track.setAttribute('data-cloned', '1');
      track.innerHTML += track.innerHTML;
    });
  }

  /* ——— Testimonial slider (native scroll-snap fallback) ——— */
  function initSmoothAnchors() {
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function (link) {
      link.addEventListener('click', function (e) {
        var id = link.getAttribute('href');
        var target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        var top = target.getBoundingClientRect().top + window.pageYOffset - 100;
        window.scrollTo({ top: top, behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    });
  }

  onReady(function () {
    initStickyHeader();
    initMobileNav();
    initSearch();
    initLangSwitcher();
    initReveal();
    initCounters();
    initAccordion();
    initFilters();
    initForms();
    initToTop();
    initPreloader();
    initMarquee();
    initSmoothAnchors();
  });
})();
