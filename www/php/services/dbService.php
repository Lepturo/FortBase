<?php
// $dbService = new DbService();
// $dbService->setDebug(true);
// $dbService->connectMySQL();
// // $result = $dbService->get("User");
// // print_r($result);
// $dbService->delete(Table::USER, TableKey::USER, "MaxMussKaka");
?>
<?php
//require("./constants.php");
/*
    dbService:
    notwendig um eine Verbindung mit der Datenbank herzustellen
*/
class DbService {
    /*
        Klassen Attribute:
        für den Verbindungsaufbau mit der Datenbank sind folgende Attribute essenziel.
    */
    private string $host = 'localhost';
    private string $dbname = 'benlotharhoffma_FortiBase';
    private string $username = 'benlotharhoffma';
    private string $password = 'System1!';
    
    /*
        $debug Variable ist vom Datentyp Bool damit man sie ein- und ausschalten kann.
        Wenn man Sie auf true stellt, zeigt sie alle Nachrichten/Informationen für alle sichtbar an.
    */
    private bool $debug = false;

    /*
        $connectino Variable speichert alle Informationen von der Verbindung
    */
    private PDO $connection;

    /*
        Methode, die eine MySQL-Datenbankverbindung herstellt
    */
    public function connectMySQL(): bool {
        try {
            // Erstellung der DSN (Data Source Name)
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);

            // Setzen des Fehlermodus auf Exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($this->debug)
                echo 'Verbindung erfolgreich!<br>';

            return true;
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Verbindung fehlgeschlagen: ' . $e->getMessage() . '<br>';
            return false;
        }
    }


    /**
     * Fügt einer Tabelle neue Einträge hinzu
     * 
     * @param string $table gibt die tabelle an auf die man die operation starten will
     * @param array $valuesArray enthält werte welche hinzugefügt werden sollen
     * @return bool gibt an ob das adden eines neuen Eintrags erfolgreich war oder fehlgeschlagen ist
    */
    public function add(string $table, array $valuesArray): bool {
        try {
            $valueSql = implode(", ", array_fill(0, count($valuesArray), "?"));
            $sqlQuery = "INSERT INTO {$table} VALUES({$valueSql})";
            $stmt = $this->connection->prepare($sqlQuery);

            $stmt->execute(array_values($valuesArray));
            
            if ($this->debug)
                echo 'Hinzufügen erfolgreich!<br>';

            return true;
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Hinzufügen fehlgeschlagen: ' . $e->getMessage() . '<br>';

            return false;
        }
    }
    /**
     * Aktualisiert einen bestehenden Eintrag in einer Tabelle
     * 
     * @param string $table gibt die Tabelle an, auf die man die Operation starten will
     * @param array $valuesArray enthält die Spaltennamen und deren neue Werte
     * @param string $key gibt den Schlüsselnamen an, welcher zur Identifikation der gewünschten Zeile dient
     * @param string $value gibt den Wert des Schlüssels an, welcher zur Identifikation der gewünschten Zeile dient
     * @return bool gibt an, ob das Aktualisieren eines Eintrags erfolgreich war oder fehlgeschlagen ist
    */
    public function update(string $table, array $valuesArray, string $key, string $value): bool {
        try {
            $setSql = implode(", ", array_map(fn($k) => "$k = ?", array_keys($valuesArray)));
            $sqlQuery = "UPDATE {$table} SET {$setSql} WHERE {$key} = ?";
            $stmt = $this->connection->prepare($sqlQuery);

            $stmt->execute([...array_values($valuesArray), $value]);

            if ($this->debug)
                echo 'Aktualisierung erfolgreich!<br>';

            return true;
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Aktualisierung fehlgeschlagen: ' . $e->getMessage() . '<br>';

            return false;
        }
    }
    /**
     * Gibt Werte einer Tabelle zurück
     * 
     * @param string $table gibt die tabelle an auf die man die operation starten will
     * @return array gibt ein Datentyp array zurück der alle angeforderten Werte beinhaltet
    */
    public function get(string $table): array {
        try {
            $sqlQuery = "SELECT * FROM {$table}";
            $stmt = $this->connection->prepare($sqlQuery);
            $stmt->execute();

            if ($this->debug)
                echo 'Query erfolgreich!<br>';

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Query fehlgeschlagen: '. $e->getMessage() . '<br>';

            return [];
        }
    }

    public function getWhere(string $table, string $key, string $value): array {
        try {
            $sqlQuery = "SELECT * FROM {$table} WHERE $key = ?";
            $stmt = $this->connection->prepare($sqlQuery);
            $stmt->execute([$value]);

            if ($this->debug)
                echo 'Query erfolgreich!<br>';

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Query fehlgeschlagen: '. $e->getMessage() . '<br>';

            return [];
        }
    }

    /**
     * Löscht eine Tabelle
     * 
     * @param string $table bestimmt die Tabelle bei der die Operaetion ausgeführt werden soll 
     * @param string $key gibt den Schlüsselnamen an welcher zur Identifikation der gewünschte Zeile dient.
     * @param string $value gibt den Wert des Schlüssels an welcher zu Identifkation der gewünschten Zeile dient.
     * @return bool gibt an ob die angeforderte Operation erfolgreich war oder fehlgeschlagen ist.
    */
    public function delete(string $table, string $key, string $value): bool {
        try {
            $sqlQuery = "DELETE FROM {$table} WHERE $key = ?";
            $stmt = $this->connection->prepare($sqlQuery);
            $stmt->execute([$value]);

            if ($this->debug)
                echo 'Löschen erfolgreich!<br>';

            return true;
        } catch (PDOException $e) {
            if ($this->debug)
                echo 'Löschen fehlgeschlagen: '. $e->getMessage() . '<br>';

            return false;
        }
    }

    // Getter für die Verbindung
    public function getConnection(): PDO {
        return $this->connection;
    }

    // Getter & Setter
    public function getHost(): string {
        return $this->host;
    }

    public function setHost(string $host): void {
        $this->host = $host;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getDebug(): bool {
        return $this->debug;
    }

    public function setDebug(bool $debug): void {
        $this->debug = $debug;
    }
}
/*
  ,-~~-.___.
 / |  '     \         
(  )         0  
 \_/-, ,----'            
    ====           // 
   /  \-'~;    /~~~(O)
  /  __/~|   /       |     
=(  _____| (_________|
*/
?>