/**
 * Abidar theme options panel.
 */
(function ($) {
  'use strict';

  $(function () {
    /* Colour pickers */
    if ($.fn.wpColorPicker) {
      $('.ab-color-field').wpColorPicker();
    }

    /* Media picker for image fields */
    $('.ab-image-field').each(function () {
      var $wrap = $(this);
      var $input = $wrap.find('.ab-image-field__input');
      var $preview = $wrap.find('.ab-image-field__preview');
      var frame = null;

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
            $input.val(attachment.id);
            $preview.html('<img src="' + (attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url) + '" alt="">');
          });
        }

        frame.open();
      });

      $wrap.find('.ab-image-field__remove').on('click', function (e) {
        e.preventDefault();
        $input.val('');
        $preview.empty();
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
