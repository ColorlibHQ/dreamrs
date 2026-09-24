/*! ColorlibUI 3.0.0 — core, slick, magnific, isotope, ajaxchimp. Built for this theme from core + the modules it uses. */
/**
 * The interactive pieces these themes actually use, without jQuery.
 *
 * The core: the small helpers every module uses, plus the pieces nearly every
 * theme needs (styled selects, counters, reveal on scroll). Modules for the
 * other plugins the themes used (Owl Carousel, Slick, Magnific Popup, Isotope,
 * SlickNav, ScrollUp, AjaxChimp, ...) are appended after it by the build, only
 * when a theme uses them, and register themselves on window.ColorlibUI.
 * Those libraries are general-purpose; the themes use a narrow slice of them:
 * a looping carousel, a slider with a thumbnail strip, a lightbox for images
 * and video embeds, a styled select, numbers that count up and elements that
 * animate in as they scroll into view.
 *
 * Markup is read from the same class names and data attributes the old
 * plugins used, so templates do not change.
 *
 * Each piece is optional: if the markup is not on the page, nothing runs.
 */
(function () {
  'use strict';

  var PREFERS_REDUCED = window.matchMedia
    ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
    : false;

  /** Elements from a selector, an Element, a NodeList/array, or a jQuery-like object. */
  function toElements(target, root) {
    if (!target) return [];
    if (typeof target === 'string') {
      return Array.prototype.slice.call((root || document).querySelectorAll(target));
    }
    if (target.nodeType === 1) return [target];
    if (typeof target.length === 'number') return Array.prototype.slice.call(target);
    return [];
  }

  /** Dispatch a bubbling CustomEvent carrying detail. */
  function emit(el, type, detail) {
    var event;
    try {
      event = new CustomEvent(type, { bubbles: true, cancelable: true, detail: detail || {} });
    } catch (e) {
      event = document.createEvent('CustomEvent');
      event.initCustomEvent(type, true, true, detail || {});
    }
    return el.dispatchEvent(event);
  }

  /** Shallow-merge plain objects left to right (Object.assign where available). */
  function extend(target) {
    for (var i = 1; i < arguments.length; i++) {
      var src = arguments[i];
      if (!src) continue;
      for (var k in src) {
        if (Object.prototype.hasOwnProperty.call(src, k)) target[k] = src[k];
      }
    }
    return target;
  }

  /** Parse an HTML string into its first element (for navText, prevArrow, ...). */
  function fromHTML(html) {
    var t = document.createElement('template');
    t.innerHTML = String(html).trim();
    return t.content.firstElementChild || document.createTextNode(String(html));
  }

  /** Animate window scroll to y over ms (instant with reduced motion). */
  function scrollToY(y, ms) {
    if (PREFERS_REDUCED || !ms) { window.scrollTo(0, y); return; }
    var from = window.pageYOffset, start = null;
    function step(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / ms, 1);
      var eased = p < 0.5 ? 2 * p * p : -1 + (4 - 2 * p) * p;
      window.scrollTo(0, from + (y - from) * eased);
      if (p < 1) window.requestAnimationFrame(step);
    }
    window.requestAnimationFrame(step);
  }

  /* ------------------------------------------------------------------ *
   * The jQuery effects the themes use, on the Web Animations API.
   * jQuery's semantics: slideDown/fadeIn show a hidden element (display
   * from the stylesheet, or block), slideUp/fadeOut end with display:none.
   * With reduced motion the end state is applied at once.
   * ------------------------------------------------------------------ */

  function isHidden(el) {
    return window.getComputedStyle(el).display === 'none';
  }

  function show(el) {
    el.style.display = '';
    if (isHidden(el)) el.style.display = 'block';
  }

  function animateTo(el, frames, ms, done) {
    if (PREFERS_REDUCED || !ms || !el.animate) { if (done) done(); return; }
    var anim = el.animate(frames, { duration: ms, easing: 'ease' });
    anim.onfinish = function () { if (done) done(); };
  }

  /** slide(el, 'up' | 'down' | 'toggle', ms = 400, done) */
  function slide(target, dir, ms, done) {
    if (ms === undefined) ms = 400;
    toElements(target).forEach(function (el) {
      var hidden = isHidden(el);
      var down = dir === 'down' || (dir === 'toggle' && hidden);
      if (down && !hidden) return;
      if (!down && hidden) return;
      if (down) show(el);
      var h = el.scrollHeight + 'px';
      el.style.overflow = 'hidden';
      animateTo(el, down ? [{ height: '0px' }, { height: h }] : [{ height: h }, { height: '0px' }], ms, function () {
        el.style.overflow = '';
        if (!down) el.style.display = 'none';
        if (done) done.call(el);
      });
    });
  }

  /** fade(el, 'in' | 'out' | 'toggle', ms = 400, done) */
  function fade(target, dir, ms, done) {
    if (ms === undefined) ms = 400;
    toElements(target).forEach(function (el) {
      var hidden = isHidden(el);
      var fadeIn = dir === 'in' || (dir === 'toggle' && hidden);
      if (fadeIn && !hidden) return;
      if (!fadeIn && hidden) return;
      if (fadeIn) show(el);
      animateTo(el, fadeIn ? [{ opacity: 0 }, { opacity: 1 }] : [{ opacity: 1 }, { opacity: 0 }], ms, function () {
        if (!fadeIn) el.style.display = 'none';
        if (done) done.call(el);
      });
    });
  }

  /** Document offset of an element, like jQuery's .offset(). */
  function offset(el) {
    var r = el.getBoundingClientRect();
    return { top: r.top + window.pageYOffset, left: r.left + window.pageXOffset };
  }

  /**
   * POST/GET to WordPress (admin-ajax.php and friends) the way $.ajax did:
   * data is form-encoded, the response parsed as JSON when it is JSON.
   * Returns a Promise.
   */
  function request(url, opts) {
    opts = opts || {};
    var method = (opts.method || opts.type || 'POST').toUpperCase();
    var body = null;
    if (opts.data) {
      var params = new URLSearchParams();
      Object.keys(opts.data).forEach(function (k) { params.append(k, opts.data[k]); });
      if (method === 'GET') url += (url.indexOf('?') < 0 ? '?' : '&') + params.toString();
      else body = params;
    }
    return fetch(url, { method: method, body: body, credentials: 'same-origin' }).then(function (res) {
      return res.text().then(function (text) {
        try { return JSON.parse(text); } catch (e) { return text; }
      });
    });
  }

  function debounce(fn, wait) {
    var t;
    return function () {
      clearTimeout(t);
      t = setTimeout(fn, wait);
    };
  }

  function videoSource(href) {
    var yt = href.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/);
    if (yt) return 'https://www.youtube.com/embed/' + yt[1] + '?autoplay=1&rel=0';
    var vm = href.match(/vimeo\.com\/(?:video\/)?(\d+)/);
    if (vm) return 'https://player.vimeo.com/video/' + vm[1] + '?autoplay=1';
    return href;
  }


  /* ------------------------------------------------------------------ *
   * Select
   *
   * Builds the same markup jQuery Nice Select produced, because the themes
   * style that structure: .nice-select > .current, and a .list of .option.
   * The original select stays in the DOM and keeps carrying the value, so
   * forms submit exactly as before and assistive technology still sees it.
   * ------------------------------------------------------------------ */

  function enhanceSelect(select) {
    if (select.dataset.clEnhanced) return;
    select.dataset.clEnhanced = '1';

    var wrap = document.createElement('div');
    wrap.className = 'nice-select ' + (select.className || '');
    wrap.tabIndex = 0;
    wrap.setAttribute('role', 'button');
    wrap.setAttribute('aria-haspopup', 'listbox');
    wrap.setAttribute('aria-expanded', 'false');

    var current = document.createElement('span');
    current.className = 'current';

    var list = document.createElement('ul');
    list.className = 'list';
    list.setAttribute('role', 'listbox');

    Array.prototype.forEach.call(select.options, function (option) {
      var li = document.createElement('li');
      li.className = 'option' + (option.selected ? ' selected' : '') +
        (option.disabled ? ' disabled' : '');
      li.textContent = option.textContent;
      li.dataset.value = option.value;
      li.setAttribute('role', 'option');
      li.setAttribute('aria-selected', option.selected ? 'true' : 'false');

      li.addEventListener('click', function (e) {
        // The wrapper toggles on click; without this the choice would bubble
        // up and reopen the list it just closed.
        e.stopPropagation();
        if (option.disabled) return;
        select.value = option.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
        sync();
        wrap.classList.remove('open');
        wrap.setAttribute('aria-expanded', 'false');
      });
      list.appendChild(li);
    });

    function sync() {
      var chosen = select.options[select.selectedIndex];
      current.textContent = chosen ? chosen.textContent : '';
      Array.prototype.forEach.call(list.children, function (li) {
        var on = li.dataset.value === select.value;
        li.classList.toggle('selected', on);
        li.setAttribute('aria-selected', on ? 'true' : 'false');
      });
    }

    wrap.addEventListener('click', function () {
      var open = wrap.classList.toggle('open');
      wrap.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    wrap.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); wrap.click(); }
      if (e.key === 'Escape') { wrap.classList.remove('open'); }
    });
    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) {
        wrap.classList.remove('open');
        wrap.setAttribute('aria-expanded', 'false');
      }
    });
    select.addEventListener('change', sync);

    wrap.appendChild(current);
    wrap.appendChild(list);
    select.parentNode.insertBefore(wrap, select);

    // Kept for the form and for assistive technology, but out of the way.
    select.style.position = 'absolute';
    select.style.width = '1px';
    select.style.height = '1px';
    select.style.opacity = '0';
    select.style.pointerEvents = 'none';

    sync();
  }

  /* ------------------------------------------------------------------ *
   * Running when the page is ready
   *
   * Callers are theme scripts in the footer and inline scripts printed by
   * widgets in the middle of the page; both are safe.
   * ------------------------------------------------------------------ */

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  function each(target, fn) {
    ready(function () {
      toElements(target).forEach(fn);
    });
  }

  /** $('select').niceSelect(), without jQuery. */
  function enhanceSelects(selector) {
    each(selector || 'select', function (select) {
      // Nice Select never handled multiple selects, and neither does this.
      if (select.multiple) return;
      enhanceSelect(select);
    });
  }


  /* ------------------------------------------------------------------ *
   * Counter
   *
   * Replaces jQuery CounterUp and the Waypoints library it depended on.
   * Like CounterUp, the number is read from the element's own text and left
   * untouched until the element scrolls into view; it then counts up from
   * zero and always finishes on the original text. Text that is not a plain
   * number ("24/7") is left alone.
   * ------------------------------------------------------------------ */

  function counter(selector, options) {
    var time = (options && options.time) || 1000;

    each(selector, function (el) {
      if (el.dataset.clCounter) return;
      el.dataset.clCounter = '1';

      var text = el.textContent.trim();
      var plain = text.replace(/,/g, '');
      if (!/^\d+(\.\d+)?$/.test(plain)) return;
      if (PREFERS_REDUCED || !('IntersectionObserver' in window)) return;

      var target = parseFloat(plain);
      var decimals = (plain.split('.')[1] || '').length;
      var commas = /\d,\d/.test(text);

      function format(n) {
        var s = n.toFixed(decimals);
        return commas ? s.replace(/\B(?=(\d{3})+(?!\d))/g, ',') : s;
      }

      var observer = new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) return;
        observer.disconnect();

        var start = null;
        function step(now) {
          if (start === null) start = now;
          var progress = Math.min((now - start) / time, 1);
          el.textContent = progress < 1 ? format(target * progress) : text;
          if (progress < 1) window.requestAnimationFrame(step);
        }
        window.requestAnimationFrame(step);
      });
      observer.observe(el);
    });
  }


  /* ------------------------------------------------------------------ *
   * Reveal on scroll
   *
   * Replaces WOW.js, against the same markup: an element with class "wow"
   * and an animate.css animation class, plus optional data-wow-duration,
   * data-wow-delay and data-wow-iteration. It is hidden until it scrolls
   * into view, then gets the "animated" class.
   *
   * The animation name is held at "none" until then, as WOW did: otherwise
   * the animation has already run (at zero duration) by the time "animated"
   * gives it a real one, and nothing moves. With reduced motion requested,
   * or no IntersectionObserver, elements are simply left visible.
   * ------------------------------------------------------------------ */

  function reveal(selector, options) {
    var offset = (options && options.offset) || 0;
    if (PREFERS_REDUCED || !('IntersectionObserver' in window)) return;

    each(selector || '.wow', function (el) {
      if (el.dataset.clReveal) return;
      el.dataset.clReveal = '1';

      el.style.visibility = 'hidden';
      el.style.animationName = 'none';

      var observer = new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) return;
        observer.disconnect();

        var data = el.dataset;
        if (data.wowDuration) el.style.animationDuration = data.wowDuration;
        if (data.wowDelay) el.style.animationDelay = data.wowDelay;
        if (data.wowIteration) el.style.animationIterationCount = data.wowIteration;
        el.style.animationName = '';
        el.style.visibility = 'visible';
        el.classList.add('animated');
      }, { rootMargin: '0px 0px ' + (-offset) + 'px 0px' });
      observer.observe(el);
    });
  }

  var UI = window.ColorlibUI || {};
  extend(UI, {
    version: '3.0.0',
    reducedMotion: PREFERS_REDUCED,
    toElements: toElements,
    each: each,
    emit: emit,
    extend: extend,
    fromHTML: fromHTML,
    scrollToY: scrollToY,
    slide: slide,
    fade: fade,
    offset: offset,
    request: request,
    enhanceSelect: enhanceSelect,
    enhanceSelects: enhanceSelects,
    counter: counter,
    reveal: reveal,
    ready: ready,
    videoSource: videoSource,
    debounce: debounce
  });
  window.ColorlibUI = UI;
}());

