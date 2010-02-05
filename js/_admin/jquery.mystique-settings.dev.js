jQuery.fn.appendVal = function (txt) {
  return this.each(function () {
    this.value += txt;
  });
};



// cookie functions
jQuery.cookie = function (name, value, options) {
  if (typeof value != 'undefined') { // name and value given, set cookie
    options = options || {};
    if (value === null) {
      value = '';
      options = jQuery.extend({},
      options); // clone object since it's unexpected behavior if the expired property were changed
      options.expires = -1;
    }
    var expires = '';
    if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
      var date;
      if (typeof options.expires == 'number') {
        date = new Date();
        date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
      } else {
        date = options.expires;
      }
      expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
    } // NOTE Needed to parenthesize options.path and options.domain
    // in the following expressions, otherwise they evaluate to undefined
    // in the packed version for some reason...
    var path = options.path ? '; path=' + (options.path) : '';
    var domain = options.domain ? '; domain=' + (options.domain) : '';
    var secure = options.secure ? '; secure' : '';
    document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
  } else { // only name given, get cookie
    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
      var cookies = document.cookie.split(';');
      for (var i = 0; i < cookies.length; i++) {
        var cookie = jQuery.trim(cookies[i]); // Does this cookie string begin with the name we want?
        if (cookie.substring(0, name.length + 1) == (name + '=')) {
          cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
          break;
        }
      }
    }
    return cookieValue;
  }
};

// optimized minitabs
(function (jQuery) {
  jQuery.fn.minitabs = function (options) {
    jQuery.fn.minitabs.defaults = {
      contentClass: '.sections',
      effect: 'top',
      speed: 333
    };
    var o = jQuery.extend({},
    jQuery.fn.minitabs.defaults, options);
    return this.each(function () {
      var $tabs = jQuery(this); // check for the active tab cookie
      var cookieID = $tabs.attr('id');
      var cookieState = jQuery.cookie(cookieID); // hide all sections
      $tabs.find(o.contentClass + " >div:gt(0)").hide();
      if (cookieState != null) { // if we have a cookie then show the section according to its value
        $tabs.find('li.' + cookieState).addClass("active");
        var link = $tabs.find('li.' + cookieState + ' a');
        var section = link.attr('href');
        $tabs.find(o.contentClass + ' div' + section).show();
      } else { // if not, show the 1st section
        $tabs.find('ul:first').find('li:first').addClass("active");
        $tabs.find(o.contentClass + ' div:first').show();
      }
      $tabs.find("ul>li>a").click(function () {
        $tabs.find('ul>li').removeClass("active");
        var cookieValue = jQuery(this).parent('li').attr("class");
        jQuery.cookie(cookieID, cookieValue, {
          path: '/'
        });
        jQuery(this).parent('li').addClass("active");
        jQuery(this).blur();
        var re = /([_\-\w]+$)/i;
        var target = jQuery('#' + re.exec(this.href)[1]);
        $tabs.find(o.contentClass + " >div").hide();
        target.css({
          opacity: 0,
          top: -20
        }).show().animate({
          opacity: 1,
          top: 0
        },
        o.speed);
        return false;
      })
    });
  };
})(jQuery);

// convert radio buttons to select
jQuery.fn.radio2select = function () {
  return this.each(function () {
    var $section = jQuery(this);

    $section.find('input[type=radio]').change(function () {
      jQuery(this).css("visibility", "hidden");
      $section.find('label').removeClass("checked");
      val = $section.find('input[type=radio]:checked').attr('value');
      $section.find('label.' + val).addClass("checked");
    });
    jQuery(this).find('input[type=radio]').change();


  });
};






/*
 * jQuery UI Slider 1.7.1
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jQueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jQuery.com/UI/Slider
 *
 * Depends:
 *	ui.core.js
 */
