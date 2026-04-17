$(document).ready(function(){
    // Remove input value on click
    $('.input-group .input-group-text .fa-times').on("click", function() {
        $(this).parents('.input-group').find('input').val("");
    });
});
