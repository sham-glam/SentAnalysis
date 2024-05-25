$(document).ready(function() {
    alert("Vous êtes dans la page de inputSentence");

    $("#view_added").click(function(event) {
        $('#inputSentence').hide();
        $('#delete_phrases').show();
    });

    $("#delete_phrases").click(function(event) {
        // alert("Vous avez cliqué sur le bouton delete_phrases");
        $('#inputSentence').hide();
    });

    $("#resume").click(function(event) {
        $('#inputSentence').hide();
    });

    $("#view_added").click(function(event) {
        $('#inputSentence').hide();
    });
});
