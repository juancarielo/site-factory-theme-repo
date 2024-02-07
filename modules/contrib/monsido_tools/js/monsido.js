// phpcs:ignoreFile

/**
 * @file
 * Contains JavaScript used to integrate monside.
 */

(function ($, Drupal, drupalSettings) {
  'use strict';
  console.log(drupalSettings.monsidoTools);
  Drupal.behaviors.monsido_behavior = {
    attach: function (context, settings) {
      const monsidoTools = drupalSettings.monsidoTools;

      window._monsido = window._monsido || {
        token: monsidoTools.token,
        statistics: {
          enabled: monsidoTools.statistics,
          cookieLessTracking: monsidoTools.cookieLessTracking,
          documentTracking: {
            enabled: monsidoTools.documentTracking,
            // TODO: Manage this part.
            documentCls: "monsido_download",
            documentIgnoreCls: "monsido_ignore_download",
            documentExt: ["pdf", "doc", "ppt", "docx", "pptx"],
          },
        },
        pageCorrect: {
          enabled: monsidoTools.pageCorrect
        },
        heatmap: {
          enabled: monsidoTools.heatmap
        },

        // Page assist v1
        pageAssist: {
          enabled: (monsidoTools.addPageAssistScript == 1) ? true : false,
          theme: "white",
          direction: "right",
          skipTo: true,
        },

        // Page assist v2
        pageAssistV2: {
          enabled: (monsidoTools.addPageAssistScript == 2) ? true : false,
          theme: "light",
          mainColor: "#783ce2",
          textColor: "#fff",
          linkColor: "#783ce2",
          buttonHoverColor: "#783ce2",
          mainDarkColor: "#052942",
          textDarkColor: "#fff",
          linkColorDark: "#ffcf4b",
          buttonHoverDarkColor: "#ffcf4b",
          greeting: "Discover your personalization options",
          direction: "leftbottom",
          coordinates: "undefined undefined undefined undefined",
          iconShape: "circle",
          title: "Personalization Options",
          titleText: "Welcome to PageAssist™ toolbar! Adjust the options below to cater the website to your accessibility needs.",
          iconPictureUrl: "logo",
          logoPictureUrl: "",
          logoPictureBase64: "",
          languages: [""],
          defaultLanguage: "",
          skipTo: false,
          alwaysOnTop: false,
        },
      };
    }
  }
})(jQuery, Drupal, drupalSettings);
