(function($){
    $(function(){
        /**
         * System message        
         */
        $(".message").click(function () {
            $(this).fadeOut();
        });
        
        /**
         * Inject menu position
         */        
        if($('.navigation-inject').length > 0){
            var newPosition = $('<li>').append($('.navigation-inject'));
            $('#navigation ul li.active ul').append(newPosition);
        }
    });
})(jQuery);

/**
 *===========================STD EXTENSIONS======================================
 */

/**
* 
* @param {int} from
* @param {int} to
* @returns {this}
*/
Array.prototype.unset = function(from, to) {
   var rest = this.slice((to || from) + 1 || this.length);            
   this.length = from < 0 ? this.length + from : from;
   return this.push.apply(this, rest);
};

/**
* 
* @param {String|Int|Float} element
* @returns {Boolean | Int}
*/
Array.prototype.inArray = function(element){  
   var elementFound = false;
   var index = 0;

   this.forEach(function(item){
       if(item == element){
           elementFound = index; 
           return;
       }
       index++;
   });

   return elementFound;
};

/**
 * 
 * @returns {String}
 */
Date.prototype.toStdString = function(year, month, day){
    return ''+year+'-'+((month > 9)?month:('0'+month))+'-'+((day > 9)?day:('0'+day));
};

/**
 *================================Jquery Plugins================================
 */
(function($){
    /**
     * Plugin returns values of collection
     */
    $.fn.vals = function(options){
        var defaults = {parseInt : false};
        options = $.extend({}, defaults, options);
        
        var array = new Array();

        $(this).each(function(i,n){
            var value = $(n).val();            
            if(options.parseInt === true){
                value = parseInt(value);
            }
            
            array[i] = value;
        });
        
        return array;
    }

    /**
     * Hiding/showing box plugin
     */
    $.fn.toogleContent = function(options) {
        var defaults = {
            'closed' : false
        };
        
        options = $.extend({}, defaults, options);
        
        return this.each(function() {
            var box = $(this);
            var content = $(this).parent().next('.content');
            
            if(box.hasClass('open')){
                content.hide();
            }else if(box.hasClass('close')){
                content.show();
            }
            
            if(options.closed === true){
                content.hide();
            }
            
            box.click(function(){
                content.slideToggle(500);
            });
        });
    };    
})(jQuery);