<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire PHP</title>
</head>
<body>
    <form method="post" action="page_suivante.php">
        <label for="menu1">Premier menu :</label>
        <select name="menu1" id="menu1" onchange="updateMenu2(this.value)">
            <option value="entree">Entrée</option>
            <option value="plat">Plat</option>
            <option value="dessert">Dessert</option>
        </select>
        <br>
        <label for="menu2">Deuxième menu :</label>
        <select name="menu2" id="menu2">
            <!-- Les options seront ajoutées dynamiquement en JavaScript -->
        </select>
        <br>
        <input type="submit" value="Page suivante">
    </form>

    <script>
        function updateMenu2(value) {
            var menu2 = document.getElementById("menu2");
            menu2.innerHTML = "";

            if (value === "entree") {
                addOptions(menu2, ["oeuf", "pâté", "poireaux"]);
            } else if (value === "plat") {
                addOptions(menu2, ["cassoulet", "steack", "quiche"]);
            } else if (value === "dessert") {
                addOptions(menu2, ["yaourt", "flan", "tarte"]);
            }
        }

        function addOptions(select, options) {
            for (var i = 0; i < options.length; i++) {
                var option = document.createElement("option");
                option.text = options[i];
                option.value = options[i];
                select.add(option);
            }
        }

        // Appel initial pour mettre à jour le menu 2 en fonction de la valeur initiale du menu 1
        updateMenu2(document.getElementById("menu1").value);
    </script>
</body>
</html>
