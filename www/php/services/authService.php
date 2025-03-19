<?php
/*
    Die Klasse AuthService ermöglicht die Regestrierung und Löschung eines neuen Nutzers.
    Neben dem kann man mit AuthService schauen ob die Nutzerdaten mit denen eines bereits existierenden Nutzers übereinstimmen.
*/
class AuthService {
    private user $user;
    private dbService $dbService;

    // Debug variable: wenn die variable true ist, werden Prozesse ausgegeben um zu verfolgen wie die Funktionen laufen
    private bool $debug = false;

    /**
     * Initialisiert User: Wichtig für weitere Prozedueren und Operationen
     * 
     * @param User übergeben von Userdaten 
    */
    public function __construct(User $user) {
        $this->user = $user;
        $this->dbService = new dbService();

        $this->dbService->connectMySQL();
    }

    /**
     * Überprüft ob die User Daten korrekt sind. Dabei wird die existenz wie die Übereinstimmung des Passworts überprüft.
     * 
     * @return bool gibt an ob User Daten korrekt sind. 
    */
    public function login(): bool {
        if(!$this->isUserExist())
            return false;

        $hashService = new HashService();
        $users = $this->dbService->get(Table::USER);
        $userPassword = $this->user->getPassword();

        foreach($users as $user) {
            if($user['p_username'] == $this->user->getUsername())
                $hashedPassword = $user['password'];
        }
        
        if(!$hashService->compareHash($userPassword, $hashedPassword))
            return false;

        return true;
    }

    /**
     * Erstellt neuen Nutzer wenn dieser noch nicht existiert
     * 
     * @return bool gibt an ob die Erstellung eines neuen Nutzers erfolgreich war
    */
    public function register(): bool {
        if($this->isUserExist())
            return false;

        if ($this->debug == true) echo "User exisitert nicht<br>";

        $hashService = new HashService();
        $hashedPassword = $hashService->getHash($this->user->getPassword());
        
        $user = [
            'p_username' => $this->user->getUsername(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'password' => $hashedPassword,
            'role' => Groups::STANDARD
        ];

        $this->dbService->add(Table::USER, $user);
            

        if ($this->debug == true) echo "User zur Datenbank hinzugefügt<br>";

        return true;
    }

    /**
     * Löscht user permanent
     * 
     * @return bool gibt an ob die operation erfolgreich war
    */
    public function deleteUser(): bool {
        if(!$this->isUserExist())
            return false;

        return $this->dbService->delete(Table::USER, 'p_username', $this->user->getUsername());
    }

    /**
     * Überprüft ob der user existiert
     * 
     * @return bool gibt an ob der user exisitert oder nicht: true = existiert, false = existiert nicht
    */
    public function isUserExist(): bool {
        $users = $this->dbService->get(Table::USER);
        
        foreach($users as $user) {
            if($user['p_username'] == $this->user->getUsername())
                return true;
        }
        return false;
    }
}
/*
      .--..--..--..--..--..--.
    .' \  (`._   (_)     _   \
  .'    |  '._)         (_)  |
  \ _.')\      .----..---.   /
  |(_.'  |    /    .-\-.  \  |
  \     0|    |   ( O| O) | o|
   |  _  |  .--.____.'._.-.  |
   \ (_) | o         -` .-`  |
    |    \   |`-._ _ _ _ _\ /
    \    |   |  `. |_||_|   |
    | o  |    \_      \     |     -.   .-.
    |.-.  \     `--..-'   O |     `.`-' .'
  _.'  .' |     `-.-'      /-.__   ' .-'
.' `-.` '.|='=.='=.='=.='=|._/_ `-'.'
`-._  `.  |________/\_____|    `-.'
   .'   ).| '=' '='\/ '=' |
   `._.`  '---------------'
           //___\   //___\
             ||       ||
    LGB      ||_.-.   ||_.-.
            (_.--__) (_.--__)     
*/
?>