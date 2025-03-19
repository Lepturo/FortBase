<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../../css/trainings.css">

<?php
$trainingService = new TrainingService();
$themeService = new ThemeService();


$showForm = isset($_POST["form-btn"]);
$showDeleteConfirmation = false;
$deleteTrainingId = null;


// add new Training
if (isset($_POST["title"]) && isset($_POST["participants"]) && isset($_POST["trainer"]) && isset($_POST["theme"]) && isset($_POST["startDate"]) && isset($_POST["endDate"])) {
    $theme = $themeService->getById((int) $_POST["theme"]);
    $trainingService->add(new Training(0, $_POST["title"], $theme, $_POST["trainer"], new DateTime($_POST["startDate"]), new DateTime($_POST["endDate"])));
    $trainingId = $trainingService->getLastInsertedTrainingsId()[0]["p_id"];

    $participants = $_POST["participants"];

    for ($i = 0; $i < count($participants); $i++) {
        // add participant to training for each dropdown
        $trainingService->addParticipant($trainingId, $participants[$i]);
    }
    $showForm = false;
}

// if(isset($_POST["close-btn"])) {
//     $showForm = false;
//     $showDeleteConfirmation = false;
// }

if (isset($_POST["delete-btn"])) {
    $showDeleteConfirmation = true;
    $deleteTrainingId = $_POST["delete-btn"];
}

if (isset($_POST["confirm-delete-btn"])) {
    //$themeService->delete($_POST["confirm-delete-btn"]);
    $trainingService->delete($_POST["confirm-delete-btn"]);
}

// Search functionality
$searchQuery = isset($_POST["search"]) ? $_POST["search"] : '';
$trainings = $searchQuery ? searchByQuery($searchQuery) : $trainingService->getAll();
function searchByQuery($query)
{
    $trainingService = new TrainingService();
    if (!$trainingService) {
        return []; // Falls $trainingService nicht existiert, wird ein leerer Array zurückgegeben
    }
    
    $query = strtolower($query);
    
    // return array_filter($trainingService->getAll(), function ($training) use ($query) {
    //     return strpos(strtolower($training->getTitle()), $query) !== false ||
    //            strpos(strtolower($training->getTheme()->getName()), $query) !== false;
    // });
    return array_filter($trainingService->getAll(), function ($training) use ($query) {
        return isQueryInTraining($training, $query, );
    });
}

function isQueryInTraining(Training $training, $query, $trainingService = new TrainingService()) {
    return strpos(strtolower($training->getTitle()), $query) !== false ||
           strpos(strtolower($training->getTheme()->getName()), $query) !== false || 
           strpos(strtolower($training->getTrainer()), $query) !== false ||
           strpos(strtolower($training->getStartDate()->format('Y-m-d H:i:s')), $query) !== false ||
           strpos(strtolower($training->getEndDate()->format('Y-m-d H:i:s')), $query) !== false || 
           isQueryInParticipants($trainingService->getParticipantsByTrainingId($training->getId()), $query);
}

function isQueryInParticipants($participants, $query) {
    foreach($participants as $participant) {
        if(strpos($participant->getUsername(), $query) !== false) {
            return true;
        }
    }
    return false;
}

?>

