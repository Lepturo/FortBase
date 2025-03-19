
<link rel="stylesheet" href="../../css/themes.css">

<?php
$themeService = new ThemeService();

$showForm = isset($_POST["form-btn"]);
$showDeleteConfirmation = false;
$deleteThemeId = null;

// add new Theme
if (isset($_POST["name"]) && isset($_POST["description"])) {
    $themeService->add(new Theme(0, $_POST["name"], $_POST["description"]));
    $showForm = false;
}

if (isset($_POST["delete-btn"])) {
    $showDeleteConfirmation = true;
    $deleteThemeId = $_POST["delete-btn"];
}

if (isset($_POST["confirm-delete-btn"])) {
    $themeService->delete($_POST["confirm-delete-btn"]);
}

// Search functionality
$searchQuery = isset($_POST["search"]) ? $_POST["search"] : '';
$themes = $searchQuery ? searchByNameOrDescription($searchQuery) : $themeService->getAll();

function searchByNameOrDescription($query)
{
    $themeService = new ThemeService();
    $query = strtolower($query);
    return array_filter($themeService->getAll(), function ($theme) use ($query) {
        return strpos(strtolower($theme->getName()), $query) !== false ||
            strpos(strtolower($theme->getDescription()), $query) !== false;
    });
}

?>

<body>
    <!-- Suchleiste -->
    <div class="search-wrapper">
        <form method="POST" action="#" class="search-form">
            <input type="text" name="search" class="search_input" placeholder="Themen suchen..." 
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
                    </div>
                </form>
                <form method="POST" action="#">
                    <div class="form-body">
                        <div class="form-body-info">
                            Hier können Sie ein neues Thema hinzufügen.
                        </div>
                        <div class="form-body-inputs">
                            <div class="form-field">
                                <label>Bezeichnung</label>
                                <input type="text" name="name" required>
                            </div>
                            <div class="form-field">
                                <label>Beschreibung</label>
                                <textarea name="description" required></textarea>
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
                    </div>
                    <div class="popup-body">
                        <p>Wollen Sie das Thema "<?php echo $themeService->getById($deleteThemeId)->getName(); ?>" wirklich löschen?</p>
                        <button type="submit" name="confirm-delete-btn" value="<?php echo $deleteThemeId; ?>" class="close-btn">Ja</button>
                        <button type="submit" name="close-btn" class="close-btn">Nein</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- scrollable list of themes -->
    <div class="list-container">
        <div class="list-header">
            <div class="list-item">Bezeichnung</div>
            <div class="list-item">Beschreibung</div>
        </div>
        <div class="list-body">
            <?php
            foreach ($themes as $theme) {
                echo "<div class='list-row'>
                            <div class='list-item'>" . $theme->getName() . "</div>
                            <div class='list-item'>" . $theme->getDescription() . "</div>
                            <div class='list-btn'>
                                <form method='POST' action='#' style='display: inline;'>
                                    <button type='submit' class='delete-btn' name='delete-btn' value='" . $theme->getId() . "' hidden></button>
                                    <img src='images/icons/trash.png' width='25px' height='25px' onclick='this.previousElementSibling.click();'>
                                </form>
                            </div>
                        </div>";
            }
            ?>
        </div>
    </div>

    <!-- button to open the popup form -->
    <div class="add">
        <form method="POST" action="#">
            <button type="submit" name="form-btn" class="add-btn">Neues Thema Hinzufügen</button>
        </form>
    </div>

    
</body>

<br><br>