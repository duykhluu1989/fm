$('.InputForNumber').keyup(function() {
    $(this).val(formatNumber($(this).val(), '.'));
});

function formatNumber(number, delimiter)
{
    if(number != '')
    {
        number = number.split(delimiter).join('');

        var formatted = '';
        var sign = '';

        if(number < 0)
        {
            number = -number;
            sign = '-';
        }

        while(number >= 1000)
        {
            var mod = number % 1000;

            if(formatted != '')
                formatted = delimiter + formatted;
            if(mod == 0)
                formatted = '000' + formatted;
            else if(mod < 10)
                formatted = '00' + mod + formatted;
            else if(mod < 100)
                formatted = '0' + mod + formatted;
            else
                formatted = mod + formatted;

            number = parseInt(number / 1000);
        }

        if(formatted != '')
            formatted = sign + number + delimiter + formatted;
        else
            formatted = sign + number;

        return formatted;
    }

    return '';
}

$('.DatePicker').datepicker({
    changeYear: true,
    changeMonth: true,
    dateFormat: 'yy-mm-dd'
});