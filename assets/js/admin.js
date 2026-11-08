/**
 * Abidar theme options panel.
 */
(function ($) {
  'use strict';

  $(function () {
    /* Unsaved-changes guard for the options form */
    var $form = $('.ab-admin__form');
    if ($form.length) {
      var dirty = false;
      $form.on('change input', 'input, select, textarea', function () {
        dirty = true;
      });
      $form.on('submit', function () {
        dirty = false;
      });
      $(window).on('beforeunload', function () {
        if (dirty) return '';
      });
      // Tab links and the WP menu should not silently throw work away.
      $('.ab-admin__tab').on('click', function (e) {
        if (dirty && !window.confirm($form.data('confirm-discard') || 'Discard unsaved changes?')) {
          e.preventDefault();
        }
      });
    }

    /* Colour pickers */
    if ($.fn.wpColorPicker) {
      $('.ab-color-field').wpColorPicker();
    }

    /* Media picker for image fields */
    $('.ab-image-field').each(function () {
      var $wrap = $(this);
      var $input = $wrap.find('.ab-image-field__input');
      var $preview = $wrap.find('.ab-image-field__preview');
      var $remove = $wrap.find('.ab-image-field__remove');
      var frame = null;

      // The remove button only makes sense once an image exists.
      function syncRemove() {
        $wrap.attr('data-empty', $preview.find('img').length ? '' : '1');
      }
      syncRemove();

      $wrap.find('.ab-image-field__select').on('click', function (e) {
        e.preventDefault();

        if (!frame) {
          frame = wp.media({
            title: 'Select image',
            button: { text: 'Use this image' },
            multiple: false
          });

          frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            var src = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
            $input.val(attachment.id);
            $preview.html($('<img>').attr({ src: src, alt: '' }));
            syncRemove();
          });
        }

        frame.open();
      });

      $wrap.find('.ab-image-field__remove').on('click', function (e) {
        e.preventDefault();
        $input.val('');
        $preview.empty();
        syncRemove();
      });
    });

    /* Language copy editor filter */
    var $search = $('#ab-lang-search');

    if ($search.length) {
      $search.on('input', function () {
        var term = $(this).val().toLowerCase().trim();

        $('.ab-lang-row').each(function () {
          var $row = $(this);
          var haystack = $row.text().toLowerCase();
          $row.toggleClass('is-hidden', term !== '' && haystack.indexOf(term) === -1);
        });

        $('.ab-lang-editor__group').each(function () {
          var $group = $(this);
          var visible = $group.find('.ab-lang-row:not(.is-hidden)').length;
          $group.toggle(visible > 0);
          if (term !== '') { $group.attr('open', true); }
        });
      });
    }

    /* Keep textareas growing with content */
    $('.ab-lang-input').on('input', function () {
      this.style.height = 'auto';
      this.style.height = this.scrollHeight + 'px';
    }).trigger('input');
  });
})(jQuery);
