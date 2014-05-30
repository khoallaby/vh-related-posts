jQuery( document ).ready(function($) {
    var el = $('input[type="checkbox"][name="vhrp_posts[]"]');
    el.click(function() {
        var checked = el.find(':checked');
        var bol = checked.length >= 4;
        el.not(":checked").attr("disabled", bol);
    });
});