/* ColorlibUI module: slick — replaces Slick carousel v1.4.1 (Ken Wheeler).
 * Every theme that uses Slick ships 1.4.1 and styles the DOM it builds, so this
 * is a port of that version rather than a new carousel: the same options and
 * defaults, the same markup (.slick-slider.slick-initialized > .slick-list >
 * .slick-track > .slick-slide, infinite-mode .slick-cloned copies, the prev/next
 * arrows, ul.slick-dots), the same inline widths and translate3d() positions,
 * and the same arithmetic for centerMode, fade, vertical, asNavFor and
 * responsive breakpoints. Class names are those of 1.4.1, which predates
 * .slick-arrow, .slick-current and .slick-dotted, so none of those are added.
 * Events use Slick's names and are dispatched on the slider element; what
 * jQuery passed as extra handler arguments is in event.detail. */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var DEFAULTS = {
    accessibility: true,
    adaptiveHeight: false,
    appendArrows: null, // null = the slider itself
    appendDots: null,
    arrows: true,
    asNavFor: null,
    prevArrow: '<button type="button" data-role="none" class="slick-prev">Previous</button>',
    nextArrow: '<button type="button" data-role="none" class="slick-next">Next</button>',
    autoplay: false,
    autoplaySpeed: 3000,
    centerMode: false,
    centerPadding: '50px',
    cssEase: 'ease',
    customPaging: function (slider, i) {
      return '<button type="button" data-role="none">' + (i + 1) + '</button>';
    },
    dots: false,
    dotsClass: 'slick-dots',
    draggable: true,
    edgeFriction: 0.35,
    fade: false,
    focusOnSelect: false,
    infinite: true,
    initialSlide: 0,
    mobileFirst: false,
    pauseOnHover: true,
    pauseOnDotsHover: false,
    respondTo: 'window',
    responsive: null,
    slide: '',
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 500,
    swipe: true,
    touchMove: true,
    touchThreshold: 5,
    vertical: false,
    waitForAnimate: true
  };

  // Slick decides "HTML or selector?" with this exact test.
  var HTML_EXPR = /^(?:\s*(<[\w\W]+>)[^>]*)$/;

  /* ---- small DOM helpers (jQuery equivalents Slick relied on) ---- */

  function num(v) { return parseFloat(v) || 0; }
  function slice(list) { return Array.prototype.slice.call(list || []); }
  function eq(list, i) { var j = i < 0 ? list.length + i : i; return list[j] ? [list[j]] : []; }
  function addClass(list, c) { list.forEach(function (el) { el.classList.add(c); }); }
  function removeNode(el) { if (el && el.parentNode) el.parentNode.removeChild(el); }
  function elements(target) {
    try { return UI.toElements(target); } catch (e) { return []; } // bad selector = nothing
  }
  function parseHTML(html) {
    var t = document.createElement('template');
    t.innerHTML = html;
    return slice(t.content.childNodes);
  }
  function isElement(n) { return n.nodeType === 1; }
  function siblingIndex(el) {
    var i = 0;
    while ((el = el.previousElementSibling)) i++;
    return i;
  }

  /* jQuery .width()/.outerHeight(true)/... : fractional, box-sizing aware.
   * box: 'content' (.width), 'border' (.outerWidth), 'margin' (.outerWidth(true)). */
  function size(el, dim, box) {
    var cs = getComputedStyle(el), w = dim === 'width';
    var a = w ? 'Left' : 'Top', b = w ? 'Right' : 'Bottom';
    var pad = num(cs['padding' + a]) + num(cs['padding' + b]);
    var brd = num(cs['border' + a + 'Width']) + num(cs['border' + b + 'Width']);
    var val = cs[dim], borderBox = cs.boxSizing === 'border-box';
    if (val === 'auto' && el.getClientRects().length) {
      val = w ? el.offsetWidth : el.offsetHeight;
      borderBox = true;
    }
    val = num(val);
    var content = borderBox ? val - pad - brd : val;
    if (box === 'content') return content;
    if (box === 'border') return content + pad + brd;
    return content + pad + brd + num(cs['margin' + a]) + num(cs['margin' + b]);
  }

  // jQuery .width(n)/.height(n): n is the content size, whatever the box-sizing.
  function setSize(el, dim, value) {
    var cs = getComputedStyle(el), w = dim === 'width', extra = 0;
    if (cs.boxSizing === 'border-box') {
      extra = w
        ? num(cs.paddingLeft) + num(cs.paddingRight) + num(cs.borderLeftWidth) + num(cs.borderRightWidth)
        : num(cs.paddingTop) + num(cs.paddingBottom) + num(cs.borderTopWidth) + num(cs.borderBottomWidth);
    }
    if (value !== value) return; // NaN: jQuery ignores it
    el.style[dim] = Math.max(0, value + extra) + 'px';
  }

  // jQuery .appendTo(): with several targets the node goes into the last one
  // and copies into the others.
  function appendTo(nodes, targets) {
    var out = [];
    targets.forEach(function (t, i) {
      nodes.forEach(function (n) {
        var node = i === targets.length - 1 ? n : n.cloneNode(true);
        t.appendChild(node);
        out.push(node);
      });
    });
    return out;
  }

  // Slick hid arrows and dots with jQuery .hide() while measuring and brought
  // them back with .show(), which also forces an element that a stylesheet
  // hides back to its tag's default display. Kept, so themes look the same.
  var defaultDisplay = {};
  function show(el, prior) {
    el.style.display = prior === 'none' ? '' : prior;
    if (el.style.display === '' && el.isConnected && getComputedStyle(el).display === 'none') {
      var tag = el.nodeName, d = defaultDisplay[tag];
      if (!d) {
        var tmp = document.body.appendChild(document.createElement(tag));
        d = getComputedStyle(tmp).display;
        removeNode(tmp);
        defaultDisplay[tag] = d = d === 'none' ? 'block' : d;
      }
      el.style.display = d;
    }
  }

  /* ------------------------------------------------------------------ */

  var uid = 0;

  function Slick(el, settings) {
    var _ = this, dataOptions = {}, responsive, i;
    var attr = el.getAttribute('data-slick');
    if (attr) { try { dataOptions = JSON.parse(attr) || {}; } catch (e) { dataOptions = {}; } }

    _.$slider = el;
    _.instanceUid = uid++;
    _.listeners = [];
    _.options = UI.extend({}, DEFAULTS, dataOptions, settings || {});
    // Slick mutates options (fade turns centerMode off, centerMode forces
    // slidesToScroll 1) and later merges breakpoints over the result, so the
    // two names must share one object, as they did.
    _.originalSettings = _.options;
    _.activeBreakpoint = null;
    _.breakpoints = [];
    _.breakpointSettings = {};
    _.paused = false;
    _.shouldClick = true;
    _.windowWidth = 0;
    _.resetState();
    _.currentSlide = _.options.initialSlide;

    responsive = _.options.responsive;
    if (responsive && responsive.length > -1) {
      _.respondTo = _.options.respondTo || 'window';
      for (i = 0; i < responsive.length; i++) {
        _.breakpoints.push(responsive[i].breakpoint);
        _.breakpointSettings[responsive[i].breakpoint] = responsive[i].settings;
      }
      _.breakpoints.sort(function (a, b) { return _.options.mobileFirst === true ? a - b : b - a; });
    }

    _.init();
    _.checkResponsive(true);
  }

  var proto = Slick.prototype;

  // Slick's "initials": what refresh() resets between rebuilds.
  proto.resetState = function () {
    UI.extend(this, {
      animating: false, dragging: false, autoPlayTimer: null, currentDirection: 0,
      currentLeft: null, currentSlide: 0, direction: 1, $dots: null, listWidth: null,
      listHeight: null, $nextArrow: [], $prevArrow: [], createdArrows: [], keyArrows: [], slideCount: null,
      slideWidth: null, $slideTrack: null, $slides: [], slideOffset: 0, swipeLeft: null,
      $list: null, touchObject: {}
    });
  };

  proto.on = function (el, type, fn, opts) {
    el.addEventListener(type, fn, opts || false);
    this.listeners.push([el, type, fn, opts || false]);
  };

  proto.init = function () {
    var _ = this;
    if (!_.$slider.classList.contains('slick-initialized')) {
      _.$slider.classList.add('slick-initialized');
      _.$slider.setAttribute('data-cl-slick', '1');
      _.buildOut();
      _.setProps();
      _.startLoad();
      _.loadSlider();
      _.initializeEvents();
      _.updateArrows();
      _.updateDots();
    }
    UI.emit(_.$slider, 'init', { slick: _ });
  };

  proto.buildOut = function () {
    var _ = this, o = _.options, slider = _.$slider, track, list;

    _.$slides = slice(slider.children).filter(function (c) {
      return !c.classList.contains('slick-cloned') && (!o.slide || c.matches(o.slide));
    });
    _.slideCount = _.$slides.length;
    _.$slides.forEach(function (s, i) {
      s.classList.add('slick-slide');
      s.setAttribute('data-slick-index', i);
    });
    slider.classList.add('slick-slider');

    // wrapAll('<div class="slick-track"/>') then wrap('<div class="slick-list"/>')
    track = document.createElement('div');
    track.className = 'slick-track';
    if (_.slideCount) {
      slider.insertBefore(track, _.$slides[0]);
      _.$slides.forEach(function (s) { track.appendChild(s); });
    } else {
      slider.appendChild(track);
    }
    list = document.createElement('div');
    list.className = 'slick-list';
    track.parentNode.insertBefore(list, track);
    list.appendChild(track);
    _.$slideTrack = track;
    _.$list = list;
    track.style.opacity = 0;

    if (o.centerMode === true) o.slidesToScroll = 1;

    _.setupInfinite();
    _.buildArrows();
    _.buildDots();
    _.updateDots();
    if (o.accessibility === true) list.tabIndex = 0;
    _.setSlideClasses(typeof _.currentSlide === 'number' ? _.currentSlide : 0);
    if (o.draggable === true) list.classList.add('draggable');
  };

  proto.setupInfinite = function () {
    var _ = this, o = _.options, i, count, clone, track = _.$slideTrack;
    if (o.fade === true) o.centerMode = false;
    if (o.infinite !== true || o.fade !== false || _.slideCount <= o.slidesToShow) return;

    function copy(index, dataIndex) {
      clone = _.$slides[index].cloneNode(true);
      clone.removeAttribute('id'); // no duplicate ids
      slice(clone.querySelectorAll('[id]')).forEach(function (n) { n.removeAttribute('id'); });
      clone.setAttribute('data-slick-index', dataIndex);
      clone.classList.add('slick-cloned');
      return clone;
    }
    count = o.centerMode === true ? o.slidesToShow + 1 : o.slidesToShow;
    for (i = _.slideCount; i > _.slideCount - count; i -= 1) {
      track.insertBefore(copy(i - 1, i - 1 - _.slideCount), track.firstChild);
    }
    for (i = 0; i < count; i += 1) {
      track.appendChild(copy(i, i + _.slideCount));
    }
  };

  // prevArrow/nextArrow: an HTML string (built and appended to appendArrows),
  // or a selector / element / NodeList that is used where it already is.
  proto.makeArrow = function (option, label) {
    var _ = this, nodes, els, match;
    if (typeof option === 'string' && (match = HTML_EXPR.exec(option))) {
      nodes = parseHTML(match[1]);
      appendTo(nodes, _.options.appendArrows ? elements(_.options.appendArrows) : [_.$slider]);
      els = nodes.filter(isElement);
      _.createdArrows = _.createdArrows.concat(nodes);
    } else {
      els = elements(option);
    }
    // Accessibility on top of Slick: themes pass icon-only <i>, <div> and
    // <button> arrows. Make each one reachable and named, without adding a
    // second tab stop when it already wraps a link or button.
    els.forEach(function (el) {
      var focus = el.nodeName === 'BUTTON' || (el.nodeName === 'A' && el.hasAttribute('href'))
        ? el : el.querySelector('a[href], button');
      if (el.nodeName === 'BUTTON' && !el.hasAttribute('type')) el.setAttribute('type', 'button');
      if (!focus) {
        focus = el;
        if (!el.hasAttribute('role')) el.setAttribute('role', 'button');
        if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '0');
        _.keyArrows.push(el);
      }
      if (!(focus.textContent || '').trim() && !focus.hasAttribute('aria-label')) focus.setAttribute('aria-label', label);
    });
    return els;
  };

  proto.buildArrows = function () {
    var _ = this, o = _.options;
    if (o.arrows === true && _.slideCount > o.slidesToShow) {
      _.$prevArrow = _.makeArrow(o.prevArrow, 'Previous');
      _.$nextArrow = _.makeArrow(o.nextArrow, 'Next');
      if (o.infinite !== true) addClass(_.$prevArrow, 'slick-disabled');
    }
  };

  proto.buildDots = function () {
    var _ = this, o = _.options, html, i;
    if (o.dots === true && _.slideCount > o.slidesToShow) {
      html = '<ul class="' + o.dotsClass + '">';
      for (i = 0; i <= _.getDotCount(); i += 1) {
        html += '<li>' + o.customPaging.call(_, _, i) + '</li>';
      }
      html += '</ul>';
      _.$dots = appendTo(parseHTML(html).filter(isElement).slice(0, 1),
        o.appendDots ? elements(o.appendDots) : [_.$slider]);
      addClass(eq(_.dotItems(), 0), 'slick-active');
    }
  };

  proto.dotItems = function () {
    var out = [];
    (this.$dots || []).forEach(function (ul) { out = out.concat(slice(ul.querySelectorAll('li'))); });
    return out;
  };

  proto.setProps = function () {
    var _ = this;
    _.positionProp = _.options.vertical === true ? 'top' : 'left';
    _.$slider.classList.toggle('slick-vertical', _.positionProp === 'top');
  };

  proto.arrowsAndDots = function () {
    var _ = this, o = _.options, out = [];
    if (o.arrows === true && _.slideCount > o.slidesToShow) out = out.concat(_.$prevArrow, _.$nextArrow);
    if (o.dots === true && _.slideCount > o.slidesToShow) out = out.concat(_.$dots || []);
    return out;
  };

  proto.startLoad = function () {
    var _ = this;
    _.hiddenUI = _.arrowsAndDots().map(function (el) {
      var prior = el.style.display;
      el.style.display = 'none';
      return [el, prior];
    });
    _.$slider.classList.add('slick-loading');
  };

  proto.loadSlider = function () {
    var _ = this;
    _.setPosition();
    _.$slideTrack.style.opacity = 1;
    _.$slider.classList.remove('slick-loading');
    _.initUI();
  };

  proto.initUI = function () {
    var _ = this;
    (_.hiddenUI || []).forEach(function (h) { show(h[0], h[1]); });
    _.hiddenUI = null;
    if (_.options.autoplay === true) _.autoPlay();
  };

  proto.initializeEvents = function () {
    var _ = this, o = _.options, list = _.$list;

    if (o.arrows === true && _.slideCount > o.slidesToShow) {
      _.$prevArrow.forEach(function (el) { _.bindArrow(el, 'previous'); });
      _.$nextArrow.forEach(function (el) { _.bindArrow(el, 'next'); });
    }
    if (o.dots === true && _.slideCount > o.slidesToShow) {
      _.dotItems().forEach(function (li) {
        _.on(li, 'click', function (e) { _.changeSlide('index', undefined, e.target); });
        if (o.pauseOnDotsHover === true && o.autoplay === true) {
          _.on(li, 'mouseenter', function () { _.paused = true; _.autoPlayClear(); });
          _.on(li, 'mouseleave', function () { _.paused = false; _.autoPlay(); });
        }
      });
    }

    _.on(list, 'touchstart', function (e) { _.swipeHandler(e, 'start'); }, { passive: true });
    _.on(list, 'mousedown', function (e) { _.swipeHandler(e, 'start'); });
    // Not passive: a horizontal swipe has to be able to stop the page scrolling.
    _.on(list, 'touchmove', function (e) { _.swipeHandler(e, 'move'); }, { passive: false });
    _.on(list, 'mousemove', function (e) { _.swipeHandler(e, 'move'); });
    ['touchend', 'mouseup', 'touchcancel', 'mouseleave'].forEach(function (t) {
      _.on(list, t, function (e) { _.swipeHandler(e, 'end'); });
    });
    // A drag that ended over a link must not follow it.
    _.on(list, 'click', function (e) {
      if (_.shouldClick === false) {
        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();
      }
    });

    if (o.autoplay === true) {
      _.on(document, 'visibilitychange', function () {
        if (document.hidden) { _.paused = true; _.autoPlayClear(); } else { _.paused = false; _.autoPlay(); }
      });
      if (o.pauseOnHover === true) {
        _.on(list, 'mouseenter', function () { _.paused = true; _.autoPlayClear(); });
        _.on(list, 'mouseleave', function () { _.paused = false; _.autoPlay(); });
      }
    }
    if (o.accessibility === true) {
      _.on(list, 'keydown', function (e) {
        if (e.keyCode === 37) _.changeSlide('previous');
        else if (e.keyCode === 39) _.changeSlide('next');
      });
    }
    if (o.focusOnSelect === true) {
      // Slick bound this on every track child (clones included); a delegated
      // listener on the track sees the same clicks.
      _.on(_.$slideTrack, 'click', function (e) {
        var n = e.target;
        while (n && n.parentNode !== _.$slideTrack) n = n.parentNode;
        if (n) _.selectHandler(e);
      });
    }
    _.on(_.$slideTrack, 'dragstart', function (e) {
      if (e.target.getAttribute && e.target.getAttribute('draggable') !== 'true') e.preventDefault();
    });

    _.on(window, 'orientationchange', function () {
      _.checkResponsive();
      if (!_.unslicked) _.setPosition();
    });
    _.on(window, 'resize', function () {
      // Only width changes matter (mobile browsers fire resize on scroll).
      if (document.documentElement.clientWidth !== _.windowWidth) {
        clearTimeout(_.windowDelay);
        _.windowDelay = setTimeout(function () {
          _.windowWidth = document.documentElement.clientWidth;
          _.checkResponsive();
          if (!_.unslicked) _.setPosition();
        }, 50);
      }
    });
    // Images finishing late change slide heights; Slick re-measured on load.
    if (document.readyState !== 'complete') _.on(window, 'load', function () { _.setPosition(); });
  };

  proto.bindArrow = function (el, message) {
    var _ = this;
    _.on(el, 'click', function (e) { _.changeSlide(message, undefined, e.target, false, e); });
    if (_.keyArrows.indexOf(el) !== -1) {
      _.on(el, 'keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
      });
    }
  };

  /* ---- geometry ---- */

  proto.setPosition = function () {
    var _ = this;
    _.setDimensions();
    _.setHeight();
    if (_.options.fade === false) _.setCSS(_.getLeft(_.currentSlide));
    else _.setFade();
    UI.emit(_.$slider, 'setPosition', { slick: _ });
  };

  proto.setDimensions = function () {
    var _ = this, o = _.options, list = _.$list, first = _.$slides[0], trackSlides, offset;
    if (o.vertical === false) {
      if (o.centerMode === true) list.style.padding = '0px ' + o.centerPadding;
    } else {
      if (first) setSize(list, 'height', size(first, 'height', 'margin') * o.slidesToShow);
      if (o.centerMode === true) list.style.padding = o.centerPadding + ' 0px';
    }
    _.listWidth = size(list, 'width', 'content');
    _.listHeight = size(list, 'height', 'content');
    if (!first) return;

    trackSlides = slice(_.$slideTrack.children).filter(function (c) { return c.classList.contains('slick-slide'); });
    if (o.vertical === false) {
      _.slideWidth = Math.ceil(_.listWidth / o.slidesToShow);
      setSize(_.$slideTrack, 'width', Math.ceil(_.slideWidth * trackSlides.length));
    } else {
      _.slideWidth = Math.ceil(_.listWidth);
      setSize(_.$slideTrack, 'height', Math.ceil(size(first, 'height', 'margin') * trackSlides.length));
    }
    // Slides get the slide width minus their own padding, border and margin.
    offset = size(first, 'width', 'margin') - size(first, 'width', 'content');
    trackSlides.forEach(function (s) { setSize(s, 'width', _.slideWidth - offset); });
  };

  proto.setHeight = function () {
    var _ = this, o = _.options, s = _.$slides[_.currentSlide];
    if (o.slidesToShow === 1 && o.adaptiveHeight === true && o.vertical === false && s) {
      _.$list.style.height = size(s, 'height', 'margin') + 'px';
    }
  };

  // Where the track has to sit so that slideIndex is the first (or centred)
  // slide, counting the clones in front of the originals. Straight from 1.4.1.
  proto.getLeft = function (slideIndex) {
    var _ = this, o = _.options, verticalHeight = 0, verticalOffset = 0;
    _.slideOffset = 0;
    if (o.vertical === true && _.$slides[0]) verticalHeight = size(_.$slides[0], 'height', 'border');

    if (o.infinite === true) {
      if (_.slideCount > o.slidesToShow) {
        _.slideOffset = -1 * _.slideWidth * o.slidesToShow;
        verticalOffset = -1 * verticalHeight * o.slidesToShow;
      }
      if (_.slideCount % o.slidesToScroll !== 0 && slideIndex + o.slidesToScroll > _.slideCount &&
          _.slideCount > o.slidesToShow) {
        if (slideIndex > _.slideCount) {
          _.slideOffset = -1 * (o.slidesToShow - (slideIndex - _.slideCount)) * _.slideWidth;
          verticalOffset = -1 * (o.slidesToShow - (slideIndex - _.slideCount)) * verticalHeight;
        } else {
          _.slideOffset = -1 * _.slideCount % o.slidesToScroll * _.slideWidth;
          verticalOffset = -1 * _.slideCount % o.slidesToScroll * verticalHeight;
        }
      }
    } else if (slideIndex + o.slidesToShow > _.slideCount) {
      _.slideOffset = (slideIndex + o.slidesToShow - _.slideCount) * _.slideWidth;
      verticalOffset = (slideIndex + o.slidesToShow - _.slideCount) * verticalHeight;
    }
    if (_.slideCount <= o.slidesToShow) {
      _.slideOffset = 0;
      verticalOffset = 0;
    }
    if (o.centerMode === true && o.infinite === true) {
      _.slideOffset += _.slideWidth * Math.floor(o.slidesToShow / 2) - _.slideWidth;
    } else if (o.centerMode === true) {
      _.slideOffset = _.slideWidth * Math.floor(o.slidesToShow / 2);
    }
    return o.vertical === false
      ? slideIndex * _.slideWidth * -1 + _.slideOffset
      : slideIndex * verticalHeight * -1 + verticalOffset;
  };

  proto.setCSS = function (position) {
    var _ = this, x = '0px', y = '0px';
    if (_.positionProp === 'left') x = Math.ceil(position) + 'px';
    else y = Math.ceil(position) + 'px';
    _.$slideTrack.style.transform = 'translate3d(' + x + ', ' + y + ', 0px)';
  };

  proto.setFade = function () {
    var _ = this;
    _.$slides.forEach(function (s, i) {
      s.style.position = 'relative';
      s.style.left = (-1 * _.slideWidth * i || 0) + 'px';
      s.style.top = '0px';
      s.style.zIndex = 800;
      s.style.opacity = 0;
    });
    eq(_.$slides, _.currentSlide).forEach(function (s) { s.style.zIndex = 900; s.style.opacity = 1; });
  };

  proto.applyTransition = function (slide) {
    var _ = this, o = _.options;
    if (o.fade === false) _.$slideTrack.style.transition = 'transform ' + o.speed + 'ms ' + o.cssEase;
    else eq(_.$slides, slide).forEach(function (s) { s.style.transition = 'opacity ' + o.speed + 'ms ' + o.cssEase; });
  };

  proto.disableTransition = function (slide) {
    var _ = this;
    if (_.options.fade === false) _.$slideTrack.style.transition = '';
    else eq(_.$slides, slide).forEach(function (s) { s.style.transition = ''; });
  };

  proto.animateSlide = function (targetLeft, callback) {
    var _ = this, o = _.options;
    _.setHeight();
    _.applyTransition();
    targetLeft = Math.ceil(targetLeft);
    _.$slideTrack.style.transform = o.vertical === false
      ? 'translate3d(' + targetLeft + 'px, 0px, 0px)'
      : 'translate3d(0px, ' + targetLeft + 'px, 0px)';
    if (callback) {
      setTimeout(function () { _.disableTransition(); callback.call(); }, o.speed);
    }
  };

  proto.fadeSlide = function (slideIndex, callback) {
    var _ = this;
    _.applyTransition(slideIndex);
    eq(_.$slides, slideIndex).forEach(function (s) { s.style.opacity = 1; s.style.zIndex = 1000; });
    if (callback) {
      setTimeout(function () { _.disableTransition(slideIndex); callback.call(); }, _.options.speed);
    }
  };

  /* ---- state ---- */

  proto.setSlideClasses = function (index) {
    var _ = this, o = _.options, all, centerOffset, indexOffset, remainder;
    all = slice(_.$slider.querySelectorAll('.slick-slide'));
    all.forEach(function (s) { s.classList.remove('slick-active', 'slick-center'); });

    if (o.centerMode === true) {
      centerOffset = Math.floor(o.slidesToShow / 2);
      if (o.infinite === true) {
        if (index >= centerOffset && index <= _.slideCount - 1 - centerOffset) {
          addClass(_.$slides.slice(index - centerOffset, index + centerOffset + 1), 'slick-active');
        } else {
          indexOffset = o.slidesToShow + index;
          addClass(all.slice(indexOffset - centerOffset + 1, indexOffset + centerOffset + 2), 'slick-active');
        }
        // The clone standing in for the centred slide while the track wraps.
        if (index === 0) addClass(eq(all, all.length - 1 - o.slidesToShow), 'slick-center');
        else if (index === _.slideCount - 1) addClass(eq(all, o.slidesToShow), 'slick-center');
      }
      addClass(eq(_.$slides, index), 'slick-center');
    } else if (index >= 0 && index <= _.slideCount - o.slidesToShow) {
      addClass(_.$slides.slice(index, index + o.slidesToShow), 'slick-active');
    } else if (all.length <= o.slidesToShow) {
      addClass(all, 'slick-active');
    } else {
      remainder = _.slideCount % o.slidesToShow;
      indexOffset = o.infinite === true ? o.slidesToShow + index : index;
      // Loose ==, as in 1.4.1: data-slick options can arrive as strings.
      if (o.slidesToShow == o.slidesToScroll && _.slideCount - index < o.slidesToShow) {
        addClass(all.slice(indexOffset - (o.slidesToShow - remainder), indexOffset + remainder), 'slick-active');
      } else {
        addClass(all.slice(indexOffset, indexOffset + o.slidesToShow), 'slick-active');
      }
    }
  };

  proto.updateDots = function () {
    var _ = this, items;
    if (_.$dots === null) return;
    items = _.dotItems();
    items.forEach(function (li) { li.classList.remove('slick-active'); });
    addClass(eq(items, Math.floor(_.currentSlide / _.options.slidesToScroll)), 'slick-active');
  };

  proto.updateArrows = function () {
    var _ = this, o = _.options, prev = _.$prevArrow, next = _.$nextArrow;
    function set(list, on) { list.forEach(function (el) { el.classList.toggle('slick-disabled', on); }); }
    if (o.arrows === true && o.infinite !== true && _.slideCount > o.slidesToShow) {
      set(prev, false);
      set(next, false);
      if (_.currentSlide === 0) {
        set(prev, true);
      } else if (_.currentSlide >= _.slideCount - o.slidesToShow && o.centerMode === false) {
        set(next, true);
      } else if (_.currentSlide >= _.slideCount - 1 && o.centerMode === true) {
        set(next, true);
      }
    }
  };

  proto.getDotCount = function () {
    var _ = this, o = _.options, breakPoint = 0, counter = 0, pagerQty = 0;
    if (o.infinite === true) {
      pagerQty = Math.ceil(_.slideCount / o.slidesToScroll);
    } else if (o.centerMode === true) {
      pagerQty = _.slideCount;
    } else {
      while (breakPoint < _.slideCount) {
        ++pagerQty;
        breakPoint = counter + o.slidesToShow;
        counter += o.slidesToScroll <= o.slidesToShow ? o.slidesToScroll : o.slidesToShow;
      }
    }
    return pagerQty - 1;
  };

  proto.getNavigableIndexes = function () {
    var _ = this, o = _.options, breakPoint = 0, counter = 0, indexes = [], max;
    if (o.infinite === false) {
      max = _.slideCount - o.slidesToShow + 1;
      if (o.centerMode === true) max = _.slideCount;
    } else {
      breakPoint = counter = -1 * _.slideCount;
      max = 2 * _.slideCount;
    }
    while (breakPoint < max) {
      indexes.push(breakPoint);
      breakPoint = counter + o.slidesToScroll;
      counter += o.slidesToScroll <= o.slidesToShow ? o.slidesToScroll : o.slidesToShow;
    }
    return indexes;
  };

  proto.checkNavigable = function (index) {
    var navigables = this.getNavigableIndexes(), prev = 0, i;
    if (index > navigables[navigables.length - 1]) return navigables[navigables.length - 1];
    for (i = 0; i < navigables.length; i++) {
      if (index < navigables[i]) return prev;
      prev = navigables[i];
    }
    return index;
  };

  /* ---- navigation ---- */

  proto.changeSlide = function (message, index, target, dontAnimate, event) {
    var _ = this, o = _.options, indexOffset, slideOffset;
    if (event && target && target.nodeName === 'A') event.preventDefault();
    indexOffset = _.slideCount % o.slidesToScroll !== 0 ? 0 : (_.slideCount - _.currentSlide) % o.slidesToScroll;

    if (message === 'previous') {
      slideOffset = indexOffset === 0 ? o.slidesToScroll : o.slidesToShow - indexOffset;
      if (_.slideCount > o.slidesToShow) _.slideHandler(_.currentSlide - slideOffset, false, dontAnimate);
    } else if (message === 'next') {
      slideOffset = indexOffset === 0 ? o.slidesToScroll : indexOffset;
      if (_.slideCount > o.slidesToShow) _.slideHandler(_.currentSlide + slideOffset, false, dontAnimate);
    } else if (message === 'index') {
      // A dot click: the dot is the clicked element's parent <li>.
      if (index !== 0 && !index) {
        index = (target && target.parentElement ? siblingIndex(target.parentElement) : -1) * o.slidesToScroll;
      }
      _.slideHandler(_.checkNavigable(index), false, dontAnimate);
    }
  };

  proto.asNavFor = function (index) {
    var nav = this.options.asNavFor, el, other;
    if (nav === null || nav === undefined || nav === false) return;
    el = elements(nav)[0];
    other = el && el.slick;
    // Slick 1.4.1 threw here when the other slider was missing; that is skipped.
    if (other && other !== this && other.slideHandler) other.slideHandler(index, true);
  };

  proto.slideHandler = function (index, sync, dontAnimate) {
    var _ = this, o = _.options, targetSlide, animSlide, targetLeft, slideLeft;
    sync = sync || false;
    if (UI.reducedMotion) dontAnimate = true;
    if (_.unslicked) return;
    if (_.animating === true && o.waitForAnimate === true) return;
    if (o.fade === true && _.currentSlide === index) return;
    if (_.slideCount <= o.slidesToShow) return;

    if (sync === false) _.asNavFor(index);

    targetSlide = index;
    targetLeft = _.getLeft(targetSlide);
    slideLeft = _.getLeft(_.currentSlide);
    _.currentLeft = _.swipeLeft === null ? slideLeft : _.swipeLeft;

    // Non-infinite sliders bounce back from the ends.
    if ((o.infinite === false && o.centerMode === false && (index < 0 || index > _.getDotCount() * o.slidesToScroll)) ||
        (o.infinite === false && o.centerMode === true && (index < 0 || index > _.slideCount - o.slidesToScroll))) {
      if (o.fade === false) {
        targetSlide = _.currentSlide;
        if (dontAnimate !== true) _.animateSlide(slideLeft, function () { _.postSlide(targetSlide); });
        else _.postSlide(targetSlide);
      }
      return;
    }

    if (o.autoplay === true) clearInterval(_.autoPlayTimer);

    // Infinite mode animates onto a clone, then postSlide() jumps to the original.
    if (targetSlide < 0) {
      animSlide = _.slideCount % o.slidesToScroll !== 0 ? _.slideCount - _.slideCount % o.slidesToScroll : _.slideCount + targetSlide;
    } else if (targetSlide >= _.slideCount) {
      animSlide = _.slideCount % o.slidesToScroll !== 0 ? 0 : targetSlide - _.slideCount;
    } else {
      animSlide = targetSlide;
    }

    _.animating = true;
    UI.emit(_.$slider, 'beforeChange', { slick: _, currentSlide: _.currentSlide, nextSlide: animSlide });
    _.currentSlide = animSlide;
    _.setSlideClasses(_.currentSlide);
    _.updateDots();
    _.updateArrows();

    if (o.fade === true) {
      if (dontAnimate !== true) _.fadeSlide(animSlide, function () { _.postSlide(animSlide); });
      else _.postSlide(animSlide);
      _.setHeight();
      return;
    }
    if (dontAnimate !== true) _.animateSlide(targetLeft, function () { _.postSlide(animSlide); });
    else _.postSlide(animSlide);
  };

  proto.postSlide = function (index) {
    var _ = this;
    if (_.unslicked) return;
    UI.emit(_.$slider, 'afterChange', { slick: _, currentSlide: index });
    _.animating = false;
    _.setPosition();
    _.swipeLeft = null;
    if (_.options.autoplay === true && _.paused === false) _.autoPlay();
  };

  proto.selectHandler = function (event) {
    var _ = this, o = _.options, slide, index;
    // Slick 1.4.1 looked from the target's parent up, so a click on the slide
    // box itself (not on its content) went to slide 0; closest() fixes that.
    slide = event.target.closest ? event.target.closest('.slick-slide') : null;
    index = parseInt(slide ? slide.getAttribute('data-slick-index') : '', 10);
    if (!index) index = 0;
    if (_.slideCount <= o.slidesToShow) {
      var all = slice(_.$slider.querySelectorAll('.slick-slide'));
      all.forEach(function (s) { s.classList.remove('slick-active'); });
      addClass(eq(_.$slides, index), 'slick-active');
      if (o.centerMode === true) {
        all.forEach(function (s) { s.classList.remove('slick-center'); });
        addClass(eq(_.$slides, index), 'slick-center');
      }
      _.asNavFor(index);
      return;
    }
    _.slideHandler(index);
  };

  /* ---- autoplay ---- */

  proto.autoPlay = function () {
    var _ = this;
    if (_.autoPlayTimer) clearInterval(_.autoPlayTimer);
    if (UI.reducedMotion || _.unslicked) return;
    if (_.slideCount > _.options.slidesToShow && _.paused !== true) {
      _.autoPlayTimer = setInterval(function () { _.autoPlayIterator(); }, _.options.autoplaySpeed);
    }
  };

  proto.autoPlayClear = function () {
    if (this.autoPlayTimer) clearInterval(this.autoPlayTimer);
  };

  proto.autoPlayIterator = function () {
    var _ = this, o = _.options;
    if (o.infinite === false) {
      if (_.direction === 1) {
        if (_.currentSlide + 1 === _.slideCount - 1) _.direction = 0;
        _.slideHandler(_.currentSlide + o.slidesToScroll);
      } else {
        if (_.currentSlide - 1 === 0) _.direction = 1;
        _.slideHandler(_.currentSlide - o.slidesToScroll);
      }
    } else {
      _.slideHandler(_.currentSlide + o.slidesToScroll);
    }
  };

  /* ---- swipe / drag (touch and mouse, as 1.4.1) ---- */

  proto.swipeHandler = function (e, action) {
    var _ = this, o = _.options, t = _.touchObject;
    if (o.swipe === false || (o.draggable === false && e.type.indexOf('mouse') !== -1)) return;
    t.fingerCount = e.touches !== undefined ? e.touches.length : 1;
    t.minSwipe = _.listWidth / o.touchThreshold;
    if (action === 'start') _.swipeStart(e);
    else if (action === 'move') _.swipeMove(e);
    else _.swipeEnd(e);
  };

  proto.swipeStart = function (e) {
    var _ = this, t = _.touchObject, touch = e.touches && e.touches[0];
    if (t.fingerCount !== 1 || _.slideCount <= _.options.slidesToShow) {
      _.touchObject = {};
      return;
    }
    t.startX = t.curX = touch ? touch.pageX : e.clientX;
    t.startY = t.curY = touch ? touch.pageY : e.clientY;
    _.dragging = true;
  };

  proto.swipeDirection = function () {
    var t = this.touchObject;
    var angle = Math.round(Math.atan2(t.startY - t.curY, t.startX - t.curX) * 180 / Math.PI);
    if (angle < 0) angle = 360 - Math.abs(angle);
    if ((angle <= 45 && angle >= 0) || (angle <= 360 && angle >= 315)) return 'left';
    if (angle >= 135 && angle <= 225) return 'right';
    return 'vertical';
  };

  // Like 1.4.1 the gesture is always horizontal, also for vertical sliders
  // (verticalSwiping arrived in 1.5): a vertical swipe keeps scrolling the page.
  proto.swipeMove = function (e) {
    var _ = this, o = _.options, t = _.touchObject, touches = e.touches, curLeft, direction, length, sign;
    if (!_.dragging || (touches && touches.length !== 1)) return;
    curLeft = _.getLeft(_.currentSlide);
    t.curX = touches ? touches[0].pageX : e.clientX;
    t.curY = touches ? touches[0].pageY : e.clientY;
    t.swipeLength = Math.round(Math.sqrt(Math.pow(t.curX - t.startX, 2)));
    direction = _.swipeDirection();
    if (direction === 'vertical') return;
    if (touches && t.swipeLength > 4 && e.cancelable) e.preventDefault();

    sign = t.curX > t.startX ? 1 : -1;
    length = t.swipeLength;
    t.edgeHit = false;
    if (o.infinite === false &&
        ((_.currentSlide === 0 && direction === 'right') || (_.currentSlide >= _.getDotCount() && direction === 'left'))) {
      length = t.swipeLength * o.edgeFriction;
      t.edgeHit = true;
    }
    _.swipeLeft = o.vertical === false
      ? curLeft + length * sign
      : curLeft + length * (size(_.$list, 'height', 'content') / _.listWidth) * sign;
    if (o.fade === true || o.touchMove === false) {
      if (e.cancelable) e.preventDefault();
      return;
    }
    if (_.animating === true) {
      _.swipeLeft = null;
      return;
    }
    _.setCSS(_.swipeLeft);
  };

  proto.swipeEnd = function () {
    var _ = this, t = _.touchObject, direction, target;
    _.dragging = false;
    _.shouldClick = !(t.swipeLength > 10);
    if (t.curX === undefined) return;
    if (t.edgeHit === true) UI.emit(_.$slider, 'edge', { slick: _, direction: _.swipeDirection() });
    if (t.swipeLength >= t.minSwipe) {
      direction = _.swipeDirection();
      if (direction === 'left' || direction === 'right') {
        target = _.currentSlide + (direction === 'left' ? 1 : -1) * _.options.slidesToScroll;
        _.slideHandler(target);
        _.currentDirection = direction === 'left' ? 0 : 1;
        _.touchObject = {};
        UI.emit(_.$slider, 'swipe', { slick: _, direction: direction });
      }
    } else if (t.startX !== t.curX) {
      _.slideHandler(_.currentSlide); // not far enough: settle back
      _.touchObject = {};
    }
  };

  /* ---- responsive / teardown ---- */

  proto.checkResponsive = function (initial) {
    var _ = this, orig = _.originalSettings, target = null, width, i, bp;
    var windowWidth = window.innerWidth; // Slick used innerWidth: scrollbar included
    if (!orig.responsive || !(orig.responsive.length > -1)) return;
    if (_.respondTo === 'slider') width = size(_.$slider, 'width', 'content');
    else if (_.respondTo === 'min') width = Math.min(windowWidth, size(_.$slider, 'width', 'content'));
    else width = windowWidth;

    // Desktop-first: the smallest breakpoint wider than the window wins.
    for (i = 0; i < _.breakpoints.length; i++) {
      bp = _.breakpoints[i];
      if (orig.mobileFirst === false ? width < bp : width > bp) target = bp;
    }
    if (target !== null) {
      if (_.activeBreakpoint === null || target !== _.activeBreakpoint) {
        _.activeBreakpoint = target;
        if (_.breakpointSettings[target] === 'unslick') {
          _.unslick();
        } else {
          _.options = UI.extend({}, orig, _.breakpointSettings[target]);
          if (initial === true) _.currentSlide = _.options.initialSlide;
          _.refresh();
        }
      }
    } else if (_.activeBreakpoint !== null) {
      _.activeBreakpoint = null;
      _.options = orig;
      if (initial === true) _.currentSlide = _.options.initialSlide;
      _.refresh();
    }
  };

  proto.refresh = function () {
    var _ = this, current = _.currentSlide;
    _.destroy();
    _.resetState();
    _.unslicked = false;
    _.init();
    _.changeSlide('index', current, null, true);
  };

  proto.destroy = function () {
    var _ = this;
    _.autoPlayClear();
    clearTimeout(_.windowDelay);
    _.touchObject = {};
    _.listeners.forEach(function (l) { l[0].removeEventListener(l[1], l[2], l[3]); });
    _.listeners = [];
    slice(_.$slider.querySelectorAll('.slick-cloned')).forEach(removeNode);
    (_.$dots || []).forEach(removeNode);
    // Only arrows built from HTML strings; the theme's own elements stay.
    _.createdArrows.forEach(removeNode);
    _.$slides.forEach(function (s) {
      s.classList.remove('slick-slide', 'slick-active', 'slick-center', 'slick-visible');
      s.removeAttribute('data-slick-index');
      ['position', 'left', 'top', 'zIndex', 'opacity', 'width', 'transition'].forEach(function (p) { s.style[p] = ''; });
    });
    _.$slider.classList.remove('slick-slider', 'slick-initialized');
    _.$slider.removeAttribute('data-cl-slick');
    // $slider.html($slides): everything else Slick put in the slider goes.
    while (_.$slider.firstChild) _.$slider.removeChild(_.$slider.firstChild);
    _.$slides.forEach(function (s) { _.$slider.appendChild(s); });
  };

  // Like 1.4.1, an 'unslick' breakpoint is final: it does not re-slick when
  // the window grows again.
  proto.unslick = function () {
    this.destroy();
    this.unslicked = true;
  };

  /* ---- public methods (Slick's names and their old aliases) ---- */

  proto.slickNext = proto.next = function () { this.changeSlide('next'); };
  proto.slickPrev = proto.prev = function () { this.changeSlide('previous'); };
  proto.slickGoTo = proto.goTo = function (slide, dontAnimate) {
    this.changeSlide('index', parseInt(slide, 10), null, dontAnimate);
  };
  proto.slickCurrentSlide = proto.getCurrent = function () { return this.currentSlide; };
  proto.slickPause = proto.pause = function () { this.autoPlayClear(); this.paused = true; };
  proto.slickPlay = proto.play = function () { this.paused = false; this.autoPlay(); };
  proto.slickGetOption = proto.getOption = function (name) { return this.options[name]; };
  proto.slickSetOption = proto.setOption = function (name, value, refresh) {
    this.options[name] = value;
    if (refresh === true) this.refresh();
  };
  proto.getSlick = function () { return this; };

  /**
   * UI.slick(target, options) — $(target).slick(options) without jQuery.
   * Returns the instances (filled at DOM ready when called before the elements exist).
   * UI.slick(target, 'slickGoTo', 2) calls a method on each, like
   * $(target).slick('slickGoTo', 2), and returns the first value a method
   * returned (slickCurrentSlide, getSlick, ...), else the instances.
   */
  UI.slick = function (target, options) {
    var args = slice(arguments).slice(2), out = [], result;
    function run(el) {
      var inst = el.slick instanceof Slick ? el.slick : null;
      if (typeof options === 'string') {
        if (inst && typeof inst[options] === 'function') {
          var r = inst[options].apply(inst, args);
          if (result === undefined) result = r;
          out.push(inst);
        }
        return;
      }
      if (inst && el.hasAttribute('data-cl-slick')) { out.push(inst); return; }
      el.slick = new Slick(el, options);
      out.push(el.slick);
    }
    // Like $(...).slick(): elements already parsed are set up now, so theme
    // code on the next line can rely on the built DOM. Only a call made before
    // its elements exist (a script in <head>) waits for DOM ready.
    var els = elements(target);
    if (els.length || document.readyState !== 'loading') els.forEach(run);
    else UI.each(target, run);
    return result !== undefined ? result : out;
  };

  // Slick initialised [data-slick] elements on DOM ready.
  UI.ready(function () { UI.slick('[data-slick]'); });
}());

