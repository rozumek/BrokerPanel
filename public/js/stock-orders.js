(function($){
    $(function(){
        /**
         * Stock Order form submition
         */
        $('#stockOrderForm').submit(function(){
            var submitForm = true;

            if($('#stopLossValue').val() == '' && $('#stockOrderForm input[name="type"]').val() != 2){
                submitForm = confirm($('#stopLossValue').attr('warning'));
            }

            return submitForm
        });

        /**
         * Register Order form limit value processing
         */
        $('input[name="limit_value_type"]').change(function(){
            if ($(this).val() == 2) {
                $('#limit_value').attr('disabled', true);
            } else {
                $('#limit_value').attr('disabled', false);
            }
        });
        $('input[name="limit_value_type"]:checked').trigger('change');
    });
})(jQuery);
