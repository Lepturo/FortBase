
<html>
    <head></head>
    <body>
        <form method="POST" action="#">
            <div><label for="id">ID:</label></div>
            <input type="text" name="id"><br>
            <div><label for="theme-id">Theme-ID:</label></div>
            <input type="text" name="theme-id"><br>
            <div><label for="title">Titel:</label></div>
            <input type="text" name="title"><br>
            <div><label for="trainer">Trainer:</label></div>
            <input type="text" name="trainer"><br>
            <div><label for="startDate">startDate:</label></div>
            <input type="datetime-local" name="startDate"><br>
            <div><label for="endDate">endDate:</label></div>
            <input type="datetime-local" name="endDate"><br>
            <input type="submit">
        </form>
        <?php
            $trainingService = new TrainingService();
            $themeService = new ThemeService();
            
            if(isset($_POST["id"]) && isset($_POST["theme-id"]) && isset($_POST["trainer"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["title"])) {
                $theme = $themeService->getById((int) $_POST["theme-id"]);
                $trainingService->add(new Training((int)$_POST["id"], $_POST["title"], $theme, $_POST["trainer"], new DateTime($_POST["startDate"]), new DateTime($_POST["endDate"])));

            }

            if(isset($_POST["testDate"])) {
                $testDate = new DateTime($_POST["testDate"]);
            }

            $trainings = $trainingService->getAll();


            if(isset($testDate)) {
                echo "" . $testDate->format('Y-m-d H:i:s');
                echo "" . $testDate->getTimestamp();
                //$testD = new DateTime($testDate->getTimestamp());
                //echo "" . $testD; 
            }

            foreach($trainings as $training) {
                echo 
                "<div>
                    <div>" . $training->getTitle() . "</div>
                    <div>ID: " . $training->getId() . "</div>
                    <div>Theme-ID: " . $training->getTheme()->getId() . "</div>
                    <div>Trainer: " . $training->getTrainer() . "</div>
                    <div>Startdatum: " . $training->getStartDate()->format('Y-m-d H:i:s') . "</div>
                    <div>Enddatum: " . $training->getEndDate()->format('Y-m-d H:i:s') . "</div>
                    <form action='' method='POST'>
                        <input type='submit' value='". $training->getId() ."' name='delete'>
                    </form>
                </div><br>";
            }

            // $themes = $themeService->getAll();

            // foreach($themes as $theme) {
            //     echo 
            //     "<div>
            //         <div>" . $theme->getName() . "</div>
            //         <div>ID: " . $theme->getId() . "</div>
            //         <div>Beschreibung: " . $theme->getDescription() . "</div>
            //         <form action='' method='POST'>
            //             <input type='submit' value='". $theme->getId() ."' name='delete'>
            //         </form>
            //     </div><br>";
            // }

            if(isset($_POST["delete"])) {
                $trainingService->delete($_POST["delete"]);  
                //reloadPage(); 
            }

            //function reloadPage() {     echo '<script type="text/javascript">var count = 0; if(count==0) {window.location.reload(); count++;}</script>'; }
        ?>
    </body>
</html>
