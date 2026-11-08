/**
 * Gutenberg block registration for the Abidar sections.
 * Mirrors inc/blocks.php on the JS side so the editor shows a live preview.
 */
(function (blocks, element, components, blockEditor, serverSideRender) {
  'use strict';

  var el = element.createElement;
  var __ = wp.i18n.__;
  var InspectorControls = blockEditor.InspectorControls;
  var PanelBody = components.PanelBody;
  var SelectControl = components.SelectControl;
  var ToggleControl = components.ToggleControl;
  var RangeControl = components.RangeControl;

  var sections = (window.abidarBlocks && window.abidarBlocks.sections) || [];

  blocks.registerBlockType('abidar/section', {
    title: __('Abidar section', 'abidar'),
    description: __('Insert a ready-made section from the Abidar theme.', 'abidar'),
    icon: 'layout',
    category: 'design',
    attributes: {
      section: { type: 'string', default: 'services' },
      surface: { type: 'boolean', default: false },
      count: { type: 'number', default: 6 }
    },

    edit: function (props) {
      var attributes = props.attributes;
      var setAttributes = props.setAttributes;

      return el(
        'div',
        blockEditor.useBlockProps ? blockEditor.useBlockProps() : {},
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: __('Section', 'abidar'), initialOpen: true },
            el(SelectControl, {
              label: __('Type', 'abidar'),
              value: attributes.section,
              options: sections,
              onChange: function (value) { setAttributes({ section: value }); }
            }),
            el(ToggleControl, {
              label: __('Surface background', 'abidar'),
              checked: attributes.surface,
              onChange: function (value) { setAttributes({ surface: value }); }
            }),
            el(RangeControl, {
              label: __('Items', 'abidar'),
              value: attributes.count,
              min: 1,
              max: 12,
              onChange: function (value) { setAttributes({ count: value }); }
            })
          )
        ),
        serverSideRender
          ? el(serverSideRender, { block: 'abidar/section', attributes: attributes })
          : el('p', null, __('Preview unavailable.', 'abidar'))
      );
    },

    save: function () {
      return null;
    }
  });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender);
