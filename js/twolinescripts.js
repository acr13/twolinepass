$(document).ready(function()
{
    $("table tr").on('click', function()
    {
        $("table tr").each(function()
        {
            if ($(this).hasClass('success'))
            {
                $(this).toggleClass('success');
            }
        });
        
        $(this).toggleClass('success');
    });
});