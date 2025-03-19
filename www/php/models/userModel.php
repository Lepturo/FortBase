<?php
/**
 * Die Klasse User dient als Model, um Daten einfacher verarbeiten zu können.
 */
class User {
    private string $username;
    private string $firstname;
    private string $lastname;
    private string $password;
    private string $group;
    
    /**
     * Erstellt eine neues Objekt mit Nutzerdaten.
     * 
     * @param string $username
     * @param string $firstname
     * @param string $lastname
     * @param string $password
     * @param string $group
    */
    public function __construct(string $username = "", string $firstname = "", string $lastname = "", string $password = "", string $group = "") {
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->group = $group;
    }

    // Getter & Setter
    public function getAsArray(): array {
        return array($this->username, $this-> firstname, $this->lastname, $this->password, $this->group);
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setGroup(string $group): void {
        $this->group = $group;
    }

    public function getGroup(): string {
        return $this->group;
    }
}
/*
         _          __________                              _,
     _.-(_)._     ."          ".      .--""--.          _.-{__}-._
   .'________'.   | .--------. |    .'        '.      .:-'`____`'-:.
  [____________] /` |________| `\  /   .'``'.   \    /_.-"`_  _`"-._\
  /  / .\/. \  \|  / / .\/. \ \  ||  .'/.\/.\'.  |  /`   / .\/. \   `\
  |  \__/\__/  |\_/  \__/\__/  \_/|  : |_/\_| ;  |  |    \__/\__/    |
  \            /  \            /   \ '.\    /.' / .-\                /-.
  /'._  --  _.'\  /'._  --  _.'\   /'. `'--'` .'\/   '._-.__--__.-_.'   \
 /_   `""""`   _\/_   `""""`   _\ /_  `-./\.-'  _\'.    `""""""""`    .'`\
(__/    '|    \ _)_|           |_)_/            \__)|        '       |   |
  |_____'|_____|   \__________/   |              |;`_________'________`;-'
jgs'----------'    '----------'   '--------------'`--------------------`
     S T A N          K Y L E        K E N N Y         C A R T M A N
*/
?>