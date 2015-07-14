function customDataTableFunctions(){
    $('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
    });

    $('input.form-filter').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('.filter-submit').click();
        }
    });

    $('select.form-filter').on('change',function(event){
        if($(this).val() != '-1'){
            $('.filter-submit').click();
        }
    });
    
    $('.date-picker input').on('change',function(){
        if($(this).val() != ' '){
            $('.filter-submit').click();
        }
    });
}

$.extend($.inputmask.defaults, {
            'autounmask': true
        });

$(".timePicker").inputmask({
    "mask": "09:99:99"
});

$(".decimalMask").inputmask({
    "mask": "99.99"
});