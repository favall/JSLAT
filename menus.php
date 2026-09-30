<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu déroulant PHP</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<form>
    <label for="menuA">Sélectionnez A, B ou C :</label>
    <select name="menuA" id="menuA">
                                    <optgroup label="Ski alpin">
                                        <option value="Skis Alpin">Skis Alpin</option>
                                        <option value="Chaussures Alpin">Chaussures Alpin</option>
                                    </optgroup>
                                    <optgroup label="Snowboard">
                                        <option value="Snowboard (avec fixation)">Snowboard (avec fixation)</option>
                                        <option value="Snowboard (planche nue)">Snowboard (planche nue)</option>
                                        <option value="Chaussures Snowboard">Chaussures Snowboard</option>
                                    </optgroup>
                                    <optgroup label="Ski de rando">
                                        <option value="Skis rando">Skis rando</option>
                                        <option value="Chaussures Rando">Chaussures Rando</option>
                                    </optgroup>
                                    <optgroup label="Ski de fond">
                                        <option value="Skis de fond">Skis de fond</option>
                                        <option value="Chaussures fond">Chaussures fond</option>
                                    </optgroup>
                                    <optgroup label="Ski télémark">
                                        <option value="Skis télémark">Skis télémark</option>
                                        <option value="Chaussures télémark">Chaussures télémark</option>
                                    </optgroup>
                                    <optgroup label="Vêtements enfant">
                                        <option value="Combinaison Ski enfant">Combinaison Ski enfant</option>
                                        <option value="Ensemble Pantalon + Veste">Ensemble Pantalon + Veste</option>
                                        <option value="Veste Ski Enfant">Veste Ski Enfant</option>
                                        <option value="Pantalon Ski Enfant">Pantalon Ski Enfant</option>
                                    </optgroup>
    </select>

    <label for="menuB">Sélectionnez 1 à 5 pour A ou C, 6 à 10 pour B :</label>
    <select name="menuB" id="menuB">
        <!-- Options dynamiquement générées ici via JavaScript -->
    </select>
</form>

<script>
    $(document).ready(function () {
        // Lorsque la valeur de menuA change
        $('#menuA').change(function () {
            // Récupérer la valeur sélectionnée dans menuA
            var selectedOptionA = $(this).val();

            // Effectuer une requête AJAX pour récupérer les options correspondantes pour menuB
            $.ajax({
                type: 'POST',
                url: 'ajax_handler.php', // Remplacez ceci par le chemin du fichier PHP qui génère les options
                data: {menuA: selectedOptionA},
                success: function (data) {
                    // Mettre à jour le contenu de menuB avec les options générées par le serveur
                    $('#menuB').html(data);
                }
            });
        });

        // Déclencher le changement initial pour afficher les options correctes au chargement de la page
        $('#menuA').trigger('change');
    });
</script>

</body>
</html>