(function ($) {
  $.widget("ui.slider", $.extend({},
  $.ui.mouse, {
    _init: function () {
      var self = this,
        o = this.options;
      this._keySliding = false;
      this._handleIndex = null;
      this._detectOrientation();
      this._mouseInit();
      this.element.addClass("ui-slider" + " ui-slider-" + this.orientation + " ui-widget" + " ui-widget-content" + " ui-corner-all");
      this.range = $([]);
      if (o.range) {
        if (o.range === true) {
          this.range = $('<div></div>');
          if (!o.values) o.values = [this._valueMin(), this._valueMin()];
          if (o.values.length && o.values.length != 2) {
            o.values = [o.values[0], o.values[0]];
          }
        } else {
          this.range = $('<div></div>');
        }
        this.range.appendTo(this.element).addClass("ui-slider-range");
        if (o.range == "min" || o.range == "max") {
          this.range.addClass("ui-slider-range-" + o.range);
        } // note: this isn't the most fittingly semantic framework class for this element,
        // but worked best visually with a variety of themes
        this.range.addClass("ui-widget-header");
      }
      if ($(".ui-slider-handle", this.element).length == 0) $('<a href="#"></a>').appendTo(this.element).addClass("ui-slider-handle");
      if (o.values && o.values.length) {
        while ($(".ui-slider-handle", this.element).length < o.values.length) $('<a href="#"></a>').appendTo(this.element).addClass("ui-slider-handle");
      }
      this.handles = $(".ui-slider-handle", this.element).addClass("ui-state-default" + " ui-corner-all");
      this.handle = this.handles.eq(0);
      this.handles.add(this.range).filter("a").click(function (event) {
        event.preventDefault();
      }).hover(function () {
        $(this).addClass('ui-state-hover');
      },
      function () {
        $(this).removeClass('ui-state-hover');
      }).focus(function () {
        $(".ui-slider .ui-state-focus").removeClass('ui-state-focus');
        $(this).addClass('ui-state-focus');
      }).blur(function () {
        $(this).removeClass('ui-state-focus');
      });
      this.handles.each(function (i) {
        $(this).data("index.ui-slider-handle", i);
      });
      this.handles.keydown(function (event) {
        var ret = true;
        var index = $(this).data("index.ui-slider-handle");
        if (self.options.disabled) return;
        switch (event.keyCode) {
        case $.ui.keyCode.HOME:
        case $.ui.keyCode.END:
        case $.ui.keyCode.UP:
        case $.ui.keyCode.RIGHT:
        case $.ui.keyCode.DOWN:
        case $.ui.keyCode.LEFT:
          ret = false;
          if (!self._keySliding) {
            self._keySliding = true;
            $(this).addClass("ui-state-active");
            self._start(event, index);
          }
          break;
        }
        var curVal, newVal, step = self._step();
        if (self.options.values && self.options.values.length) {
          curVal = newVal = self.values(index);
        } else {
          curVal = newVal = self.value();
        }
        switch (event.keyCode) {
        case $.ui.keyCode.HOME:
          newVal = self._valueMin();
          break;
        case $.ui.keyCode.END:
          newVal = self._valueMax();
          break;
        case $.ui.keyCode.UP:
        case $.ui.keyCode.RIGHT:
          if (curVal == self._valueMax()) return;
          newVal = curVal + step;
          break;
        case $.ui.keyCode.DOWN:
        case $.ui.keyCode.LEFT:
          if (curVal == self._valueMin()) return;
          newVal = curVal - step;
          break;
        }
        self._slide(event, index, newVal);
        return ret;
      }).keyup(function (event) {
        var index = $(this).data("index.ui-slider-handle");
        if (self._keySliding) {
          self._stop(event, index);
          self._change(event, index);
          self._keySliding = false;
          $(this).removeClass("ui-state-active");
        }
      });
      this._refreshValue();
    },
    destroy: function () {
      this.handles.remove();
      this.range.remove();
      this.element.removeClass("ui-slider" + " ui-slider-horizontal" + " ui-slider-vertical" + " ui-slider-disabled" + " ui-widget" + " ui-widget-content" + " ui-corner-all").removeData("slider").unbind(".slider");
      this._mouseDestroy();
    },
    _mouseCapture: function (event) {
      var o = this.options;
      if (o.disabled) return false;
      this.elementSize = {
        width: this.element.outerWidth(),
        height: this.element.outerHeight()
      };
      this.elementOffset = this.element.offset();
      var position = {
        x: event.pageX,
        y: event.pageY
      };
      var normValue = this._normValueFromMouse(position);
      var distance = this._valueMax() - this._valueMin() + 1,
        closestHandle;
      var self = this,
        index;
      this.handles.each(function (i) {
        var thisDistance = Math.abs(normValue - self.values(i));
        if (distance > thisDistance) {
          distance = thisDistance;
          closestHandle = $(this);
          index = i;
        }
      }); // workaround for bug #3736 (if both handles of a range are at 0,
      // the first is always used as the one with least distance,
      // and moving it is obviously prevented by preventing negative ranges)
      if (o.range == true && this.values(1) == o.min) {
        closestHandle = $(this.handles[++index]);
      }
      this._start(event, index);
      self._handleIndex = index;
      closestHandle.addClass("ui-state-active").focus();
      var offset = closestHandle.offset();
      var mouseOverHandle = !$(event.target).parents().andSelf().is('.ui-slider-handle');
      this._clickOffset = mouseOverHandle ? {
        left: 0,
        top: 0
      } : {
        left: event.pageX - offset.left - (closestHandle.width() / 2),
        top: event.pageY - offset.top - (closestHandle.height() / 2) - (parseInt(closestHandle.css('borderTopWidth'), 10) || 0) - (parseInt(closestHandle.css('borderBottomWidth'), 10) || 0) + (parseInt(closestHandle.css('marginTop'), 10) || 0)
      };
      normValue = this._normValueFromMouse(position);
      this._slide(event, index, normValue);
      return true;
    },
    _mouseStart: function (event) {
      return true;
    },
    _mouseDrag: function (event) {
      var position = {
        x: event.pageX,
        y: event.pageY
      };
      var normValue = this._normValueFromMouse(position);
      this._slide(event, this._handleIndex, normValue);
      return false;
    },
    _mouseStop: function (event) {
      this.handles.removeClass("ui-state-active");
      this._stop(event, this._handleIndex);
      this._change(event, this._handleIndex);
      this._handleIndex = null;
      this._clickOffset = null;
      return false;
    },
    _detectOrientation: function () {
      this.orientation = this.options.orientation == 'vertical' ? 'vertical' : 'horizontal';
    },
    _normValueFromMouse: function (position) {
      var pixelTotal, pixelMouse;
      if ('horizontal' == this.orientation) {
        pixelTotal = this.elementSize.width;
        pixelMouse = position.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left : 0);
      } else {
        pixelTotal = this.elementSize.height;
        pixelMouse = position.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top : 0);
      }
      var percentMouse = (pixelMouse / pixelTotal);
      if (percentMouse > 1) percentMouse = 1;
      if (percentMouse < 0) percentMouse = 0;
      if ('vertical' == this.orientation) percentMouse = 1 - percentMouse;
      var valueTotal = this._valueMax() - this._valueMin(),
        valueMouse = percentMouse * valueTotal,
        valueMouseModStep = valueMouse % this.options.step,
        normValue = this._valueMin() + valueMouse - valueMouseModStep;
      if (valueMouseModStep > (this.options.step / 2)) normValue += this.options.step; // Since JavaScript has problems with large floats, round
      // the final value to 5 digits after the decimal point (see #4124)
      return parseFloat(normValue.toFixed(5));
    },
    _start: function (event, index) {
      var uiHash = {
        handle: this.handles[index],
        value: this.value()
      };
      if (this.options.values && this.options.values.length) {
        uiHash.value = this.values(index);
        uiHash.values = this.values();
      }
      this._trigger("start", event, uiHash);
    },
    _slide: function (event, index, newVal) {
      var handle = this.handles[index];
      if (this.options.values && this.options.values.length) {
        var otherVal = this.values(index ? 0 : 1);
        if ((index == 0 && newVal >= otherVal) || (index == 1 && newVal <= otherVal)) newVal = otherVal;
        if (newVal != this.values(index)) {
          var newValues = this.values();
          newValues[index] = newVal; // A slide can be canceled by returning false from the slide callback
          var allowed = this._trigger("slide", event, {
            handle: this.handles[index],
            value: newVal,
            values: newValues
          });
          var otherVal = this.values(index ? 0 : 1);
          if (allowed !== false) {
            this.values(index, newVal, (event.type == 'mousedown' && this.options.animate), true);
          }
        }
      } else {
        if (newVal != this.value()) { // A slide can be canceled by returning false from the slide callback
          var allowed = this._trigger("slide", event, {
            handle: this.handles[index],
            value: newVal
          });
          if (allowed !== false) {
            this._setData('value', newVal, (event.type == 'mousedown' && this.options.animate));
          }
        }
      }
    },
    _stop: function (event, index) {
      var uiHash = {
        handle: this.handles[index],
        value: this.value()
      };
      if (this.options.values && this.options.values.length) {
        uiHash.value = this.values(index);
        uiHash.values = this.values();
      }
      this._trigger("stop", event, uiHash);
    },
    _change: function (event, index) {
      var uiHash = {
        handle: this.handles[index],
        value: this.value()
      };
      if (this.options.values && this.options.values.length) {
        uiHash.value = this.values(index);
        uiHash.values = this.values();
      }
      this._trigger("change", event, uiHash);
    },
    value: function (newValue) {
      if (arguments.length) {
        this._setData("value", newValue);
        this._change(null, 0);
      }
      return this._value();
    },
    values: function (index, newValue, animated, noPropagation) {
      if (arguments.length > 1) {
        this.options.values[index] = newValue;
        this._refreshValue(animated);
        if (!noPropagation) this._change(null, index);
      }
      if (arguments.length) {
        if (this.options.values && this.options.values.length) {
          return this._values(index);
        } else {
          return this.value();
        }
      } else {
        return this._values();
      }
    },
    _setData: function (key, value, animated) {
      $.widget.prototype._setData.apply(this, arguments);
      switch (key) {
      case 'orientation':
        this._detectOrientation();
        this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation);
        this._refreshValue(animated);
        break;
      case 'value':
        this._refreshValue(animated);
        break;
      }
    },
    _step: function () {
      var step = this.options.step;
      return step;
    },
    _value: function () {
      var val = this.options.value;
      if (val < this._valueMin()) val = this._valueMin();
      if (val > this._valueMax()) val = this._valueMax();
      return val;
    },
    _values: function (index) {
      if (arguments.length) {
        var val = this.options.values[index];
        if (val < this._valueMin()) val = this._valueMin();
        if (val > this._valueMax()) val = this._valueMax();
        return val;
      } else {
        return this.options.values;
      }
    },
    _valueMin: function () {
      var valueMin = this.options.min;
      return valueMin;
    },
    _valueMax: function () {
      var valueMax = this.options.max;
      return valueMax;
    },
    _refreshValue: function (animate) {
      var oRange = this.options.range,
        o = this.options,
        self = this;
      if (this.options.values && this.options.values.length) {
        var vp0, vp1;
        this.handles.each(function (i, j) {
          var valPercent = (self.values(i) - self._valueMin()) / (self._valueMax() - self._valueMin()) * 100;
          var _set = {};
          _set[self.orientation == 'horizontal' ? 'left' : 'bottom'] = valPercent + '%';
          $(this).stop(1, 1)[animate ? 'animate' : 'css'](_set, o.animate);
          if (self.options.range === true) {
            if (self.orientation == 'horizontal') {
              (i == 0) && self.range.stop(1, 1)[animate ? 'animate' : 'css']({
                left: valPercent + '%'
              },
              o.animate);
              (i == 1) && self.range[animate ? 'animate' : 'css']({
                width: (valPercent - lastValPercent) + '%'
              },
              {
                queue: false,
                duration: o.animate
              });
            } else {
              (i == 0) && self.range.stop(1, 1)[animate ? 'animate' : 'css']({
                bottom: (valPercent) + '%'
              },
              o.animate);
              (i == 1) && self.range[animate ? 'animate' : 'css']({
                height: (valPercent - lastValPercent) + '%'
              },
              {
                queue: false,
                duration: o.animate
              });
            }
          }
          lastValPercent = valPercent;
        });
      } else {
        var value = this.value(),
          valueMin = this._valueMin(),
          valueMax = this._valueMax(),
          valPercent = valueMax != valueMin ? (value - valueMin) / (valueMax - valueMin) * 100 : 0;
        var _set = {};
        _set[self.orientation == 'horizontal' ? 'left' : 'bottom'] = valPercent + '%';
        this.handle.stop(1, 1)[animate ? 'animate' : 'css'](_set, o.animate);
        (oRange == "min") && (this.orientation == "horizontal") && this.range.stop(1, 1)[animate ? 'animate' : 'css']({
          width: valPercent + '%'
        },
        o.animate);
        (oRange == "max") && (this.orientation == "horizontal") && this.range[animate ? 'animate' : 'css']({
          width: (100 - valPercent) + '%'
        },
        {
          queue: false,
          duration: o.animate
        });
        (oRange == "min") && (this.orientation == "vertical") && this.range.stop(1, 1)[animate ? 'animate' : 'css']({
          height: valPercent + '%'
        },
        o.animate);
        (oRange == "max") && (this.orientation == "vertical") && this.range[animate ? 'animate' : 'css']({
          height: (100 - valPercent) + '%'
        },
        {
          queue: false,
          duration: o.animate
        });
      }
    }
  }));
  $.extend($.ui.slider, {
    getter: "value values",
    version: "1.7.1",
    eventPrefix: "slide",
    defaults: {
      animate: false,
      delay: 0,
      distance: 0,
      max: 100,
      min: 0,
      orientation: 'horizontal',
      range: false,
      step: 1,
      value: 0,
      values: null
    }
  });
})(jQuery);

