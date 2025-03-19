<?php
class TrainingService
{
    private DbService $dbService;
    private ThemeService $themeService;

    public function __construct()
    {
        $this->dbService = new DbService();
        $this->dbService->connectMySQL();

        $this->themeService = new ThemeService();
    }
    /**
     * Fragt alle Trainings aus der Datenbank ab und gibt diese als Array zurück
     * 
     * @return array Ein Array mit allen Trainings aus der Datenbank
     */
    public function getAll(): array
    {
        $trainingsArray = $this->dbService->get(Table::TRAINING);
        $trainings = array();

        foreach ($trainingsArray as $training) {
            $theme = $this->themeService->getById($training["f_theme_id"]);
            $trainings[] = new Training((int)$training["p_id"], $training["title"], $theme, $training["trainer"], new DateTime($training["startDate"]), new DateTime($training["endDate"]));
        }
        return $trainings;
    }

    public function getById(int $id): Training
    {
        $trainings = $this->getAll();
        foreach ($trainings as $training) {
            if ($training->getId() == $id) {
                return $training;
            }
        }
        return new Training(-1, "Nicht gefunden", new Theme(-1, "Nicht gefunden", "Nicht gefunden"), "Nicht gefunden", new DateTime(), new DateTime());
    }
    

    public function getUserByUsername(String $username): User
    {
        $user = $this->dbService->getWhere(TABLE::USER, "p_username", $username)[0];
        return new User($user["p_username"], $user["firstname"], $user["lastname"], $user["password"], $user["role"]);
    }

    public function getParticipantsByTrainingId(int $id): array
    {
        $fetchResult = $this->dbService->getWhere(TABLE::TRAINING_USER, "pf_training_id", $id);
        $participants = array();


        foreach ($fetchResult as $participant) {
            $participants[] = $this->getUserByUsername($participant["pf_username"]);
        }

        return $participants;
    }

    public function getAllUsers()
    {
        $users = $this->dbService->get(TABLE::USER);
        $result = array();
        foreach($users as $user) {
            $result[] = new User($user["p_username"], $user["firstname"], $user["lastname"], $user["password"], $user["role"]);
        }
        return $result;
    }

    public function addParticipant(int $trainingId, String $username)
    {
        $this->dbService->add(TABLE::TRAINING_USER, array($trainingId, $username));
    }

    /**
     * Fügt ein Traing, welches als Parameter übergeben wird, in die Datenbank ein
     * 
     * @param $training Das einzufügende Training
     */
    public function add(Training $training)
    {
        $this->dbService->add(Table::TRAINING, $training->getAsArray());
    }

    public function updateUserRole(String $username, String $newRole) {
        $this->dbService->update(TABLE::USER, ["role" => $newRole], "p_username", $username);
    }

    /**
     * Löscht ein Training aus der Datenbank anhand der übergebenen id
     * 
     * @param int $id Die id des zu löschenden Trainings
     */
    public function delete(int $id)
    {
        $this->dbService->delete(Table::TRAINING, TablePrimaryKey::TRAINING, $id);
    }

    public function getLastInsertedTrainingsId()
    {
        $conn = $this->dbService->getConnection();

        $tableTraining = TABLE::TRAINING;
        $tablePrimaryKeyTraining = TablePrimaryKey::TRAINING;

        $query = "SELECT * FROM  {$tableTraining}  ORDER BY {$tablePrimaryKeyTraining} DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!--
               .+i+;I:
              :=..    t:
             =;+;:..   ii.
            +I::;=:;;+t=i;=.
            +;;;+;i.;;:It++i;               
          ;X  t+=+=;;i;=iItt+V
          :t  =ii+.=.;:=+ii++iIY
          :R   i=ti+=+;i+i=t=++:+Ii+==
          :R  .+iii==;;itt=++i=i::=YRBBBMRRVY+;
           ;+    +i+;+;+itiiitii+i+i .=iYVIi+iitI=;=
   +. ::.X:.;   .:=;+=i=ii++YYIIiiiIt.  ;YI::+:;=iit===;
  I;:. .  :+:YI;R..=;;=i+titIVItIYVYYYXVX=+:.....;;+t=+::=
  +i;.::......:;:=;;;;;=+iii=+=++ii++tttVI===;;;;::;;+;tti=
   tI+.::::.;::;:=+++i=+;i++ititttItIItt=;=t+==;:;::;:;=+IY=:
    :=i;::.::::;=:;++=i===;iiittitttttItt=;=;:;;...::;::;.;+ii:;
      :=+::.;+i+t++itiIIY=RRRXXV+VYi===:::;;:.:.........::;;;:;;;;:;;;;
          :tYti=;=+;+;=+++=;iIVRRRRVVRXRYYYV=;=::::..........:.:==+i==;;==;;:
            ;Xti;=;+i;+ti++=+tRBBBYBVRYXIVtYY++=..:........:.;;::==;::;.;;;
              YVi==;++:I;;ii+IRXIYIY=:;i;i;=;;;;;.........;:::;:;=;..:;::
              :=XI=+iItIiit=:IXRRIItiXiIYiIt;I==:.......:..:....;:........
              :BWRRV;YRIXY...+YRRVYVR+XIRItitI++=:.....;:.........:....:.::..
             ==+RWBXtIRRV+.+IiYRBYBRRYYIRI;VitI;=;..........:::.::;::::...;;;:.
-->