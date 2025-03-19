<?php
session_start();
(new FortBase);
class FortBase {
    private string $jsonModelsPath = "json/models.json";
    private string $jsonServicesPath = "json/services.json";
    private string $jsonPagesPath = "json/pages.json";
    private string $jsonConstantsPath = "json/constants.json";
    
    private array $jsonModels;
    private array $jsonServices;
    private array $jsonPages;
    private array $jsonConstants;

    private $user;

    private Html $html;

    public function __construct() {
        $jsonPagesContent = file_get_contents(filename: $this->jsonPagesPath);
        $this->jsonPages = json_decode(json: $jsonPagesContent, associative: true) ?? [];

        /*
            Importiert essenzielle Bestandteile
        */
        $this->importConstants();
        $this->importModels();
        $this->importServices();
        $this->loadUser();
        $this->importPages();
    }

    private function loadUser() {
        $sessionService = new SessionService();
        $userObject = $sessionService->getObject('user_object');
        $this->user = $userObject != null
            ? $userObject
            : new User("Guest", "", "", "", "UNKNOWN"); 
    }

    private function importHomePage(): void {
        $jsonPagesContent = file_get_contents(filename: $this->jsonPagesPath);
        $this->jsonPages = json_decode(json: $jsonPagesContent, associative: true) ?? [];

        foreach ($this->jsonPages as $page) {
            if($page['name'] == Page::HOME) {
                $this->import($page['path']);
            }
        }
        exit();
    }

    private function importPages(): void {
        if(!isset($_GET['page']))
            $this->importHomePage();

        if(!file_exists(filename: $this->jsonPagesPath))
            return; 

        $jsonPagesContent = file_get_contents(filename: $this->jsonPagesPath);
        $this->jsonPages = json_decode(json: $jsonPagesContent, associative: true) ?? [];
        $this->html = new Html(pages: $this->jsonPages);

        // $userGroup =
        foreach($this->jsonPages as $page) {

            // echo $page['name'] . "|<br>";
            // echo $this->user->getGroup() . "|<br>";
            // print_r(array_map('trim', $page['permissions']));
            // echo "<br>";
            // echo "================<br>";

            // continue;
            if($page['name'] == $_GET['page']) {
                // in_array("Apfel", $array)
                if(isset($page['permissions'])) {
                    if(in_array($this->user->getGroup(), array_map('trim', $page['permissions']))) {
                        $this->html->loadDashboard($page['path'] ?? '');
                        exit();
                    }
                } else {
                    $this->html->loadDashboard($page['path'] ?? '');
                    exit();
                }
            }
        }

        foreach($this->jsonPages as $page) {
            if($page['name'] == Page::DEFAULT) {
                $this->html->loadDashboard($page['path'] ?? '');
            }
        }
        
        exit();
    }

    private function importModels(): void {        
        if(!file_exists(filename: $this->jsonModelsPath))
            return; 

        $jsonModelsContent = file_get_contents(filename: $this->jsonModelsPath);
        $this->jsonModels = json_decode(json: $jsonModelsContent, associative: true) ?? [];
    
        foreach ($this->jsonModels as $jsonModelPath) {
            $this->import(path: $jsonModelPath);
        }
    }

    private function importServices(): void {
        if(!file_exists(filename: $this->jsonServicesPath))
            return; 

        $jsonServicesContent = file_get_contents(filename: $this->jsonServicesPath);
        $this->jsonServices = json_decode(json: $jsonServicesContent, associative: true) ?? [];

        foreach ($this->jsonServices as $jsonServicePath) {
            $this->import(path: $jsonServicePath);
        }
    }

    private function importConstants(): void {
        if(!file_exists(filename: $this->jsonConstantsPath))
            return; 

        $jsonConstantsContent = file_get_contents(filename: $this->jsonConstantsPath);
        $this->jsonConstants = json_decode(json: $jsonConstantsContent, associative: true) ?? [];

        foreach ($this->jsonConstants as $jsonConstantPath) {
            $this->import(path: $jsonConstantPath);
        }
    }

    private function import(string $path): void {
        if(!file_exists($path))
            return;

        require($path);
    }
}

class Html {
    public array $pages;
    private User $user;
    
    public function __construct(array $pages) {
        $this->pages = $pages;

        $this->loadUser();
    }

    private function loadUser() {
        $sessionService = new SessionService();
        $userObject = $sessionService->getObject('user_object');
        $this->user = $userObject != null
            ? $userObject
            : new User("Guest", "", "", "", "UNKNOWN"); 
    }

