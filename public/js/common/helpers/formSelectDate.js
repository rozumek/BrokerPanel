(function(){
    $(function(){                
        $('.form-date-select select').change(function(){
            var day = $(this).parent().children('select[id*="day"]').val();
            var month = $(this).parent().children('select[id*="month"]').val();
            var year = $(this).parent().children('select[id*="year"]').val();
            
            if(day !== '' && month !== '' && year !== ''){                
                $(this).siblings('input[type="hidden"]').val(new Date().toStdString(year, month, day));
            }else{
                $(this).siblings('input[type="hidden"]').val('');
            }
        });
    });
})();