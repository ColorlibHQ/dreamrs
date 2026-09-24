/**
 * Dreamrs front-end behaviour, without jQuery.
 *
 * The plugin calls keep the options they always had; ColorlibUI provides
 * drop-in versions of Magnific Popup, Slick and AjaxChimp that build the same
 * markup, so the theme's stylesheets apply unchanged. The grid uses
 * WordPress core Masonry through UI.masonry.
 */
(function () {
  'use strict';

  var UI = window.ColorlibUI;
  if (!UI) return;

  // menu fixed js code
  UI.ready(function () {
    var menus = UI.toElements('.main_menu');
    window.addEventListener('scroll', function () {
      var fixed = window.pageYOffset + 1 > 50;
      menus.forEach(function (menu) {
        if (fixed) {
          menu.classList.add('menu_fixed', 'animated', 'fadeInDown');
        } else {
          menu.classList.remove('menu_fixed', 'animated', 'fadeInDown');
        }
      });
    }, { passive: true });
  });

  // The old script enhanced the selects three times (once if #default-select
  // existed, once behind a check that could never pass, and unconditionally
  // on DOM ready); the unconditional call is the one that counted.
  UI.enhanceSelects('select');

  // page-scroll: smooth scroll to the link's target, 80px below the header.
  UI.ready(function () {
    var headerH = 80;
    UI.toElements('.page-scroll').forEach(function (anchor) {
      anchor.addEventListener('click', function (event) {
        var target = null;
        try {
          target = document.querySelector(anchor.getAttribute('href'));
        } catch (e) {
          // Not a selector (a full URL, a bare "#"): leave the link alone.
        }
        if (!target) return;
        UI.scrollToY(UI.offset(target).top - headerH, 1500);
        event.preventDefault();
      });
    });
  });

  //counter up
  UI.counter('.counter', { time: 2000 });

  //masonry js
  UI.masonry('.grid', {
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer',
    percentPosition: true
  });

  //gallery js
  UI.magnific('.img-gal', {
    type: 'image',
    gallery: {
      enabled: true
    }
  });

  UI.slick('.slider', {
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    speed: 500,
    infinite: true,
    asNavFor: '.slider-nav-thumbnails',
    autoplay: true,
    autoplaySpeed: 3000,
    touchThreshold: 1000,
    pauseOnFocus: true,
    dots: false
  });

  UI.slick('.slider-nav-thumbnails', {
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider',
    focusOnSelect: true,
    infinite: true,
    prevArrow: false,
    nextArrow: false,
    centerMode: true,
    autoplaySpeed: 3000,
    touchThreshold: 1000,
    speed: 500

    // responsive: [
    //   {
    //     breakpoint: 480,
    //     settings: {
    //       centerMode: false,
    //     }
    //   }
    // ]
  });

  //------- Mailchimp js --------//
  UI.ajaxChimp('#mc_embed_signup form');
}());
