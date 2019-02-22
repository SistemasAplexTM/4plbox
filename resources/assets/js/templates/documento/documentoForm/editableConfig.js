(function ($) {
    "use strict";

    var Address = function (options) {
        this.init('address', options, Address.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Address, $.fn.editabletypes.abstractinput);

    $.extend(Address.prototype, {
        /**
        Renders input from tpl

        @method render()
        **/
        render: function() {
           this.$input = this.$tpl.find('input');
        },

        /**
        Default method to show value in element. Can be overwritten by display option.

        @method value2html(value, element)
        **/
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return;
            }
            var html = 'Vol=' + $('<div>').text(value.largo).html() + 'x' + $('<div>').text(value.ancho).html() + 'x' + $('<div>').text(value.alto).html();
            $(element).html(html);
        },

        /**
        Gets value from element's html

        @method html2value(html)
        **/
        html2value: function(html) {
          /*
            you may write parsing method to get value by element's html
            e.g. "Moscow, st. Lenina, bld. 15" => {city: "Moscow", street: "Lenina", building: "15"}
            but for complex structures it's not recommended.
            Better set value directly via javascript, e.g.
            editable({
                value: {
                    city: "Moscow",
                    street: "Lenina",
                    building: "15"
                }
            });
          */
          // console.log('asdf: '+ html);
          return null;
        },

       /**
        Converts value to string.
        It is used in internal comparing (not for sending to server).

        @method value2str(value)
       **/
       value2str: function(value) {
           var str = '';
           if(value) {
               for(var k in value) {
                   str = str + k + ':' + value[k] + ';';
               }
           }
           return str;
       },

       /*
        Converts string to value. Used for reading value from 'data-value' attribute.

        @method str2value(str)
       */
       str2value: function(str) {
           /*
           this is mainly for parsing value defined in data-value attribute.
           If you will always set value by javascript, no need to overwrite it
           */
           return str;
       },

       /**
        Sets value of input.

        @method value2input(value)
        @param {mixed} value
       **/
       value2input: function(value) {
           if(!value) {
             return;
           }else{
            value = value.split(',');
           }
           this.$input.filter('[name="largo"]').val(value[0]);
           this.$input.filter('[name="ancho"]').val(value[1]);
           this.$input.filter('[name="alto"]').val(value[2]);
       },

       /**
        Returns value of input.

        @method input2value()
       **/
       input2value: function() {
           return {
              largo: this.$input.filter('[name="largo"]').val(),
              ancho: this.$input.filter('[name="ancho"]').val(),
              alto: this.$input.filter('[name="alto"]').val()
           };
       },

        /**
        Activates input: sets focus on the first field.

        @method activate()
       **/
       activate: function() {
            this.$input.filter('[name="largo"]').focus();
       },

       /**
        Attaches handler to submit form in case of 'showbuttons=false' mode

        @method autosubmit()
       **/
       autosubmit: function() {
           this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
           });
       }
    });

    Address.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-address"><label><span>Length: </span><input type="number" name="largo" class="input-small form-control" autocomplete="off"></label></div>'+
             '<div class="editable-address"><label><span>Width:  </span><input type="number" name="ancho" class="input-small form-control" autocomplete="off"></label></div>'+
             '<div class="editable-address"><label><span>Heigth: </span><input type="number" name="alto" class="input-small form-control" autocomplete="off"></label></div>',

        inputclass: ''
    });

    $.fn.editabletypes.address = Address;

}(window.jQuery));
