(function($){
    $(function(){
        /**
         * Stock Order form submition
         */
        $('#stockOrderForm').submit(function(){            
            var submitForm = true;

            if($('#stopLossValue').val() == '' && $('#stockOrderForm input[name="type"]').val() != 2){
                submitForm = confirm($('#stopLossValue').attr('warning'))
            }
            
            return submitForm
        });        
    });
})(jQuery);
