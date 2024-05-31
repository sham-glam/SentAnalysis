$(document).ready(function() {
    // alert("Vous êtes dans la page d'accueil");
    $('#darkModeToggle').click(function() {
        $('body').toggleClass('dark-mode');
        $('#darkModeToggle').hide();
        $('#lightModeToggle').show();
    });


    $('#lightModeToggle').click(function() {
        $('body').removeClass('dark-mode');
        $('#lightModeToggle').hide();
        $('#darkModeToggle').show();
    });
});