/* ColorlibUI module: magnific — replaces Magnific Popup 1.1.0 (and Simple Lightbox 1.x).
 *
 * The themes style the DOM Magnific builds (.mfp-bg, .mfp-wrap, .mfp-container,
 * .mfp-content, .mfp-figure, .mfp-close, .mfp-arrow, ...) through magnific-popup.css
 * and their own overrides (#test-form button.mfp-close, .mfp-bg colours), so this
 * rebuilds that DOM step for step: one shared overlay (bg + wrap + container) that is
 * reused between opens, content built from the same markup strings, the same
 * classes toggled at the same moments (mfp-ready 16 ms after insertion, mfp-removing
 * for removalDelay ms), the same inline styles (wrap top/height when
 * fixedContentPos is off, html overflow + scrollbar margin when it is on). Types
 * covered: image, iframe (YouTube / Vimeo / Google Maps URL rewriting identical to
 * Magnific's), inline, plus the gallery module. Ajax and zoom are not used by any
 * theme; a trigger with type 'ajax' simply follows its link.
 *
 * UI.simpleLightbox rebuilds Simple Lightbox's own .slb* DOM instead, because the one
 * theme using it (eiser) loads simpleLightbox.css, not magnific-popup.css.
 */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var READY = 'mfp-ready';
  var REMOVING = 'mfp-removing';
  var PREVENT_CLOSE = 'mfp-prevent-close';
  var docEl = document.documentElement;

  // Magnific's UA checks decide fixedContentPos:'auto' and the iOS height fix; the
  // themes were tuned against that behaviour, so it is kept as it was.
  var appVersion = navigator.appVersion;
  var isIOS = /iphone|ipad|ipod/i.test(appVersion);
  var probablyMobile = /android/i.test(appVersion) || isIOS ||
    /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent);

  var defaults = {
    disableOn: 0, key: null, midClick: false, mainClass: '', preloader: true, focus: '',
    closeOnContentClick: false, closeOnBgClick: true, closeBtnInside: true, showCloseBtn: true,
    enableEscapeKey: true, modal: false, alignTop: false, removalDelay: 0, prependTo: null,
    fixedContentPos: 'auto', fixedBgPos: 'auto', overflowY: 'auto',
    closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
    tClose: 'Close (Esc)', tLoading: 'Loading...', autoFocusLast: true,
    inline: { hiddenClass: 'hide', markup: '', tNotFound: 'Content not found' },
    image: {
      markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div>' +
        '<figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div>' +
        '</div></figcaption></figure></div>',
      cursor: 'mfp-zoom-out-cur', titleSrc: 'title', verticalFit: true,
      tError: '<a href="%url%">The image</a> could not be loaded.'
    },
    iframe: {
      // allow="autoplay" is the only addition: without it Chrome ignores the
      // ?autoplay=1 Magnific appends, in a cross-origin iframe.
      markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" ' +
        'src="//about:blank" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>',
      srcAction: 'iframe_src',
      patterns: {
        youtube: { index: 'youtube.com', id: 'v=', src: '//www.youtube.com/embed/%id%?autoplay=1' },
        vimeo: { index: 'vimeo.com/', id: '/', src: '//player.vimeo.com/video/%id%?autoplay=1' },
        gmaps: { index: '//maps.google.', src: '%id%&output=embed' }
      }
    },
    gallery: {
      enabled: false,
      arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
      preload: [0, 2], navigateByImgClick: true, arrows: true,
      tPrev: 'Previous (Left arrow key)', tNext: 'Next (Right arrow key)', tCounter: '%curr% of %total%'
    }
  };

  /* Deep merge of plain objects, like $.extend(true, ...): nested option groups
   * (gallery, image, iframe.patterns, callbacks) merge, elements stay references. */
  function isPlain(o) { return Object.prototype.toString.call(o) === '[object Object]' && !o.nodeType; }
  function merge(target, src) {
    for (var k in src) {
      if (!Object.prototype.hasOwnProperty.call(src, k) || src[k] === undefined) continue;
      var v = src[k];
      if (isPlain(v)) target[k] = merge(isPlain(target[k]) ? target[k] : {}, v);
      else if (Array.isArray(v)) target[k] = v.slice();
      else target[k] = v;
    }
    return target;
  }

  function div(name, parent, html) {
    var el = document.createElement('div');
    el.className = 'mfp-' + name;
    if (html) el.innerHTML = html;
    if (parent) parent.appendChild(el);
    return el;
  }
  function classes(el, str, on) {
    String(str).split(/\s+/).forEach(function (c) { if (c) el.classList[on ? 'add' : 'remove'](c); });
  }
  function detach(el) { if (el && el.parentNode) el.parentNode.removeChild(el); }
  function query(root, sel) { try { return root.querySelector(sel); } catch (e) { return null; } }
  function lcfirst(s) { return s.charAt(0).toLowerCase() + s.slice(1); }

  /* ------------------------------------------------------------------ *
   * The popup. Magnific keeps one instance for the page; so does this, and it is
   * what `this` is inside callbacks (this.st.focus, this.st.mainClass, this.close()).
   * ------------------------------------------------------------------ */

  var mfp = { isOpen: false, items: [], index: 0 };
  var bg, wrap, container;       // created on first open, reused afterwards
  var preloaderEl;               // like Magnific, never reset once created
  var lastType, lastStatus, lastCloseType, wrapClasses;
  var sizeTimer, preloadTimer, closeTimer;
  var inlinePlaceholder, inlineHidden, inlineEl;
  var scrollbarSize;

  function fire(name, args) {
    args = args === undefined ? [] : (Array.isArray(args) ? args : [args]);
    var cbs = mfp.st && mfp.st.callbacks;
    if (cbs && typeof cbs[lcfirst(name)] === 'function') cbs[lcfirst(name)].apply(mfp, args);
    UI.emit(mfp.ev || document, 'mfp' + name, { instance: mfp, args: args });
  }

  function toMFP(cls, on) { classes(bg, cls, on); classes(wrap, cls, on); }

  function docHeight() {
    var b = document.body;
    return Math.max(b.scrollHeight, docEl.scrollHeight, b.offsetHeight, docEl.offsetHeight, docEl.clientHeight);
  }

  function getScrollbarSize() {
    if (scrollbarSize === undefined) {
      var d = document.createElement('div');
      d.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
      document.body.appendChild(d);
      scrollbarSize = d.offsetWidth - d.clientWidth;
      document.body.removeChild(d);
    }
    return scrollbarSize;
  }

  function closeBtn(type) {
    var tpl = mfp.currTemplate;
    if (type !== lastCloseType || !tpl.closeBtn) {
      tpl.closeBtn = UI.fromHTML(mfp.st.closeMarkup.replace('%title%', mfp.st.tClose));
      if (tpl.closeBtn.setAttribute) tpl.closeBtn.setAttribute('aria-label', mfp.st.tClose);
      lastCloseType = type;
    }
    return tpl.closeBtn;
  }

  function open(c) {
    // A new popup during the previous one's removalDelay: finish that close first,
    // or its timer would tear down the popup being opened.
    if (closeTimer) { clearTimeout(closeTimer); finishClose(); }

    if (c.isObj === false) {
      mfp.items = c.items.slice();
      mfp.index = Math.max(0, mfp.items.indexOf(c.el));
    } else {
      mfp.items = Array.isArray(c.items) ? c.items.slice() : [c.items];
      mfp.index = c.index || 0;
    }
    if (mfp.isOpen) { updateItemHTML(); return; }

    wrapClasses = '';
    mfp.ev = c.mainEl || document;
    mfp.popupsCache = mfp.popupsCache || {};
    mfp.currTemplate = c.key ? (mfp.popupsCache[c.key] = mfp.popupsCache[c.key] || {}) : {};
    var st = mfp.st = merge(merge({}, defaults), c);
    mfp.fixedContentPos = st.fixedContentPos === 'auto' ? !probablyMobile : st.fixedContentPos;
    if (st.modal) {
      st.closeOnContentClick = st.closeOnBgClick = st.showCloseBtn = st.enableEscapeKey = false;
    }

    if (!bg) {
      bg = div('bg');
      bg.addEventListener('click', close);
      wrap = div('wrap');
      wrap.setAttribute('tabindex', '-1');
      wrap.addEventListener('click', onWrapClick);
      container = div('container', wrap);
    }
    mfp.bgOverlay = bg; mfp.wrap = wrap; mfp.container = container;
    mfp.contentContainer = div('content');
    if (st.preloader) {
      preloaderEl = div('preloader', container, st.tLoading);
      // Links in an error message ("The image could not be loaded") stay clickable.
      preloaderEl.addEventListener('click', function (e) {
        if (e.target.closest && e.target.closest('a')) e.stopPropagation();
      });
    }
    mfp.preloader = preloaderEl;
    if (st.gallery.enabled) wrapClasses += ' mfp-gallery';
    mfp.direction = true;

    fire('BeforeOpen');
    st = mfp.st;

    if (st.showCloseBtn) {
      if (st.closeBtnInside) wrapClasses += ' mfp-close-btn-in';
      else wrap.appendChild(closeBtn());
    }
    if (st.alignTop) wrapClasses += ' mfp-align-top';
    if (mfp.fixedContentPos) {
      wrap.style.overflow = st.overflowY;
      wrap.style.overflowX = 'hidden';
      wrap.style.overflowY = st.overflowY;
    } else {
      // Not fixed: the wrap is absolutely placed at the current scroll offset.
      wrap.style.top = window.pageYOffset + 'px';
      wrap.style.position = 'absolute';
    }
    if (st.fixedBgPos === false || (st.fixedBgPos === 'auto' && !mfp.fixedContentPos)) {
      bg.style.height = docHeight() + 'px';
      bg.style.position = 'absolute';
    }
    if (st.enableEscapeKey) document.addEventListener('keyup', onKeyUp);
    window.addEventListener('resize', onResize);
    if (!st.closeOnContentClick) wrapClasses += ' mfp-auto-cursor';
    classes(wrap, wrapClasses, true);

    var wH = mfp.wH = docEl.clientHeight;
    var htmlCss = {};
    if (mfp.fixedContentPos && document.body.scrollHeight > wH) {
      // Hiding the page scrollbar would shift the page sideways; pad it back.
      var sb = getScrollbarSize();
      if (sb) htmlCss.marginRight = sb + 'px';
    }
    if (mfp.fixedContentPos) htmlCss.overflow = 'hidden';
    if (st.mainClass) toMFP(st.mainClass, true);

    updateItemHTML();
    buildControls();
    fire('BuildControls');
    for (var k in htmlCss) docEl.style[k] = htmlCss[k];

    var parent = (st.prependTo && UI.toElements(st.prependTo)[0]) || document.body;
    parent.insertBefore(wrap, parent.firstChild);
    parent.insertBefore(bg, wrap);

    var active = document.activeElement;
    // Safari does not focus links on click; the trigger is where focus belongs.
    mfp._lastFocusedEl = (active && active !== document.body) ? active : (st.el && st.el.focus ? st.el : active);

    // Added a frame later so CSS transitions from the initial state (mfp-fade) run.
    setTimeout(function () {
      if (!mfp.isOpen) return;
      if (mfp.content) { toMFP(READY, true); setFocus(); } else bg.classList.add(READY);
      document.addEventListener('focusin', onFocusIn);
    }, 16);

    mfp.isOpen = true;
    updateSize(wH);
    if (mfp.currItem.type === 'image' && st.image.cursor) document.body.classList.add(st.image.cursor);
    if (st.gallery.enabled) document.addEventListener('keydown', onGalleryKey);
    fire('Open');
  }

  function close() {
    if (!mfp.isOpen) return;
    fire('BeforeClose');
    mfp.isOpen = false;
    if (mfp.st.removalDelay && !UI.reducedMotion) {
      // Kept on screen with mfp-removing so the themes' fade-out CSS can play.
      toMFP(REMOVING, true);
      closeTimer = setTimeout(finishClose, mfp.st.removalDelay);
    } else {
      finishClose();
    }
  }

  function finishClose() {
    closeTimer = null;
    var st = mfp.st;
    // Module close handlers first (as Magnific's event handlers ran before callbacks).
    restoreInline();
    if (st.image.cursor) document.body.classList.remove(st.image.cursor);
    window.removeEventListener('resize', onResize);
    var frame = mfp.currTemplate && mfp.currTemplate.iframe;
    if (frame && frame.querySelector) {
      var ifr = frame.querySelector('iframe');
      if (ifr) ifr.src = '//about:blank';   // stops the video
    }
    document.removeEventListener('keydown', onGalleryKey);
    mfp.arrowLeft = mfp.arrowRight = null;
    fire('Close');

    detach(bg);
    detach(wrap);
    while (container.firstChild) container.removeChild(container.firstChild);
    toMFP(REMOVING + ' ' + READY + ' ' + (st.mainClass || ''), false);
    if (mfp.fixedContentPos) { docEl.style.marginRight = ''; docEl.style.overflow = ''; }
    document.removeEventListener('keyup', onKeyUp);
    document.removeEventListener('focusin', onFocusIn);
    wrap.className = 'mfp-wrap';
    wrap.removeAttribute('style');
    bg.className = 'mfp-bg';
    container.className = 'mfp-container';
    if (st.showCloseBtn && (!st.closeBtnInside || mfp.currTemplate[mfp.currItem.type] === true)) {
      detach(mfp.currTemplate.closeBtn);
    }
    if (st.autoFocusLast && mfp._lastFocusedEl && mfp._lastFocusedEl.focus) mfp._lastFocusedEl.focus();
    mfp.currItem = mfp.content = mfp.currTemplate = null;
    mfp.prevHeight = 0;
    fire('AfterClose');
  }

  function updateSize(winHeight) {
    if (isIOS) {
      // iOS reports the layout viewport; scale to what is actually visible.
      var h = window.innerHeight * (docEl.clientWidth / window.innerWidth);
      wrap.style.height = h + 'px';
      mfp.wH = h;
    } else {
      mfp.wH = winHeight || docEl.clientHeight;
    }
    if (!mfp.fixedContentPos) wrap.style.height = mfp.wH + 'px';
    resizeImage();
    fire('Resize');
  }

  function onResize() { updateSize(); }
  function onKeyUp(e) { if (e.keyCode === 27) close(); }
  function onGalleryKey(e) {
    if (e.keyCode === 37) prev();
    else if (e.keyCode === 39) next();
  }
  function onFocusIn(e) {
    // Keep focus inside the popup while it is open.
    if (e.target !== wrap && !wrap.contains(e.target)) setFocus();
  }
  function setFocus() {
    var el = mfp.st.focus ? (mfp.content && mfp.content.querySelector ? query(mfp.content, mfp.st.focus) : null) : wrap;
    if (el && el.focus) el.focus();
  }

  function onWrapClick(e) {
    var t = e.target;
    var g = mfp.st && mfp.st.gallery;
    if (g && g.enabled && g.navigateByImgClick && t.classList && t.classList.contains('mfp-img') &&
        mfp.items.length > 1) {
      next();
      e.preventDefault();
      e.stopPropagation();
      return;
    }
    if (checkIfClose(t)) close();
  }

  function checkIfClose(t) {
    if (t.classList && t.classList.contains(PREVENT_CLOSE)) return false;
    var cc = mfp.st.closeOnContentClick, cb = mfp.st.closeOnBgClick;
    if (cc && cb) return true;
    if (!mfp.content || (t.classList && t.classList.contains('mfp-close')) ||
        (mfp.preloader && t === mfp.preloader)) return true;
    if (t === mfp.content || mfp.content.contains(t)) return !!cc;
    return !!(cb && document.contains(t));
  }

  function updateItemHTML() {
    var item = mfp.items[mfp.index];
    detach(mfp.contentContainer);
    if (mfp.content) detach(mfp.content);
    if (!item.parsed) item = parseEl(mfp.index);
    var type = item.type;
    var prevType = mfp.currItem ? mfp.currItem.type : '';
    if (prevType === 'iframe' && type !== 'iframe') blankIframe();
    fire('BeforeChange', [prevType, type]);
    mfp.currItem = item;
    if (!mfp.currTemplate[type]) {
      var markup = mfp.st[type] ? mfp.st[type].markup : false;
      fire('FirstMarkupParse', markup);
      mfp.currTemplate[type] = markup ? UI.fromHTML(markup) : true;
    }
    if (lastType && lastType !== type) container.classList.remove('mfp-' + lastType + '-holder');
    appendContent(getters[type](item, mfp.currTemplate[type]), type);
    item.preloaded = true;
    fire('Change', item);
    if (mfp.st.gallery.enabled) {
      clearTimeout(preloadTimer);
      preloadTimer = setTimeout(preloadNearbyImages, 16);
    }
    lastType = type;
    container.insertBefore(mfp.contentContainer, container.firstChild);
    fire('AfterChange');
  }

  function blankIframe() {
    var t = mfp.currTemplate && mfp.currTemplate.iframe;
    var f = t && t.querySelector && t.querySelector('iframe');
    if (f) f.src = '//about:blank';
  }

  function appendContent(content, type) {
    mfp.content = content;
    if (content) {
      // Inline content has no template of its own: the close button goes inside it.
      if (mfp.st.showCloseBtn && mfp.st.closeBtnInside && mfp.currTemplate[type] === true &&
          !content.querySelector('.mfp-close')) {
        content.appendChild(closeBtn());
      }
    } else {
      mfp.content = '';
    }
    fire('BeforeAppend');
    container.classList.add('mfp-' + type + '-holder');
    if (mfp.content) mfp.contentContainer.appendChild(mfp.content);
  }

  function parseEl(index) {
    var raw = mfp.items[index], item, type;
    if (raw.tagName) {
      item = { el: raw };
      var types = ['inline', 'image', 'iframe'];
      for (var i = 0; i < types.length; i++) {
        if (raw.classList.contains('mfp-' + types[i])) { type = types[i]; break; }
      }
      item.src = raw.getAttribute('data-mfp-src') || raw.getAttribute('href') || undefined;
    } else {
      type = raw.type;
      item = { data: raw, src: raw.src };
    }
    item.type = type || mfp.st.type || 'inline';
    item.index = index;
    item.parsed = true;
    mfp.items[index] = item;
    fire('ElementParse', item);
    return mfp.items[index];
  }

  function updateStatus(status, text) {
    if (!mfp.preloader) return;
    if (lastStatus !== status) container.classList.remove('mfp-s-' + lastStatus);
    if (!text && status === 'loading') text = mfp.st.tLoading;
    var data = { status: status, text: text };
    if (mfp.st.gallery.enabled && data.text) data.text = counterText(data.text, mfp.currItem.index);
    fire('UpdateStatus', data);
    if (data.text !== undefined) mfp.preloader.innerHTML = data.text;
    container.classList.add('mfp-s-' + data.status);
    lastStatus = data.status;
  }

  /* Fills a template: "key" sets the .mfp-key element's HTML; "key_attr" sets an
   * attribute; "key_replaceWith" swaps the element for a node (Magnific's scheme). */
  function parseMarkup(template, values, item) {
    if (item.data) values = UI.extend(item.data, values);
    if (mfp.st.gallery.enabled) {
      values.counter = mfp.items.length > 1 ? counterText(mfp.st.gallery.tCounter, item.index) : '';
    }
    if (mfp.st.showCloseBtn && mfp.st.closeBtnInside) values.close_replaceWith = closeBtn(item.type);
    fire('MarkupParse', [template, values, item]);
    Object.keys(values).forEach(function (key) {
      var val = values[key];
      if (val === undefined || val === false) return;
      var parts = key.split('_');
      var el = query(template, '.mfp-' + parts[0]);
      if (!el) return;
      if (parts.length > 1) {
        if (parts[1] === 'replaceWith') {
          if (typeof val === 'string') val = UI.fromHTML(val);
          if (el !== val) el.parentNode.replaceChild(val, el);
        } else if (parts[1] === 'img') {
          if (el.tagName === 'IMG') el.src = val;
          else {
            var img = document.createElement('img');
            img.src = val;
            img.className = el.className;
            el.parentNode.replaceChild(img, el);
          }
        } else {
          el.setAttribute(parts[1], val);
        }
      } else if (val.nodeType) {
        el.innerHTML = '';
        el.appendChild(val);
      } else {
        el.innerHTML = val;
      }
    });
  }

  function counterText(text, index) {
    return String(text).replace(/%curr%/gi, index + 1).replace(/%total%/gi, mfp.items.length);
  }

  /* ---------------- content types ---------------- */

  function restoreInline() {
    if (inlineEl) {
      inlineEl.classList.add(inlineHidden);
      var parent = inlinePlaceholder.parentNode;
      if (parent) parent.insertBefore(inlineEl, inlinePlaceholder.nextSibling);
      detach(inlinePlaceholder);
      inlineEl = null;
    }
  }

  function findInline(src) {
    if (src.nodeType) return src;
    src = String(src).trim();
    if (src.charAt(0) === '<') return UI.fromHTML(src);
    var el = query(document, src);
    // "https://site/#test-form" is not a selector (jQuery threw on it); its hash is.
    if (!el && src.indexOf('#') > 0) el = query(document, src.slice(src.indexOf('#')));
    return el;
  }

  function resizeImage() {
    var item = mfp.currItem;
    if (item && item.img && mfp.st.image.verticalFit) item.img.style.maxHeight = mfp.wH + 'px';
  }

  function onImageHasSize(item) {
    if (!item.img) return;
    item.hasSize = true;
    clearInterval(sizeTimer);
    item.isCheckingImgSize = false;
    fire('ImageHasSize', item);
    if (item.imgHidden) {
      if (mfp.content) mfp.content.classList.remove('mfp-loading');
      item.imgHidden = false;
    }
  }

  /* Shows the figure as soon as the image's dimensions are known, before it has
   * finished downloading (Magnific's polling schedule). */
  function findImageSize(item) {
    var counter = 0, img = item.img;
    function poll(interval) {
      clearInterval(sizeTimer);
      sizeTimer = setInterval(function () {
        if (img.naturalWidth > 0) { onImageHasSize(item); return; }
        if (counter > 200) clearInterval(sizeTimer);
        counter++;
        if (counter === 3) poll(10);
        else if (counter === 40) poll(50);
        else if (counter === 100) poll(500);
      }, interval);
    }
    poll(1);
  }

  var getters = {
    inline: function (item, template) {
      restoreInline();
      if (item.src) {
        var ist = mfp.st.inline;
        var el = findInline(item.src);
        if (el) {
          if (el.parentNode && el.parentNode.tagName) {
            // A placeholder keeps the element's place in the page for when it goes back.
            if (!inlinePlaceholder) {
              inlinePlaceholder = div(ist.hiddenClass);
              inlineHidden = 'mfp-' + ist.hiddenClass;
            }
            el.parentNode.insertBefore(inlinePlaceholder, el.nextSibling);
            detach(el);
            el.classList.remove(inlineHidden);
            inlineEl = el;
          }
          updateStatus('ready');
        } else {
          updateStatus('error', ist.tNotFound);
          el = document.createElement('div');
        }
        item.inlineElement = el;
        return el;
      }
      updateStatus('ready');
      if (template !== true) parseMarkup(template, {}, item);
      return template;
    },

    image: function (item, template) {
      var ist = mfp.st.image;
      var tries = 0;
      function onLoad() {
        if (!item) return;
        if (img.complete) {
          unbind();
          if (item === mfp.currItem) { onImageHasSize(item); updateStatus('ready'); }
          item.hasSize = item.loaded = true;
          fire('ImageLoadComplete');
        } else if (++tries < 200) {
          setTimeout(onLoad, 100);
        } else {
          onError();
        }
      }
      function onError() {
        if (!item) return;
        unbind();
        if (item === mfp.currItem) {
          onImageHasSize(item);
          updateStatus('error', ist.tError.replace('%url%', item.src));
        }
        item.hasSize = item.loaded = item.loadError = true;
      }
      function unbind() {
        img.removeEventListener('load', onLoad);
        img.removeEventListener('error', onError);
      }

      var img;
      if (template.querySelector('.mfp-img')) {
        img = document.createElement('img');
        img.className = 'mfp-img';
        var inner = item.el && item.el.querySelector('img');
        if (inner) img.alt = inner.getAttribute('alt') || '';
        item.img = img;
        img.addEventListener('load', onLoad);
        img.addEventListener('error', onError);
        img.src = item.src;
        if (img.naturalWidth > 0) item.hasSize = true;
        else if (!img.width) item.hasSize = false;
      }

      var title = '';
      if (item.data && item.data.title !== undefined) title = item.data.title;
      else if (typeof ist.titleSrc === 'function') title = ist.titleSrc.call(mfp, item);
      else if (ist.titleSrc && item.el) title = item.el.getAttribute(ist.titleSrc) || '';
      parseMarkup(template, { title: title, img_replaceWith: item.img }, item);
      resizeImage();

      if (item.hasSize) {
        clearInterval(sizeTimer);
        if (item.loadError) {
          template.classList.add('mfp-loading');
          updateStatus('error', ist.tError.replace('%url%', item.src));
        } else {
          template.classList.remove('mfp-loading');
          updateStatus('ready');
        }
        return template;
      }
      updateStatus('loading');
      item.loading = true;
      if (!item.hasSize) {
        item.imgHidden = true;
        template.classList.add('mfp-loading');
        findImageSize(item);
      }
      return template;
    },

    iframe: function (item, template) {
      var src = item.src || '';
      var ist = mfp.st.iframe;
      var patterns = ist.patterns || {};
      // First pattern whose index occurs in the URL wins, e.g.
      // youtube.com/watch?v=ID -> //www.youtube.com/embed/ID?autoplay=1
      for (var k in patterns) {
        var p = patterns[k];
        if (!p || src.indexOf(p.index) === -1) continue;
        if (p.id) {
          src = typeof p.id === 'string'
            ? src.substr(src.lastIndexOf(p.id) + p.id.length, src.length)
            : p.id.call(p, src);
        }
        src = p.src.replace('%id%', src);
        break;
      }
      var values = {};
      if (ist.srcAction) values[ist.srcAction] = src;
      parseMarkup(template, values, item);
      updateStatus('ready');
      return template;
    }
  };

  /* ---------------- gallery ---------------- */

  function wrapIndex(i) {
    var n = mfp.items.length;
    return i > n - 1 ? i - n : (i < 0 ? n + i : i);
  }
  function next() { mfp.direction = true; mfp.index = wrapIndex(mfp.index + 1); updateItemHTML(); }
  function prev() { mfp.direction = false; mfp.index = wrapIndex(mfp.index - 1); updateItemHTML(); }
  function goTo(i) { mfp.direction = i >= mfp.index; mfp.index = i; updateItemHTML(); }

  function buildControls() {
    var g = mfp.st.gallery;
    if (!g.enabled || mfp.items.length < 2 || !g.arrows || mfp.arrowLeft) return;
    function arrow(dir, title, fn) {
      var b = UI.fromHTML(g.arrowMarkup.replace(/%title%/gi, title).replace(/%dir%/gi, dir));
      b.classList.add(PREVENT_CLOSE);
      b.setAttribute('aria-label', title);
      b.addEventListener('click', fn);
      return b;
    }
    mfp.arrowLeft = arrow('left', g.tPrev, prev);
    mfp.arrowRight = arrow('right', g.tNext, next);
    container.appendChild(mfp.arrowLeft);
    container.appendChild(mfp.arrowRight);
  }

  function preloadNearbyImages() {
    if (!mfp.isOpen) return;
    var p = mfp.st.gallery.preload, n = mfp.items.length;
    var before = Math.min(p[0], n), after = Math.min(p[1], n), i;
    for (i = 1; i <= (mfp.direction ? after : before); i++) preloadItem(mfp.index + i);
    for (i = 1; i <= (mfp.direction ? before : after); i++) preloadItem(mfp.index - i);
  }
  function preloadItem(i) {
    i = wrapIndex(i);
    var item = mfp.items[i];
    if (item.preloaded) return;
    if (!item.parsed) item = parseEl(i);
    fire('LazyLoad', item);
    if (item.type === 'image') {
      var img = new Image();
      img.onload = function () { item.hasSize = true; };
      img.onerror = function () { item.hasSize = item.loadError = true; fire('LazyLoadError', item); };
      img.src = item.src;
    }
    item.preloaded = true;
  }

  UI.extend(mfp, {
    open: open, close: close, next: next, prev: prev, goTo: goTo,
    updateSize: updateSize, updateItemHTML: updateItemHTML, updateStatus: updateStatus
  });

  /* ------------------------------------------------------------------ *
   * Triggers: $(els).magnificPopup(options)
   * ------------------------------------------------------------------ */

  function openClick(e, el, opts) {
    var midClick = opts.midClick !== undefined ? opts.midClick : defaults.midClick;
    // Modified clicks keep their browser meaning (open in a new tab, ...).
    if (!midClick && (e.button === 1 || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey)) return;
    var disableOn = opts.disableOn !== undefined ? opts.disableOn : defaults.disableOn;
    if (disableOn) {
      // Narrow screens follow the link instead (e.g. straight to YouTube).
      if (typeof disableOn === 'function') { if (!disableOn.call(mfp)) return; }
      else if (docEl.clientWidth < disableOn) return;
    }
    if (!opts.isObj && !getters[opts.type || 'inline']) return;   // unsupported type: plain link
    if (e.type) {
      e.preventDefault();
      if (mfp.isOpen) e.stopPropagation();
    }
    // An inline popup whose target doesn't exist (a link left at "#"): Magnific
    // cancelled the click and then threw looking it up, so nothing happened.
    // Do the same without the error, rather than opening an empty popup.
    if (!opts.isObj && !opts.delegate && (opts.type || 'inline') === 'inline' && el) {
      var src = el.getAttribute('data-mfp-src') || el.getAttribute('href') || '';
      if (!src || src === '#' || !findInline(src)) return;
    }
    opts.el = el;
    if (opts.delegate) {
      var found = [];
      opts.mainEls.forEach(function (m) {
        found = found.concat(UI.toElements(m.querySelectorAll(opts.delegate)));
      });
      opts.items = found;
    }
    open(opts);
  }

  function onTriggerClick(e) {
    var host = e.currentTarget, opts = host.__clMagnific;
    if (!opts) return;
    var el = host;
    if (opts.delegate) {
      el = e.target.closest ? e.target.closest(opts.delegate) : null;
      if (!el || el === host || !host.contains(el)) return;
    }
    openClick(e, el, opts);
  }

  function command(els, cmd, args) {
    if (cmd === 'open') {
      var el = els[0], opts = el && el.__clMagnific;
      if (!opts) return;
      var i = parseInt(args[0], 10) || 0;
      if (opts.isObj) opts.index = i;
      var list = opts.isObj ? [] : (opts.delegate ? UI.toElements(el.querySelectorAll(opts.delegate)) : opts.items);
      openClick({}, list[i], opts);
    } else if (mfp.isOpen && typeof mfp[cmd] === 'function') {
      mfp[cmd].apply(mfp, args);
    }
  }

  function bind(els, options, out) {
    // One options object shared by the call's elements, as Magnific stored it.
    var opts = merge({}, options || {});
    opts.mainEls = els;
    opts.mainEl = els[0];
    if (opts.items) opts.isObj = true;
    else {
      opts.isObj = false;
      if (!opts.delegate) opts.items = els;
    }
    els.forEach(function (el) {
      // A later call on the same element replaces its options (Magnific's
      // off().on()); the listener itself is only ever added once.
      el.__clMagnific = opts;
      if (!el.hasAttribute('data-cl-magnific')) {
        el.setAttribute('data-cl-magnific', '');
        el.addEventListener('click', onTriggerClick);
      }
      out.push({
        el: el,
        options: opts,
        popup: mfp,
        open: function (index) { command([el], 'open', [index]); },
        close: close
      });
    });
  }

  UI.magnific = function (target, options) {
    var out = [], args = Array.prototype.slice.call(arguments, 2);
    UI.ready(function () {
      var els = UI.toElements(target);
      if (!els.length) return;
      if (typeof options === 'string') command(els, options, args);
      else bind(els, options, out);
    });
    return out;
  };
  /** $.magnificPopup.open(options, index) */
  UI.magnific.open = function (options, index) {
    var c = merge({}, options || {});
    c.isObj = true;
    c.index = index || 0;
    open(c);
    return mfp;
  };
  /** $.magnificPopup.close() */
  UI.magnific.close = function () { close(); };
  UI.magnific.instance = mfp;
  UI.magnific.defaults = defaults;


  /* ------------------------------------------------------------------ *
   * Simple Lightbox 1.x: $(items).simpleLightbox(options)
   *
   * Same .slbElement DOM, classes and timing (loading text after 100 ms, image
   * shown once loaded, next image preloaded), so simpleLightbox.css renders it
   * unchanged. Additions: focus goes back to the clicked link on close, and
   * buttons get aria-labels.
   * ------------------------------------------------------------------ */

  var slbDefaults = {
    elementClass: '', elementLoadingClass: 'slbLoading', htmlClass: 'slbActive',
    closeBtnClass: '', nextBtnClass: '', prevBtnClass: '', loadingTextClass: '',
    closeBtnCaption: 'Close', nextBtnCaption: 'Next', prevBtnCaption: 'Previous',
    loadingCaption: 'Loading...', bindToItems: true, closeOnOverlayClick: true,
    closeOnEscapeKey: true, nextOnImageClick: true, showCaptions: true,
    captionAttribute: 'title', urlAttribute: 'href', startAt: 0, loadingTimeout: 100,
    appendTarget: 'body', beforeSetContent: null, beforeClose: null, beforeDestroy: null,
    videoRegex: /youtube.com|vimeo.com/
  };

  function SLB(options) {
    var self = this;
    this.options = UI.extend({}, slbDefaults, options);
    this.items = [];
    this.captions = [];
    var o = this.options;
    if (o.$items) {
      this.$items = o.$items;
      o.$items.forEach(function (el) {
        self.items.push(el.getAttribute(o.urlAttribute));
        self.captions.push(el.getAttribute(o.captionAttribute));
      });
    } else if (o.items) {
      this.items = o.items;
    }
    if (o.captions) this.captions = o.captions;
    this._onKey = function (e) { self.onKey(e); };
    this._onResize = function () { self.setImageDimensions(); };
    this._onClick = function (e) { self.onClick(e); };
  }

  SLB.prototype = {
    next: function () { return this.showPosition(this.currentPosition + 1); },
    prev: function () { return this.showPosition(this.currentPosition - 1); },
    normalizePosition: function (p) {
      if (p >= this.items.length) return 0;
      return p < 0 ? this.items.length - 1 : p;
    },
    showPosition: function (p) {
      this.currentPosition = this.normalizePosition(p);
      return this.setupLightboxHtml().prepareItem(this.currentPosition, this.setContent).show();
    },
    loading: function (on) {
      var self = this, o = this.options;
      if (on) {
        this.loadingTimer = setTimeout(function () {
          self.el.classList.add(o.elementLoadingClass);
          self.content.innerHTML = '<p class="slbLoadingText ' + o.loadingTextClass + '">' + o.loadingCaption + '</p>';
          self.show();
        }, o.loadingTimeout);
      } else {
        if (this.el) this.el.classList.remove(o.elementLoadingClass);
        clearTimeout(this.loadingTimer);
      }
    },
    prepareItem: function (pos, done) {
      var self = this, url = this.items[pos];
      this.loading(true);
      if (this.options.videoRegex.test(url)) {
        var v = UI.fromHTML('<div class="slbIframeCont"><iframe class="slbIframe" frameborder="0" allowfullscreen></iframe></div>');
        v.firstChild.src = url;
        done.call(this, v);
      } else {
        var wrapEl = UI.fromHTML('<div class="slbImageWrap"><img class="slbImage"></div>');
        this.currentImage = wrapEl.firstChild;
        this.currentImage.src = url;
        if (this.options.showCaptions && this.captions[pos]) {
          var cap = document.createElement('div');
          cap.className = 'slbCaption';
          cap.innerHTML = this.captions[pos];
          wrapEl.appendChild(cap);
        }
        this.loadImage(url, function () {
          self.setImageDimensions();
          done.call(self, wrapEl);
          self.loadImage(self.items[self.normalizePosition(self.currentPosition + 1)]);
        });
      }
      return this;
    },
    loadImage: function (url, cb) {
      if (this.options.videoRegex.test(url)) return;
      var img = new Image();
      if (cb) img.onload = cb;
      img.src = url;
    },
    setupLightboxHtml: function () {
      var o = this.options;
      if (!this.el) {
        this.el = UI.fromHTML('<div class="slbElement ' + o.elementClass + '"><div class="slbOverlay"></div>' +
          '<div class="slbWrapOuter"><div class="slbWrap"><div class="slbContentOuter"><div class="slbContent"></div>' +
          '<button type="button" title="' + o.closeBtnCaption + '" aria-label="' + o.closeBtnCaption +
          '" class="slbCloseBtn ' + o.closeBtnClass + '">×</button></div></div></div></div>');
        if (this.items.length > 1) {
          this.el.querySelector('.slbContentOuter').appendChild(UI.fromHTML('<div class="slbArrows">' +
            '<button type="button" title="' + o.prevBtnCaption + '" class="prev slbArrow' + o.prevBtnClass + '">' +
            o.prevBtnCaption + '</button><button type="button" title="' + o.nextBtnCaption +
            '" class="next slbArrow' + o.nextBtnClass + '">' + o.nextBtnCaption + '</button></div>'));
        }
        this.content = this.el.querySelector('.slbContent');
      }
      this.content.innerHTML = '';
      return this;
    },
    show: function () {
      if (!this.modalInDom) {
        (UI.toElements(this.options.appendTarget)[0] || document.body).appendChild(this.el);
        docEl.classList.add(this.options.htmlClass);
        this.setupLightboxEvents();
        this.modalInDom = true;
      }
      return this;
    },
    setContent: function (node) {
      if (typeof node === 'string') node = UI.fromHTML(node);
      this.loading(false);
      this.setupLightboxHtml();
      if (this.options.beforeSetContent) this.options.beforeSetContent(node, this);
      this.content.innerHTML = '';
      this.content.appendChild(node);
      return this;
    },
    setImageDimensions: function () {
      if (this.currentImage) this.currentImage.style.maxHeight = docEl.clientHeight + 'px';
    },
    onClick: function (e) {
      var t = e.target, cl = t.classList, o = this.options;
      if (!cl) return;
      if (cl.contains('slbCloseBtn') || (o.closeOnOverlayClick && cl.contains('slbWrap'))) this.close();
      else if (cl.contains('slbArrow')) { if (cl.contains('next')) this.next(); else this.prev(); }
      else if (o.nextOnImageClick && this.items.length > 1 && cl.contains('slbImage')) this.next();
    },
    onKey: function (e) {
      var k = e.keyCode;
      if (this.options.closeOnEscapeKey && k === 27) this.close();
      if (this.items.length > 1) {
        if (k === 39 || k === 68) this.next();
        if (k === 37 || k === 65) this.prev();
      }
    },
    setupLightboxEvents: function () {
      if (this.eventsOn) return;
      this.el.addEventListener('click', this._onClick);
      document.addEventListener('keyup', this._onKey);
      window.addEventListener('resize', this._onResize);
      this.eventsOn = true;
    },
    close: function () {
      if (!this.modalInDom) return;
      if (this.options.beforeClose) this.options.beforeClose(this);
      this.el.removeEventListener('click', this._onClick);
      document.removeEventListener('keyup', this._onKey);
      window.removeEventListener('resize', this._onResize);
      this.eventsOn = false;
      detach(this.el);
      docEl.classList.remove(this.options.htmlClass);
      this.modalInDom = false;
      if (this.trigger && this.trigger.focus) this.trigger.focus();
    },
    destroy: function () {
      this.close();
      if (this.options.beforeDestroy) this.options.beforeDestroy(this);
      var self = this;
      if (this.$items) this.$items.forEach(function (el) { el.removeEventListener('click', self._onItem); });
      detach(this.el);
    }
  };

  UI.simpleLightbox = function (target, options) {
    var out = [];
    UI.ready(function () {
      var els = UI.toElements(target);
      var fresh = els.filter(function (el) { return !el.hasAttribute('data-cl-slb'); });
      if (!fresh.length) return;
      var inst = new SLB(UI.extend({}, options, { $items: els }));
      inst._onItem = function (e) {
        e.preventDefault();
        inst.trigger = e.currentTarget;
        inst.showPosition(els.indexOf(e.currentTarget));
      };
      fresh.forEach(function (el) {
        el.setAttribute('data-cl-slb', '');
        if (inst.options.bindToItems) el.addEventListener('click', inst._onItem);
        out.push(inst);
      });
    });
    return out;
  };
  /** $.SimpleLightbox.open(options) */
  UI.simpleLightbox.open = function (options) {
    var inst = new SLB(options);
    return options && options.content ? inst.setContent(options.content).show() : inst.showPosition(inst.options.startAt);
  };
}());

