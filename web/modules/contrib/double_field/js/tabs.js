/**
 * @file
 * Behavior for tabs formatter.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.doubleFieldTabs = {
    attach: function () {

      $('.double-field-tabs').tabs();

    }
  };

})(jQuery);