<body>
    <!-- Suchleiste -->
    <div class="search-wrapper">
        <form method="POST" action="#" class="search-form">
            <input type="text" name="search" class="search_input" placeholder="Training suchen..."
                value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" name="search-btn" class="search_button">Suchen</button>
        </form>
    </div>
    <!-- Popup Form to add a Theme -->
    <?php if ($showForm): ?>
        <div class="overlay">
            <div class="theme-form">
                <form method="POST" action="">
                    <div class="form-header">
                        <button type="submit" name="close-btn" class="close-btn">&times;</button>
                        <input type="hidden" name="close-form" value="true">
                    </div>
                </form>
                <form method="POST" action="#">
                    <div class="form-body">
                        <div class="form-body-info">
                            Hier können Sie ein neues Training hinzufügen.
                        </div>
                        <div class="form-body-inputs">
                            <div class="form-field">
                                <label>Titel</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-field" id="participant-container">
                                <label>Teilnehmer</label>
                                <button type="button" class="add-dropdown-btn" onclick="addDropDown()">+</button>
                                <div>
                                    <?php
                                    echo "<select name='participants[]'>";
                                    echo "<option value='' disabled selected>Bitte auswählen</option>";
                                    $participants = $trainingService->getAllUsers();
                                    foreach ($participants as $participant) {
                                        echo "<option value='" . $participant->getUsername() . "'>" . $participant->getUsername() . "</option>";
                                    }
                                    echo "</select>";
                                    ?>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Trainer</label>
                                <input type="text" name="trainer" required>
                            </div>
                            <div class="form-field">
                                <label>Thema</label>
                                <select name="theme">
                                    <?php
                                    $themes = $themeService->getAll();
                                    foreach ($themes as $theme) {
                                        echo "<option value='" . $theme->getId() . "'>" . $theme->getName() . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Startdatum</label>
                                <input type="datetime-local" name="startDate" max="9999-12-31" required>
                            </div>
                            <div class="form-field">
                                <label>Enddatum</label>
                                <input type="datetime-local" name="endDate" max="9999-12-31" required>
                            </div>
                        </div>
                        <div class="form-submit">
                            <button type="submit" name="form-btn" class="add-btn">Hinzufügen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($showDeleteConfirmation): ?>
        <div class="overlay">
            <div class="confirmation-popup theme-form">
                <form method="POST" action="#">
                    <div class="popup-header">
                        <button type="submit" name="close-btn" class="close-btn">&times;</button>
                        <input type="hidden" name="close-form" value="true">
                    </div>
                    <div class="popup-body">
                        <p>Wollen Sie das Training "<?php echo $trainingService->getById($deleteTrainingId)->getTitle(); ?>" wirklich löschen?</p>
                        <button type="submit" name="confirm-delete-btn" value="<?php echo $deleteTrainingId; ?>" class="close-btn">Ja</button>
                        <button type="submit" name="close-btn" class="close-btn">Nein</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- scrollable list of themes -->
    <div class="list-container">
        <div class="list-header">
            <div class="list-item">Titel</div>
            <div class="list-item">Teilnehmer</div>
            <div class="list-item">Trainer</div>
            <div class="list-item">Thema</div>
            <div class="list-item">Startdatum</div>
            <div class="list-item">Enddatum</div>
        </div>
        <div class="list-body">
            <?php
            function printParticipants(int $id)
            {
                $trainingService = new TrainingService();
                $participants = $trainingService->getParticipantsByTrainingId($id);
                $output = "";
                foreach ($participants as $participant) {
                    // echo "<script>console.log('" . print_r($participant) . "');</script>";
                    $output .= "<li>- " . $participant->getUsername() . "</li>";
                }
                return $output;
            }

            foreach ($trainings as $training) {
                echo "<div class='list-row'>
                            
                            <div class='list-item'>" . $training->getTitle() . "</div>
                            <div class='list-item'><ul>" .
                    (count($trainingService->getParticipantsByTrainingId($training->getId())) == 0 ?
                        "keine Teilnehmer" : printParticipants($training->getId())) .
                    "</ul></div>
                            <div class='list-item'>" . $training->getTrainer() . "</div>
                            <div class='list-item'>" . $training->getTheme()->getName() . "</div>
                            <div class='list-item'>" . $training->getStartDate()->format('Y-m-d H:i:s') . "</div>
                            <div class='list-item'>" . $training->getEndDate()->format('Y-m-d H:i:s') . "</div>
                            <div class='list-btn'>
                                <form method='POST' action='#' style='display: inline;'>
                                    <button type='submit' class='delete-btn' name='delete-btn' value='" . $training->getId() . "' hidden></button>
                                    <img src='images/icons/trash.png' width='25px' height='25px' onclick='this.previousElementSibling.click();'>
                                </form>
                            </div>
                        </div>";
            }
            ?>
        </div>
    </div>
    <!-- button to add open the popup form -->
    <div class="add">
        <form method="POST" action="">
            <button type="submit" name="form-btn" class="add-btn">Hinzufügen</button>
        </form>
    </div>

    <script>
        // fetch all possible participants
        async function fetchParticipants() {
            const response = await fetch("php/services/fetchParticipants.php");

            if (!response.ok) {
                console.log("Fehler beim laden der Teilnehmer");
            }

            const participants = await response.json();
            return participants;
        }


        async function addDropDown() {
            const participant = await fetchParticipants();
            const container = document.getElementById("participant-container");
            const newDropDown = document.createElement("div");

            const select = document.createElement("select");
            select.name = "participants[]";
            select.required = true;

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            defaultOption.textContent = 'Bitte auswählen';
            select.appendChild(defaultOption);


            for (const p of participant) {
                const option = document.createElement('option');
                option.value = p.p_username;
                option.textContent = p.p_username;
                select.appendChild(option);
            }

            newDropDown.appendChild(select);
            container.appendChild(newDropDown);
        }
    </script>
</body>

</html>

<!--
  ROFL:ROFL:ROFL:ROFL
         _^___
 L    __/   [] \
LOL===__        \
 L      \________]
         I   I
        --------/ 
-->