/* ColorlibUI module: isotope — replaces Isotope 3.0.5, the jQuery bridge to Masonry and
 * jQuery imagesLoaded. WordPress core already ships Masonry 4.2.2 (window.Masonry, script
 * handle 'masonry') and imagesLoaded 5 (window.imagesLoaded, handle 'imagesloaded'), so
 * UI.masonry() and UI.imagesLoaded() are thin wrappers over those globals, and UI.isotope()
 * is filtering on top of a core Masonry instance. Isotope's default layout mode *is*
 * Masonry's code, so laying out only the matching items with Masonry gives the same
 * geometry and the same inline styles (position/left/top on items, position/height on the
 * container); items that are filtered out are hidden with Masonry's own hide() (the
 * opacity/scale transition that ends in display:none, exactly Isotope's). fitRows is a
 * small override of the same Masonry instance's layout methods.
 */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var slice = Array.prototype.slice;
  var warned = {};

  /** A core global, or a console warning once: the theme's enqueue declares the dependency. */
  function lib(name) {
    var fn = window[name];
    if (!fn && !warned[name]) {
      warned[name] = true;
      if (window.console) {
        console.warn('ColorlibUI: window.' + name + ' is missing; enqueue the WordPress core "' +
          name.toLowerCase() + '" script.');
      }
    }
    return fn;
  }

  /** Emitter.once that really runs once (EvEmitter 1.x keys once-listeners by source text). */
  function once(emitter, type, fn) {
    function handler() {
      emitter.off(type, handler);
      fn.apply(this, arguments);
    }
    emitter.on(type, handler);
  }

  /**
   * Outlayer's dispatchEvent runs its own listeners, then triggers the jQuery event; the DOM
   * event goes in that same place, so events reach the page in the order jQuery saw them
   * (arrangeComplete before the layoutComplete that completed it).
   */
  function bridgeEvents(layout, el, instance) {
    var dispatch = layout.dispatchEvent;
    layout.dispatchEvent = function (type, event, args) {
      dispatch.call(this, type, event, args);
      UI.emit(el, type, { items: (args && args[0]) || [], instance: instance });
    };
  }

  /**
   * Isotope and Masonry never re-layout by themselves when images arrive; themes that init
   * on DOM ready then show overlapping items. Lay out again as late images load (only
   * images still loading at init, so a finished page gets no extra layoutComplete).
   */
  function relayoutOnImages(el, layout) {
    var imagesLoaded = window.imagesLoaded;
    if (!imagesLoaded) return;
    var pending = slice.call(el.querySelectorAll('img')).some(function (img) {
      return !img.complete;
    });
    if (pending) imagesLoaded(el).on('progress', UI.debounce(layout, 60));
  }

  /** Run fn per element at DOM ready; jQuery-bridge return value (method result or list). */
  function run(name, target, fn) {
    var list = [];
    var ret;
    UI.each(target, function (el) {
      var r = fn(el);
      if (!r) return;
      list.push(r.instance);
      if (r.value !== undefined && ret === undefined) ret = r.value;
    });
    // $grid = $('.grid').isotope({...}); $grid.isotope({ filter: v }) keeps working.
    list[name] = function () {
      return UI[name].apply(null, [target].concat(slice.call(arguments)));
    };
    return ret !== undefined ? ret : list;
  }

  /** $('.grid').plugin('method', ...) */
  function callMethod(instance, method, args) {
    if (!instance || method.charAt(0) === '_' || typeof instance[method] !== 'function') return null;
    return { instance: instance, value: instance[method].apply(instance, args) };
  }

  function reduceMotion(options) {
    return UI.reducedMotion ? UI.extend({}, options, { transitionDuration: 0 }) : options;
  }

  /* ------------------------------------------------------------------ *
   * imagesLoaded
   * ------------------------------------------------------------------ */

  /** $(target).imagesLoaded(callback): one callback once every image inside has loaded or failed. */
  UI.imagesLoaded = function (target, options, callback) {
    var imagesLoaded = lib('imagesLoaded');
    var list = [];
    if (!imagesLoaded) return list;
    UI.ready(function () {
      list.push(imagesLoaded(UI.toElements(target), options, callback));
    });
    return list;
  };

  /* ------------------------------------------------------------------ *
   * Masonry
   * ------------------------------------------------------------------ */

  /** $('.grid').masonry({...}): new Masonry(el, options), same options. */
  UI.masonry = function (target, options) {
    var Masonry = lib('Masonry');
    var args = slice.call(arguments, 2);
    if (!Masonry) return [];
    return run('masonry', target, function (el) {
      var msnry = Masonry.data(el);
      if (typeof options === 'string') return callMethod(msnry, options, args);
      if (msnry) {
        // What the jQuery bridge did on a second call: set options, lay out again.
        msnry.option(reduceMotion(options || {}));
        msnry.layout();
        return { instance: msnry };
      }
      var opts = UI.extend({}, reduceMotion(options || {}));
      var initLayout = opts.isInitLayout !== undefined ? opts.isInitLayout : opts.initLayout !== false;
      delete opts.isInitLayout;
      opts.initLayout = false;
      el.setAttribute('data-cl-masonry', '1');
      msnry = new Masonry(el, opts);
      // Listen before the first layout so its layoutComplete reaches the page too.
      bridgeEvents(msnry, el, msnry);
      if (initLayout) msnry.layout();
      relayoutOnImages(el, function () {
        if (Masonry.data(el) === msnry) msnry.layout();
      });
      return { instance: msnry };
    });
  };

  /* ------------------------------------------------------------------ *
   * Isotope
   * ------------------------------------------------------------------ */

  // Isotope reads these from options.masonry, never from the top level: sunshine-wedding's
  // top-level `gutter: 10` did nothing, so it must not reach Masonry either.
  var MODE_KEYS = { columnWidth: 1, gutter: 1, fitWidth: 1, isFitWidth: 1, horizontalOrder: 1 };
  var OWN_KEYS = {
    filter: 1, layoutMode: 1, masonry: 1, fitRows: 1, vertical: 1, sortBy: 1, getSortData: 1,
    sortAscending: 1, isJQueryFiltering: 1, initLayout: 1, isInitLayout: 1
  };
  var LAYOUT_METHODS = ['_resetLayout', '_getItemLayoutPosition', '_getContainerSize', 'needsResizeLayout'];

  /** matches(), plus jQuery's leniency for class names CSS rejects (".2019" from a slug). */
  function matches(el, selector) {
    try {
      return el.matches(selector);
    } catch (e) {
      return selector.split(',').some(function (part) {
        part = part.trim();
        if (part === '*') return true;
        if (!/^(\.[\w-]+)+$/.test(part)) return false;
        return part.slice(1).split('.').every(function (c) { return el.classList.contains(c); });
      });
    }
  }

  /** Outlayer's _getMeasurement: a selector/element's size, or the number as given. */
  function measure(root, option, prop) {
    if (!option) return 0;
    var el = typeof option === 'string' ? root.querySelector(option) :
      option instanceof HTMLElement ? option : null;
    if (!el) return option;
    return window.getSize ? window.getSize(el)[prop] : el.offsetWidth;
  }

  function setPositionInt(x, y) {
    this.position.x = parseInt(x, 10);
    this.position.y = parseInt(y, 10);
  }

  /**
   * Every Isotope the themes shipped (3.0.1 to 3.0.5, Outlayer 2.1.0) truncated item positions
   * to whole pixels; core Masonry (Outlayer 2.1.1) keeps the fractions. Truncate too, so items
   * land on the same pixels and write the same left/top values as before.
   */
  function wholePixels(m) {
    var itemize = m._itemize;
    function patch(items) {
      items.forEach(function (item) { item.setPosition = setPositionInt; });
      return items;
    }
    patch(m.items);
    m._itemize = function () {
      return patch(itemize.apply(this, arguments));
    };
  }

  function Isotope(el, options) {
    this.element = el;
    this.options = { layoutMode: 'masonry' };
    this.option(options);
    this.masonry = new window.Masonry(el, this._masonryOptions());
    this.items = this.masonry.items;
    this.filteredItems = this.items;
    this._setMode();
    wholePixels(this.masonry);
    bridgeEvents(this.masonry, el, this);
    if (this._getOption('initLayout') !== false) this.layout();

    var self = this;
    relayoutOnImages(el, function () {
      if (el.__clIsotope === self) self.layout();
    });
  }

  var proto = Isotope.prototype;

  proto._getOption = function (name) {
    var old = { initLayout: 'isInitLayout', layoutInstant: 'isLayoutInstant' }[name];
    return old && this.options[old] !== undefined ? this.options[old] : this.options[name];
  };

  /** Isotope's options as Masonry options: Outlayer ones as given, mode ones from `masonry`. */
  proto._masonryOptions = function () {
    var src = this.options;
    var out = {};
    for (var k in src) {
      if (Object.prototype.hasOwnProperty.call(src, k) && !MODE_KEYS[k] && !OWN_KEYS[k]) out[k] = src[k];
    }
    UI.extend(out, src.masonry);
    out.initLayout = false; // Isotope filters before its first layout
    return reduceMotion(out);
  };

  proto.option = function (opts) {
    UI.extend(this.options, opts);
    if (this.masonry) {
      this.masonry.option(this._masonryOptions());
      this._setMode();
    }
  };

  /** Masonry's own methods for layoutMode 'masonry'; instance overrides for 'fitRows'. */
  proto._setMode = function () {
    var m = this.masonry;
    var self = this;
    LAYOUT_METHODS.forEach(function (k) { delete m[k]; });
    if (this.options.layoutMode !== 'fitRows') return;
    // isotope-layout/js/layout-modes/fit-rows.js
    m._resetLayout = function () {
      this.getSize();
      this.x = 0;
      this.y = 0;
      this.maxY = 0;
      this.gutter = measure(this.element, (self.options.fitRows || {}).gutter, 'outerWidth');
    };
    m._getItemLayoutPosition = function (item) {
      item.getSize();
      var itemWidth = item.size.outerWidth + this.gutter;
      var containerWidth = this.size.innerWidth + this.gutter;
      if (this.x !== 0 && itemWidth + this.x > containerWidth) {
        this.x = 0;
        this.y = this.maxY;
      }
      var position = { x: this.x, y: this.y };
      this.maxY = Math.max(this.maxY, this.y + item.size.outerHeight);
      this.x += itemWidth;
      return position;
    };
    m._getContainerSize = function () {
      return { height: this.maxY };
    };
    m.needsResizeLayout = window.Outlayer ? window.Outlayer.prototype.needsResizeLayout : m.needsResizeLayout;
  };

  proto._isInstant = function () {
    var instant = this._getOption('layoutInstant');
    return instant !== undefined ? instant : !this.masonry._isLayoutInited;
  };

  proto._filter = function (items) {
    var filter = this.options.filter || '*';
    var test = typeof filter === 'function' ?
      function (el) { return filter.call(el, 0, el); } : // jQuery .is(fn) signature
      function (el) { return matches(el, filter); };
    var out = { matches: [], needReveal: [], needHide: [] };
    items.forEach(function (item) {
      if (item.isIgnored) return;
      var isMatched = test(item.element);
      if (isMatched) out.matches.push(item);
      if (isMatched && item.isHidden) out.needReveal.push(item);
      else if (!isMatched && !item.isHidden) out.needHide.push(item);
    });
    return out;
  };

  /** Filter + layout. Masonry lays out whatever is in its items: the matching ones. */
  proto.arrange = function (opts) {
    var m = this.masonry;
    if (opts) this.option(opts);
    var instant = this._isInstant();
    var filtered = this._filter(this.items);
    this.filteredItems = filtered.matches;

    // arrangeComplete once layout, hide and reveal have all finished.
    var pending = 3;
    var self = this;
    function done() {
      if (--pending === 0) m.dispatchEvent('arrangeComplete', null, [self.filteredItems]);
    }
    once(m, 'layoutComplete', done);
    once(m, 'hideComplete', done);
    once(m, 'revealComplete', done);

    m.items = this.items; // hide()/reveal() look items up here
    var duration = m.options.transitionDuration;
    if (instant) m.options.transitionDuration = 0; // first arrange: no hide animation
    m.reveal(filtered.needReveal);
    m.hide(filtered.needHide);
    m.options.transitionDuration = duration;

    m.items = this.filteredItems;
    m.layout();
  };

  proto.layout = function () {
    if (!this.masonry._isLayoutInited && this._getOption('initLayout') !== false) {
      this.arrange();
      return;
    }
    this.masonry.items = this.filteredItems;
    this.masonry.layout();
  };

  proto.reloadItems = function () {
    var m = this.masonry;
    m.reloadItems();
    this.items = m.items;
    m.items = this.filteredItems; // as Isotope: filteredItems only change on arrange()
  };

  proto.appended = function (elems) {
    var m = this.masonry;
    m.items = this.items;
    var added = m.addItems(elems);
    this.items = m.items;
    var filtered = this._filter(added);
    m.hide(filtered.needHide);
    m.reveal(filtered.matches);
    m.layoutItems(filtered.matches, true);
    this.filteredItems = this.filteredItems.concat(filtered.matches);
    m.items = this.filteredItems;
  };

  proto.remove = function (elems) {
    var m = this.masonry;
    m.items = this.items;
    var gone = m.getItems(UI.toElements(elems));
    m.remove(elems);
    this.items = m.items;
    this.filteredItems = this.filteredItems.filter(function (item) { return gone.indexOf(item) === -1; });
    m.items = this.filteredItems;
  };

  proto.getItemElements = function () {
    return this.items.map(function (item) { return item.element; });
  };

  proto.getFilteredItemElements = function () {
    return this.filteredItems.map(function (item) { return item.element; });
  };

  proto.on = function (type, fn) { this.masonry.on(type, fn); return this; };
  proto.off = function (type, fn) { this.masonry.off(type, fn); return this; };
  proto.once = function (type, fn) { once(this.masonry, type, fn); return this; };

  proto.destroy = function () {
    var m = this.masonry;
    m.items = this.items;
    m.destroy();
    this.items.forEach(function (item) { item.element.style.display = ''; });
    delete this.element.__clIsotope;
    this.element.removeAttribute('data-cl-isotope');
  };

  /** $('.grid').isotope({...}), $grid.isotope({ filter: '.x' }), $grid.isotope('reloadItems'). */
  UI.isotope = function (target, options) {
    var args = slice.call(arguments, 2);
    if (!lib('Masonry')) return [];
    return run('isotope', target, function (el) {
      var iso = el.__clIsotope;
      if (typeof options === 'string') return callMethod(iso, options, args);
      if (iso) {
        iso.arrange(options); // a second call re-filters, as with jQuery
      } else {
        el.setAttribute('data-cl-isotope', '1');
        iso = el.__clIsotope = new Isotope(el, options);
      }
      return { instance: iso };
    });
  };
}());

