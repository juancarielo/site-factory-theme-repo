// phpcs:ignoreFile

/**
 * @file
 * Contains JavaScript used to integrate monside.
 */

(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.behaviors.monsido_consent_manager_behavior = {
    attach: function (context, settings) {
      const monsidoTools = drupalSettings.monsidoTools;

      if (monsidoTools.enableConsentManager) {
        window._monsidoConsentManagerConfig = window._monsidoConsentManagerConfig || {
          token: drupalSettings.monsidoTools.token,
          privacyRegulation: "ccpa",
          settings: {
            manualStartup: false,
            hideOnAccepted: false,
            perCategoryConsent: true,
            explicitRejectOption: false,
            hasOverlay: false,
          },
          i18n: {
            languages: ["en_US"],
            defaultLanguage: "en_US"
          },
          theme: {
            buttonColor: "#783ce2",
            buttonTextColor: "#fff",
            iconPictureUrl: "cookie",
            iconShape: "circle",
            position: "bottom-right",
          },
          links: {
            cookiePolicyUrl: "https://drupal10.lndo.site/",
            optOutUrl: "https://drupal10.lndo.site/",
          },
        };
      }
    }
  }
})(jQuery, Drupal, drupalSettings);
