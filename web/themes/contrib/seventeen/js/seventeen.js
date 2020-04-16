/**
 * @file
 * Some UI improvements.
 */

(function ($, Drupal, settings) {

  'use strict';

  Drupal.behaviors.seventeenSystemMessages = {
    attach: function() {

      $('.messages:not(:has(span.hide-message))')
        .append('<span class="hide-message" aria-label="' + Drupal.t('Close') + '">&#10799;</span>')
        .not('.view-changed')
        .fadeIn(500);

      $('.hide-message')
        .click(function() {
          $(this).parent().fadeOut()
        });

    }
  };

  Drupal.behaviors.seventeenStickyFormActions = {
    attach: function (context) {

      var $element = $(context)
        .find('[data-drupal-selector="edit-actions"]')
        .once('sticky-form-actions');

      if ($element.length === 0) {
        return;
      }

      var mode = settings.seventeen.stickyFormActions;
      if (mode === 'never') {
        return;
      }

      // Skip Views UI pages.
      if (settings.path.currentPath.lastIndexOf('admin/structure/views', 0) === 0) {
        return;
      }

      // Skip themes admin page (Save config applies only to bottom).
      if (settings.path.currentPath === 'admin/appearance') {
        return;
      }

      // Skip Views exposed filters and bulk operations.
      var $form = $element.closest('form');
      if ($form.data('drupal-selector').lastIndexOf('views-', 0) === 0) {
        return;
      }

      // We need this to track the original position of the element because its
      // initial height may change after ajax requests.
      var $stickyMarker = $('<div/>').insertBefore($element);
      var elementLeft = $element[0].getBoundingClientRect().left;

      var $window = $(window);

      var setSticky = function ($element) {
        $element
          .addClass('sticky-form-actions')
          .css('padding-left', elementLeft + 'px')
          .css('padding-right', elementLeft + 'px');
      };

      var removeSticky = function ($element) {
        $element.removeClass('sticky-form-actions')
          .css('padding-left', '')
          .css('padding-right', '');
      };

      if (mode === 'always') {
        setSticky($element);
      }
      else {
        var checkSticky = Drupal.debounce(function () {
          var elementTop = $stickyMarker[0].getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop);
          var offset =  $element.height() + 15;
          $window.scrollTop() + $window.height() < elementTop + offset ?
            setSticky($element) : removeSticky($element)
        }, 10);
        $window
          .scroll(checkSticky)
          .resize(checkSticky);
        checkSticky();
      }

    }
  }

})(jQuery, Drupal, drupalSettings);
