$(document).ready(function() {
    alert("Vous êtes dans la page de l\'analyse des utilisateurs");
    console.log( "document loaded" );

    $('#annotate').click(function(event) {
        alert("Vous allez être redigé vers un formulaire d'annotation");
        $('#explainTask').hide();
        fetchDBSentences(); // rammène les phrases de la base de données via ajax
    });

    $('#submitAnnotation').click(function(event) {
        alert("La base de données va être mise à jour");
        $('#annotationTableForm').hide();
        $('#annotationTableBody').hide();
        updateDBSentences(); // met à jour la base de données avec les annotations

    });

    // 
    function fetchDBSentences() {
        $.ajax({
            url: 'php/fetchUserAnalysis.php',
            method: 'GET', 
            dataType: 'json',
            success: function(data) {
                console.log('Data fetched successfully:', data);
                populateForm(data);
            },
            error: function(error) {
                console.error('Error fetching data from the database:', error);
            }
        });
    }
    // shuffle function -> source stackoverflow
    function shuffle(array) {
        let currentIndex = array.length;
      
        // While there remain elements to shuffle...
        while (currentIndex != 0) {
      
          // Pick a remaining element...
          let randomIndex = Math.floor(Math.random() * currentIndex);
          currentIndex--;
      
          // And swap it with the current element.
          [array[currentIndex], array[randomIndex]] = [
            array[randomIndex], array[currentIndex]];
        }
      }
    // fonction qui peuple  dynamiquement le formulaire pour annotation 
    function populateForm(sentences) {
        var form = $('#annotationTableBody');
        form.empty();
    
        shuffle(sentences); 
        // Creation dynamique des lignes du tableau dans le html
        sentences.slice(0, 5).forEach(function(sentence) {
            var row = $('<tr></tr>');
            // row.append(`<td>phrase_${sentence.id}</td>`);
            row.append(`<td>${sentence.phrase}</td>`);
            row.append(`<td>
                            <select class="form-control" id="emotion_${sentence.id}" name="emotion_${sentence.id}">
                                <option value=""></option>
                                <option value="heureux">Heureux</option>
                                <option value="colere">Colère</option>
                                <option value="triste">Triste</option>
                                <option value="neutre">Neutre/Inconnu</option>
                            </select>
                        </td>`);
            form.append(row); // Append the row to the table body
        });
        
        $('#annotationTableForm').show();
    }

    // fonction met à jour (augmente score) la base de données avec les annotations
    function updateDBSentences() {
        $('#annotationTableForm').hide();

        var annotations = [];
        var annotatedIds = []; // Array stocke les ids des phrases annotées
    
        var tableBody = $('#annotationTableBody');
        
        tableBody.find('tr').each(function(index, row) {
            var sentenceId = $(row).find('select').attr('id').split('_')[1]; // récupère l'id de la phrase pour insertion
            var emotion = $(row).find('select').val(); // select contient nom de col bdd
            if (emotion) {
                annotations.push({ id: sentenceId, emotion: emotion });
                annotatedIds.push(sentenceId); // scokage des ids des phrases annotées
            }
        });
    
        $.ajax({
            url: 'php/updateUserAnalysis.php', 
            method: 'POST',
            data: { annotations: annotations },
            success: function(response) {
                console.log('Data updated successfully:', response);
                if (annotatedIds.length > 0) {
                    showCurrentAnnotations(annotatedIds); 
                }else{
                    // $('#annotationTableForm').hide();
                    $('#noAnnotations').show();
                    // $('#explainTask').show();
                    
                }
            },
            error: function(error) {
                console.error('Error updating data:', error);
            }
        });
    }
    

    // on montre tous les résultats des phrases annotées
    function showCurrentAnnotations(annotatedIds) {
        $.ajax({
            url: 'php/fetchCurrentAnnotations.php', 
            method: 'POST',
            data: { ids: annotatedIds }, // Send the annotated IDs to fetch relevant annotations
            success: function(data) {
                $('#currentAnnotations').html(data);
                // $('#merci').hide();
                $('#currentAnnotationsContainer').show(); 

            },
            error: function(error) {
                console.error('Error fetching current annotations:', error);
            }
        });
    }

    });

