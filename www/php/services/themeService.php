<?php
     /**
     * Der ThemeService wird verwendet, um Themen hinzuzufügen, zu löschen oder abzufragen.
     */
    class ThemeService {
        private DbService $dbService;

        public function __construct() {
            $this->dbService = new DbService();
            $this->dbService->connectMySQL();
        }   
        /**
         * Fragt alle Themen aus der Datenbank ab und gibt diese als Array zurück
         * 
         * @return array Ein Array mit allen Themen aus der Datenbank
         */
        public function getAll(): array {
            // structure from get -> [p_id]=id [name]=value [description]=value
            $themesArray = $this->dbService->get(Table::THEME);
            $themes = array();

            foreach($themesArray as $theme) {
                $themes[] = new Theme($theme["p_id"], $theme["name"], $theme["description"]);
            }
            return $themes;
        }

        /**
         * Fragt anhand der per parameter übergebenden id ein bestimmtes Thema aus der Datenbank ab
         * 
         * @param int $id Die id des Themas, welches abgefragt wird
         * @return Theme Das Thema, welches anhand der id abgefragt wurde
         */
        public function getById(int $id): Theme {
            $themes = $this->getAll();
            foreach($themes as $theme) {
                if ($theme->getId() == $id) {
                    return $theme;
                }
            }
            return new Theme(-1, "Nicht gefunden", "Nicht gefunden");
        }

        /**
         * Fügt ein neues Thema in die Datenbank ein
         * 
         * @param $theme Das Thema, welches hinzugefügt wird 
         */
        public function add(Theme $theme) {
            $this->dbService->add(Table::THEME, $theme->getAsArray());
        }

        /**
         * Löscht einen Eintrag aus der Datenbank anhand der übergebenen id
         * 
         * @param $id Die id des zu löschenden Themas
         */
        public function delete(int $id) {
            $this->dbService->delete(Table::THEME, TablePrimaryKey::THEME, $id);
        }
    }
?>