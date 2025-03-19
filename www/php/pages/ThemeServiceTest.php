
<html>
    <head></head>
    <body>
        <form method="POST" action="#">
            <label for="Mike">ID:</label>
            <input type="text" name="Mike">
            <label for="Actually">Name:</label>
            <input type="text" name="Actually">
            <label for="Won">Description:</label>
            <input type="text" name="Won">
            <input type="submit">
        </form>
        <?php
            $themeService = new ThemeService();

            if(isset($_POST["Mike"]) && isset($_POST["Actually"]) && isset($_POST["Won"])) {
                $themeService->add(new Theme((int)$_POST["Mike"], $_POST["Actually"], $_POST["Won"]));
            }

            $themes = $themeService->getAll();

            foreach($themes as $theme) {
                echo 
                "<div>
                    <div>" . $theme->getName() . "</div>
                    <div>ID: " . $theme->getId() . "</div>
                    <div>Beschreibung: " . $theme->getDescription() . "</div>
                    <form action='' method='POST'>
                        <input type='submit' value='". $theme->getId() ."' name='delete'>
                    </form>
                </div><br>";
            }

            if(isset($_POST["delete"])) {
                $themeService->delete($_POST["delete"]);  
                //reloadPage(); 
            }

            //function reloadPage() {     echo '<script type="text/javascript">var count = 0; if(count==0) {window.location.reload(); count++;}</script>'; }
        ?>
    </body>
</html>