/* ColorlibUI module: ajaxchimp — replaces jquery.ajaxchimp. A Mailchimp embed
 * form posts to .../subscribe/post?u=..&id=..; like the plugin, this turns it into
 * a JSONP request to .../subscribe/post-json?u=..&id=..&c=<callback> (Mailchimp
 * sends no CORS headers, so JSONP is still the only way to read the answer), stops
 * the normal submit and writes the result into the form's `.info` element with the
 * `valid` / `error` classes on it and on the email input. The JSONP call is a
 * script tag with a one-off global callback and a timeout. */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var SUCCESS = 'We have sent you a confirmation email';
  var seq = 0;
  var cacheBust = Date.now();
  var SHOW_PROPS = ['height', 'marginTop', 'marginBottom', 'paddingTop', 'paddingBottom',
    'width', 'marginLeft', 'marginRight', 'paddingLeft', 'paddingRight'];

  /** jQuery's form.serializeArray(), folded into an object (last value wins). */
  function serialize(form) {
    var data = {};
    Array.prototype.forEach.call(form.elements, function (el) {
      if (!el.name || el.disabled || el.matches(':disabled')) return;
      if (!/^(?:input|select|textarea|keygen)/i.test(el.nodeName)) return;
      if (/^(?:submit|button|image|reset|file)$/i.test(el.type)) return;
      if (/^(?:checkbox|radio)$/i.test(el.type) && !el.checked) return;
      if (el.nodeName === 'SELECT' && el.multiple) {
        Array.prototype.forEach.call(el.options, function (o) { if (o.selected) data[el.name] = o.value; });
        return;
      }
      if (el.nodeName === 'SELECT' && el.selectedIndex < 0) return;
      data[el.name] = el.value.replace(/\r?\n/g, '\r\n');
    });
    return data;
  }

  function param(data) {
    return Object.keys(data).map(function (k) {
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k] == null ? '' : data[k]);
    }).join('&');
  }

  /** JSONP like jQuery's: url's `c=?` becomes the callback name; data and `_=` are appended. */
  function jsonp(url, data, success, error) {
    var name = 'ColorlibUIChimp_' + (++seq) + '_' + Date.now();
    var script = document.createElement('script');
    var called = false, timer;
    function cleanup(answered) {
      clearTimeout(timer);
      // After a timeout or error a late reply may still arrive: leave it a no-op to call.
      if (answered) { try { delete window[name]; } catch (e) { window[name] = undefined; } }
      else window[name] = function () {};
      if (script.parentNode) script.parentNode.removeChild(script);
    }
    window[name] = function (resp) { called = true; cleanup(true); success(resp); };
    url = url.replace(/(=)\?(?=&|$)|\?\?/, '$1' + name);
    var qs = param(data);
    if (qs) url += (/\?/.test(url) ? '&' : '?') + qs;
    url += (/\?/.test(url) ? '&' : '?') + '_=' + (cacheBust++);
    script.async = true;
    script.src = url;
    script.onload = function () { if (!called) { cleanup(); error('parsererror'); } };
    script.onerror = function () { cleanup(); error('error'); };
    timer = setTimeout(function () { cleanup(); error('timeout'); }, 15000);
    document.head.appendChild(script);
  }

  /** jQuery's show(2000): a hidden element grows and fades in; a visible one is left alone. */
  function show(el, ms) {
    if (getComputedStyle(el).display !== 'none') return;
    el.style.display = '';
    if (getComputedStyle(el).display === 'none') el.style.display = 'block';
    if (UI.reducedMotion) return;
    var cs = getComputedStyle(el), full = {}, start = null;
    SHOW_PROPS.forEach(function (p) { full[p] = parseFloat(cs[p]) || 0; });
    var opacity = parseFloat(cs.opacity);
    el.style.overflow = 'hidden';
    function apply(e) {
      SHOW_PROPS.forEach(function (p) { el.style[p] = full[p] * e + 'px'; });
      el.style.opacity = String(opacity * e);
    }
    apply(0);
    requestAnimationFrame(function frame(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / ms, 1);
      apply(0.5 - Math.cos(p * Math.PI) / 2);
      if (p < 1) { requestAnimationFrame(frame); return; }
      SHOW_PROPS.forEach(function (prop) { el.style[prop] = ''; });
      el.style.opacity = '';
      el.style.overflow = '';
    });
  }

  function each(list, fn) { Array.prototype.forEach.call(list, fn); }
  function swap(list, remove, add) {
    each(list, function (el) { el.classList.remove(remove); el.classList.add(add); });
  }

  function translate(language, key) {
    var t = UI.ajaxChimp.translations;
    return language !== 'en' && t && t[language] && t[language][key] ? t[language][key] : null;
  }

  function init(form, options) {
    var emails = form.querySelectorAll('input[type=email]');
    var labels = form.querySelectorAll('.info');
    var s = UI.extend({ url: form.getAttribute('action'), language: 'en' }, options);
    if (!s.url) return null;
    var url = s.url.replace('/post?', '/post-json?').concat('&c=?');

    form.setAttribute('novalidate', 'true');
    each(emails, function (e) { e.setAttribute('name', 'EMAIL'); });

    function setLabel(html) {
      each(labels, function (l) { l.innerHTML = html; show(l, 2000); });
    }

    function onResponse(resp) {
      var msg;
      if (resp.result === 'success') {
        msg = SUCCESS;
        swap(labels, 'error', 'valid');
        swap(emails, 'error', 'valid');
      } else {
        swap(emails, 'valid', 'error');
        swap(labels, 'valid', 'error');
        // Mailchimp prefixes field errors with the field index: "0 - Please enter a value".
        try {
          var parts = resp.msg.split(' - ', 2);
          msg = parts[1] !== undefined && parseInt(parts[0], 10).toString() === parts[0] ? parts[1] : resp.msg;
        } catch (e) {
          msg = resp.msg;
        }
      }
      var code = UI.ajaxChimp.responses[msg];
      if (code !== undefined) msg = translate(s.language, code) || msg;
      setLabel(msg);
      if (s.callback) s.callback(resp);
      UI.emit(form, 'ajaxchimp:response', resp);
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();
      jsonp(url, serialize(form), onResponse, function (text) {
        if (window.console) console.log('mailchimp ajax submit error: ' + text);
      });
      setLabel(translate(s.language, 'submit') || 'Submitting...');
    });
    return { form: form, settings: s };
  }

  /** $(form).ajaxChimp(options) → ColorlibUI.ajaxChimp(form, options). */
  UI.ajaxChimp = function (target, options) {
    var out = [];
    UI.toElements(target).forEach(function (form) {
      if (form._clAjaxChimp) { out.push(form._clAjaxChimp); return; }
      var inst = init(form, options);
      if (!inst) return;
      form._clAjaxChimp = inst;
      form.setAttribute('data-cl-ajaxchimp', '1');
      out.push(inst);
    });
    return out;
  };
  UI.ajaxChimp.responses = {
    'We have sent you a confirmation email': 0,
    'Please enter a valid email': 1,
    'An email address must contain a single @': 2,
    'The domain portion of the email address is invalid (the portion after the @: )': 3,
    'The username portion of the email address is invalid (the portion before the @: )': 4,
    'This email address looks fake or invalid. Please enter a real email address': 5
  };
  UI.ajaxChimp.translations = { en: null };
  UI.ajaxChimp.init = function (selector, options) { return UI.ajaxChimp(selector, options); };
}());
