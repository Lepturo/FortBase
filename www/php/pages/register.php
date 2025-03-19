<?php
function register(string $username, string $firstname, string $lastname, string $password): bool {
    $user = new User($username, $firstname, $lastname, $password, "");
    $authService = new AuthService($user);

    return $authService->register();
}

$info = '';

if(isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['password']) && isset($_POST['repeat_password'])) {
    if($_POST['password'] != $_POST['repeat_password']) {
        $info = '<p>Password does not match</p>';
    } elseif(register($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])) {
        $info = "<div class='success'>Konto wurde erfolgreich erstellt - Willkommen {$_POST['firstname']} {$_POST['lastname']}</div>";
    } else {
        $info = "<div class='error'>Konto mit dem Nutzernamen {$_POST['username']}, bereits vorhanden</div>";
    }
}

$html = "
<body class='register_body'>
    <link rel='stylesheet' href='css/register.css'>

    <div class='form_container'>
        <form action='#' method='POST' class='register_form'>
            {$info}
            <label for='username' class='register_label'>Benutzername</label>
            <br>
            <input type='text' id='username' name='username' placeholder='Benutzername' class='register_input' required>
            <br><br>

            <label for='firstname' class='register_label'>Vorname</label>
            <br>
            <input type='text' id='firstname' name='firstname' placeholder='Vorname' class='register_input' required>
            <br><br>

            <label for='lastname' class='register_label'>Nachname</label>
            <br>
            <input type='text' id='lastname' name='lastname' placeholder='Nachname' class='register_input' required>
            <br><br>

            <label for='password' class='register_label'>Passwort</label>
            <br>
            <input type='password' id='password' name='password' placeholder='*****' class='register_input' required>
            <br><br>

            <label for='password' class='register_label'>Passwort wiederholen</label>
            <br>
            <input type='password' id='reapeat_password' name='repeat_password' placeholder='*****' class='register_input' required>
            <br><br>

            <button type='submit' class='register_button'>Anmelden</button>
        </form>
    </div>
    
</body>";
echo $html;
?>