    public function loadDashboard(string $pagePath): void {
        $htmlElementSidebar = "";
        $pageTitle = "Default Title";
        $pageSubject = "Default Subject";
        
        foreach ($this->pages as $page) {
            if (empty($page['name'])) {
                continue;
            }

            if (isset($page['permissions'])) {
                if (!in_array($this->user->getGroup(), $page['permissions']))
                continue;
            }
        
            $iconPath = $page['icon'] ?? '';

            $htmlElementSidebar .= $page['name'] == Page::HOME
            ? "<div class='sidebar-item'><a href='/'><img src='{$iconPath}' style='width:20px; height:20px; object-fit:contain; margin-right:8px; vertical-align:middle;'>{$page['name']}</a></div>"
            : "<div class='sidebar-item'><a href='?page={$page['name']}'><img src='{$iconPath}' style='width:20px; height:20px; object-fit:contain; margin-right:8px; vertical-align:middle;'>{$page['name']}</a></div>";
        
        
            // if (isset($_GET['page']) && $_GET['page'] === $page['name']) {
            if (isset($_GET['page']) && $page['path'] == $pagePath) {
                $pageTitle = $page['title'] ?? "Seite ohne Titel";
                $pageSubject = $page['subject'] ?? "Kein Betreff verfügbar";
            }
        }


        $username = $this->user->getUsername();
        $firstname = $this->user->getFirstname();
        $lastname = $this->user->getLastname();
        $group = $this->user->getGroup();
        $password = $this->user->getPassword();

        $html = "
        <!DOCTYPE html>
        <html lang='de'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$pageTitle}</title>
            <link rel='stylesheet' type='text/css' href='css/dashboard.css'>
            <link rel='stylesheet' type='text/css' href='css/main.css'>
            <link href='https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap' rel='stylesheet'>

            <script src='js/dashboard.js'></script>
        </head>
        <body>
            <header>
                <div class='header-left'>
                    <button class='toggle-btn' onclick='toggleSidebar()'>☰</button>
                    <img src='images/logo_hell.png' class='image_userprofile'>
                    </a>
                    <h1>FortBase</h1>
                </div>
                <div class='image_userprofile_container'>
                    <img src='images/userprofile.png' alt='User Profile' class='image_userprofile'>
                    <h2>{$username}</h2>
                </div>
            </header>
            <div class='container'>
                <div class='sidebar' id='sidebar'>
                    <button class='close-btn' onclick='toggleSidebar()'>X</button>
                    <div class='button-spacing'></div>    
                    <h3>Sidebar</h3>
                    <ul>
                        {$htmlElementSidebar}
                    </ul>
                </div>
                <div class='content' id='content'>
                    <h2>{$pageSubject}</h2>
        ";
        echo $html;
        include($pagePath); 
        
        $html = "
                </div>
            </div>
            <footer>
                <p>&copy; 2024 FortBase by Luca Volkmann & Ben Hoffmann</p>
            </footer>
        </body>
        </html>
        ";
        echo $html;
    }
}
/*
                      :::!~!!!!!:.
                  .xUHWH!! !!?M88WHX:.
                .X*#M@$!!  !X!M$$$$$$WWx:.
               :!!!!!!?H! :!$!$$$$$$$$$$8X:
              !!~  ~:~!! :~!$!#$$$$$$$$$$8X:
             :!~::!H!<   ~.U$X!?R$$$$$$$$MM!
             ~!~!!!!~~ .:XW$$$U!!?$$$$$$RMM!
               !:~~~ .:!M"T#$$$$WX??#MRRMMM!
               ~?WuxiW*`   `"#$$$$8!!!!??!!!
             :X- M$$$$       `"T#$T~!8$WUXU~
            :%`  ~#$$$m:        ~!~ ?$$$$$$
          :!`.-   ~T$$$$8xx.  .xWW- ~""##*"
.....   -~~:<` !    ~?T#$$@@W@*?$$      /`
W$@@M!!! .!~~ !!     .:XUW$W!~ `"~:    :
#"~~`.:x%`!!  !H:   !WM$$$$Ti.: .!WUn+!`
:::~:!!`:X~ .: ?H.!u "$$$B$$$!W:U!T$$M~
.~~   :X@!.-~   ?@WTWo("*$$$W$TH$! `
Wi.~!X$?!-~    : ?$$$B$Wu("**$RM!
$R@i.~~ !     :   ~$$$$$B$$en:``
?MXT@Wx.~    :     ~"##*$$$$M~

*/
?>
<?php
?>
