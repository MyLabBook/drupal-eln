/**
 * @file
 * Behavior for accordion formatter.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.doubleFieldAccordion = {
    attach: function () {

      $('.double-field-accordion')
        .accordion({
          collapsible: true,
          active: false
        });

    }
  };

})(jQuery);
