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


  /* ——— Elementor masthead (header widget) ————————————— */
  function initMasthead() {
    var wraps = document.querySelectorAll('[data-masthead]');
    if (!wraps.length) return;

    wraps.forEach(function (wrap) {
      var bar = wrap.querySelector('[data-masthead-bar]');
      var drawer = wrap.querySelector('.ab-masthead__drawer');
      var toggles = wrap.querySelectorAll('[data-ab-drawer]');
      var backdrop = null;

      /* Sticky elevation */
      if (bar) {
        var ticking = false;
        function onScroll() {
          if (ticking) return;
          ticking = true;
          window.requestAnimationFrame(function () {
            bar.classList.toggle('is-scrolled', window.pageYOffset > 24);
            ticking = false;
          });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
      }

      /* Mobile drawer */
      if (!drawer || !toggles.length) return;

      function setDrawer(open) {
        drawer.hidden = false;
        // Force reflow so the transition runs from the off-canvas position.
        void drawer.offsetWidth;
        drawer.classList.toggle('is-open', open);
        document.body.classList.toggle('ab-drawer-open', open);

        toggles.forEach(function (btn) {
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        if (open) {
          if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'ab-drawer-backdrop';
            backdrop.addEventListener('click', function () { setDrawer(false); });
            document.body.appendChild(backdrop);
          }
          requestFrame(function () { backdrop.classList.add('is-open'); });
          var first = drawer.querySelector('a, button');
          if (first) first.focus({ preventScroll: true });
        } else {
          if (backdrop) {
            backdrop.classList.remove('is-open');
            window.setTimeout(function () {
              if (backdrop && !backdrop.classList.contains('is-open')) {
                backdrop.remove();
                backdrop = null;
              }
            }, 320);
          }
          drawer.hidden = true;
        }
      }

      function requestFrame(fn) {
        if (window.requestAnimationFrame) window.requestAnimationFrame(fn);
        else window.setTimeout(fn, 16);
      }

      toggles.forEach(function (btn) {
        btn.addEventListener('click', function () {
          setDrawer(!drawer.classList.contains('is-open'));
        });
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
          setDrawer(false);
        }
      });
    });
  }

  /* ——— Search panel from the header widget ——————————— */
  function initMastheadSearch() {
    var panels = document.querySelectorAll('[data-ab-search-panel]');
    if (!panels.length) return;

    var toggles = document.querySelectorAll('[data-ab-search]');

    toggles.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var panel = document.querySelector('[data-ab-search-panel]');
        if (!panel) return;
        var opening = panel.hasAttribute('hidden');
        if (opening) {
          panel.removeAttribute('hidden');
          var input = panel.querySelector('input[type="search"]');
          if (input) input.focus();
        } else {
          panel.setAttribute('hidden', '');
        }
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      document.querySelectorAll('[data-ab-search-panel]:not([hidden])').forEach(function (panel) {
        panel.setAttribute('hidden', '');
      });
    });
  }

  /* ——— Dismissible top bar ————————————————————————— */
  function initTopbar() {
    var wrap = document.querySelector('[data-dismissible]');
    if (!wrap) return;

    var key = 'abidar_topbar_hidden';

    try {
      if (window.sessionStorage.getItem(key) === '1') {
        wrap.hidden = true;
        return;
      }
    } catch (err) { /* storage blocked — ignore */ }

    var btn = wrap.querySelector('[data-ab-dismiss]');
    if (!btn) return;

    btn.addEventListener('click', function () {
      wrap.hidden = true;
      try { window.sessionStorage.setItem(key, '1'); } catch (err) { /* ignore */ }
    });
  }

  /* ——— Mega menu drawer ——————————————————————————— */
  function initMegaMenu() {
    document.querySelectorAll('[data-mega]').forEach(function (mega) {
      var toggle = mega.querySelector('[data-mega-toggle]');
      if (!toggle) return;

      toggle.addEventListener('click', function () {
        var open = mega.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mega.classList.contains('is-open')) {
          mega.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
        }
      });

      /* Keyboard support for the mega panels */
      mega.querySelectorAll('.has-panel > .ab-mega__link').forEach(function (link) {
        link.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            var item = link.parentNode;
            item.classList.toggle('is-panel-open');
          }
        });
      });
    });
  }

  /* ——— Newsletter form (footer widget) ————————————— */
  function initNewsletter() {
    document.querySelectorAll('[data-newsletter]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var email = form.querySelector('input[type="email"]');
        if (!email || !email.value) return;

        var btn = form.querySelector('button');
        if (btn) btn.disabled = true;

        var body = new URLSearchParams();
        body.append('action', 'abidar_newsletter');
        body.append('email', email.value);
        body.append('nonce', data.nonce || '');

        fetch(data.ajaxUrl || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
          body: body.toString()
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            form.classList.add(res.success ? 'is-done' : 'is-error');
            if (res.success && res.data && res.data.message && window.abidarToast) {
              window.abidarToast(res.data.message);
            }
            if (res.success && email) email.value = '';
          })
          .catch(function () { form.classList.add('is-error'); })
          .finally(function () { if (btn) btn.disabled = false; });
      });
    });
  }

  onReady(function () {
    initStickyHeader();
    initMasthead();
    initMastheadSearch();
    initTopbar();
    initMegaMenu();
    initNewsletter();
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