/**
 * Farbtastic Color Picker 1.2
 * Â© 2008 Steven Wittens
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */
jQuery.fn.farbtastic = function (callback) {
  jQuery.farbtastic(this, callback);
  return this;
};
jQuery.farbtastic = function (container, callback) {
  var container = jQuery(container).get(0);
  return container.farbtastic || (container.farbtastic = new jQuery._farbtastic(container, callback));
}
jQuery._farbtastic = function (container, callback) { // Store farbtastic object
  var fb = this; // Insert markup
  jQuery(container).html('<div class="farbtastic"><div class="color"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker"></div><div class="sl-marker marker"></div></div>');
  var e = jQuery('.farbtastic', container);
  fb.wheel = jQuery('.wheel', container).get(0); // Dimensions
  fb.radius = 84;
  fb.square = 100;
  fb.width = 194; // Fix background PNGs in IE6
  if (navigator.appVersion.match(/MSIE [0-6]\./)) {
    jQuery('*', e).each(function () {
      if (this.currentStyle.backgroundImage != 'none') {
        var image = this.currentStyle.backgroundImage;
        image = this.currentStyle.backgroundImage.substring(5, image.length - 2);
        jQuery(this).css({
          'backgroundImage': 'none',
          'filter': "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='" + image + "')"
        });
      }
    });
  }
  /**
   * Link to the given element(s) or callback.
   */
  fb.linkTo = function (callback) { // Unbind previous nodes
    if (typeof fb.callback == 'object') {
      jQuery(fb.callback).unbind('keyup', fb.updateValue);
    } // Reset color
    fb.color = null; // Bind callback or elements
    if (typeof callback == 'function') {
      fb.callback = callback;
    } else if (typeof callback == 'object' || typeof callback == 'string') {
      fb.callback = jQuery(callback);
      fb.callback.bind('keyup', fb.updateValue);
      if (fb.callback.get(0).value) {
        fb.setColor(fb.callback.get(0).value);
      }
    }
    return this;
  }
  fb.updateValue = function (event) {
    if (this.value && this.value != fb.color) {
      fb.setColor(this.value);
    }
  }
  /**
   * Change color with HTML syntax #123456
   */
  fb.setColor = function (color) {
    var unpack = fb.unpack(color);
    if (fb.color != color && unpack) {
      fb.color = color;
      fb.rgb = unpack;
      fb.hsl = fb.RGBToHSL(fb.rgb);
      fb.updateDisplay();
    }
    return this;
  }
  /**
   * Change color with HSL triplet [0..1, 0..1, 0..1]
   */
  fb.setHSL = function (hsl) {
    fb.hsl = hsl;
    fb.rgb = fb.HSLToRGB(hsl);
    fb.color = fb.pack(fb.rgb);
    fb.updateDisplay();
    return this;
  }
  /**
   * Retrieve the coordinates of the given event relative to the center
   * of the widget.
   */
  fb.widgetCoords = function (event) {
    var x, y;
    var el = event.target || event.srcElement;
    var reference = fb.wheel;
    if (typeof event.offsetX != 'undefined') { // Use offset coordinates and find common offsetParent
      var pos = {
        x: event.offsetX,
        y: event.offsetY
      }; // Send the coordinates upwards through the offsetParent chain.
      var e = el;
      while (e) {
        e.mouseX = pos.x;
        e.mouseY = pos.y;
        pos.x += e.offsetLeft;
        pos.y += e.offsetTop;
        e = e.offsetParent;
      } // Look for the coordinates starting from the wheel widget.
      var e = reference;
      var offset = {
        x: 0,
        y: 0
      }
      while (e) {
        if (typeof e.mouseX != 'undefined') {
          x = e.mouseX - offset.x;
          y = e.mouseY - offset.y;
          break;
        }
        offset.x += e.offsetLeft;
        offset.y += e.offsetTop;
        e = e.offsetParent;
      } // Reset stored coordinates
      e = el;
      while (e) {
        e.mouseX = undefined;
        e.mouseY = undefined;
        e = e.offsetParent;
      }
    } else { // Use absolute coordinates
      var pos = fb.absolutePosition(reference);
      x = (event.pageX || 0 * (event.clientX + jQuery('html').get(0).scrollLeft)) - pos.x;
      y = (event.pageY || 0 * (event.clientY + jQuery('html').get(0).scrollTop)) - pos.y;
    } // Subtract distance to middle
    return {
      x: x - fb.width / 2,
      y: y - fb.width / 2
    };
  }
  /**
   * Mousedown handler
   */
  fb.mousedown = function (event) { // Capture mouse
    if (!document.dragging) {
      jQuery(document).bind('mousemove', fb.mousemove).bind('mouseup', fb.mouseup);
      document.dragging = true;
    } // Check which area is being dragged
    var pos = fb.widgetCoords(event);
    fb.circleDrag = Math.max(Math.abs(pos.x), Math.abs(pos.y)) * 2 > fb.square; // Process
    fb.mousemove(event);
    return false;
  }
  /**
   * Mousemove handler
   */
  fb.mousemove = function (event) { // Get coordinates relative to color picker center
    var pos = fb.widgetCoords(event); // Set new HSL parameters
    if (fb.circleDrag) {
      var hue = Math.atan2(pos.x, -pos.y) / 6.28;
      if (hue < 0) hue += 1;
      fb.setHSL([hue, fb.hsl[1], fb.hsl[2]]);
    } else {
      var sat = Math.max(0, Math.min(1, -(pos.x / fb.square) + .5));
      var lum = Math.max(0, Math.min(1, -(pos.y / fb.square) + .5));
      fb.setHSL([fb.hsl[0], sat, lum]);
    }
    return false;
  }
  /**
   * Mouseup handler
   */
  fb.mouseup = function () { // Uncapture mouse
    jQuery(document).unbind('mousemove', fb.mousemove);
    jQuery(document).unbind('mouseup', fb.mouseup);
    document.dragging = false;
  }
  /**
   * Update the markers and styles
   */
  fb.updateDisplay = function () { // Markers
    var angle = fb.hsl[0] * 6.28;
    jQuery('.h-marker', e).css({
      left: Math.round(Math.sin(angle) * fb.radius + fb.width / 2) + 'px',
      top: Math.round(-Math.cos(angle) * fb.radius + fb.width / 2) + 'px'
    });
    jQuery('.sl-marker', e).css({
      left: Math.round(fb.square * (.5 - fb.hsl[1]) + fb.width / 2) + 'px',
      top: Math.round(fb.square * (.5 - fb.hsl[2]) + fb.width / 2) + 'px'
    }); // Saturation/Luminance gradient
    jQuery('.color', e).css('backgroundColor', fb.pack(fb.HSLToRGB([fb.hsl[0], 1, 0.5]))); // Linked elements or callback
    if (typeof fb.callback == 'object') { // Set background/foreground color
      jQuery(fb.callback).css({
        backgroundColor: fb.color,
        color: fb.hsl[2] > 0.5 ? '#000' : '#fff'
      }); // Change linked value
      jQuery(fb.callback).each(function () {
        if (this.value && this.value != fb.color) {
          this.value = fb.color;
        }
      });
    } else if (typeof fb.callback == 'function') {
      fb.callback.call(fb, fb.color);
    }
  }
  /**
   * Get absolute position of element
   */
  fb.absolutePosition = function (el) {
    var r = {
      x: el.offsetLeft,
      y: el.offsetTop
    }; // Resolve relative to offsetParent
    if (el.offsetParent) {
      var tmp = fb.absolutePosition(el.offsetParent);
      r.x += tmp.x;
      r.y += tmp.y;
    }
    return r;
  };
  /* Various color utility functions */
  fb.pack = function (rgb) {
    var r = Math.round(rgb[0] * 255);
    var g = Math.round(rgb[1] * 255);
    var b = Math.round(rgb[2] * 255);
    return '#' + (r < 16 ? '0' : '') + r.toString(16) + (g < 16 ? '0' : '') + g.toString(16) + (b < 16 ? '0' : '') + b.toString(16);
  }
  fb.unpack = function (color) {
    if (color.length == 7) {
      return [parseInt('0x' + color.substring(1, 3)) / 255, parseInt('0x' + color.substring(3, 5)) / 255, parseInt('0x' + color.substring(5, 7)) / 255];
    } else if (color.length == 4) {
      return [parseInt('0x' + color.substring(1, 2)) / 15, parseInt('0x' + color.substring(2, 3)) / 15, parseInt('0x' + color.substring(3, 4)) / 15];
    }
  }
  fb.HSLToRGB = function (hsl) {
    var m1, m2, r, g, b;
    var h = hsl[0],
      s = hsl[1],
      l = hsl[2];
    m2 = (l <= 0.5) ? l * (s + 1) : l + s - l * s;
    m1 = l * 2 - m2;
    return [this.hueToRGB(m1, m2, h + 0.33333), this.hueToRGB(m1, m2, h), this.hueToRGB(m1, m2, h - 0.33333)];
  }
  fb.hueToRGB = function (m1, m2, h) {
    h = (h < 0) ? h + 1 : ((h > 1) ? h - 1 : h);
    if (h * 6 < 1) return m1 + (m2 - m1) * h * 6;
    if (h * 2 < 1) return m2;
    if (h * 3 < 2) return m1 + (m2 - m1) * (0.66666 - h) * 6;
    return m1;
  }
  fb.RGBToHSL = function (rgb) {
    var min, max, delta, h, s, l;
    var r = rgb[0],
      g = rgb[1],
      b = rgb[2];
    min = Math.min(r, Math.min(g, b));
    max = Math.max(r, Math.max(g, b));
    delta = max - min;
    l = (min + max) / 2;
    s = 0;
    if (l > 0 && l < 1) {
      s = delta / (l < 0.5 ? (2 * l) : (2 - 2 * l));
    }
    h = 0;
    if (delta > 0) {
      if (max == r && max != g) h += (g - b) / delta;
      if (max == g && max != b) h += (2 + (b - r) / delta);
      if (max == b && max != r) h += (4 + (r - g) / delta);
      h /= 6;
    }
    return [h, s, l];
  } // Install mousedown handler (the others are set on the document on-demand)
  jQuery('*', e).mousedown(fb.mousedown); // Init color
  fb.setColor('#000000'); // Set linked elements/callback
  if (callback) {
    fb.linkTo(callback);
  }
}

