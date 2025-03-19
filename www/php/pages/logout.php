<?php
function login(string $username, string $password): bool {
    $user = new User($username, "", "", $password, "");
    $authService = new AuthService($user);

    return $authService->login();
}

$info = '';

if(isset($_POST['username']) && isset($_POST['password'])) {
    if(login($_POST['username'], $_POST['password'])) {
        
        $dbService = new DbService();
        $dbService->connectMySQL();
        
        $users = $dbService->get(Table::USER);
        $username = $_POST['username'];

        foreach($users as $user) {
            if($username == $user['p_username']) {
                $firstname = $user['firstname'];
                $lastname = $user['lastname'];
                $password = $user['password'];
                $group = $user['role'];
            }
        }

        $user = new User($username, $firstname, $lastname, $password, $group);
        $sessionService = new SessionService();
        $sessionService->setObject('user_object', $user);
        
        $info = "<div class='success'>Anmeldung erfolgreich - Willkommen zurück {$firstname} {$lastname}</div>";
        $redirectService = new RedirectService();

        $redirectService->redirect('?page=Dashboard', 2000);
    } else {
        $info = "<div class='error'>Konto Daten sind falsch, bitte versuchen sie es erneut</div>";
    }
}

$html = "
<body class='login_body'>
    <link rel='stylesheet' href='css/login.css'>
    
    <!--div class='info'>Das ist eine Info-Nachricht.</div>
    <div class='success'>Erfolgreich eingeloggt!</div>
    <div class='error'>Fehler beim Login!</div>
    <div class='warning'>Warnung: Passwort läuft bald ab.</div-->
    
    <div class='form_container'>
        <form action='#' method='POST' class='login_form'>
            {$info}
            <label for='username' class='login_label'>Benutzername</label>
            <input type='text' id='username' name='username' placeholder='Benutzername' class='login_input' required>

            <label for='password' class='login_label'>Passwort</label>
            <input type='password' id='password' name='password' placeholder='******' class='login_input' required>

            <button type='submit' class='login_button'>Anmelden</button>
        </form>
    </div>
    
</body>";
echo $html;
?>