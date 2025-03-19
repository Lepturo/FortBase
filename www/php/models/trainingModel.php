 <?php
    //require("./theme.php");
    class Training
    {
        private int $id;
        private String $title;
        private Theme $theme;
        private String $trainer;
        private DateTime $startDate;
        private DateTime $endDate;
        private array $participants;

        // Vielleicht noch ThemeId hinzufügen - In der Datenbank ist theme_id
        // gespeichert, über welche man das Thema per ID queryn könnte

        // TODO: ADD participants to database OR REMOVE FROM PROJECT
        //private $participants;

        public function __construct(int $id, String $title, Theme $theme, String $trainer, DateTime $startDate, DateTime $endDate)
        {
            $this->id = $id;
            $this->title = $title;
            $this->theme = $theme;
            $this->trainer = $trainer;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
        }

        // Getter & Setter
        public function getAsArray(): array
        {
            return array($this->id, $this->theme->getId(), $this->title, $this->trainer, $this->startDate->format('Y-m-d H:i:s'), $this->endDate->format('Y-m-d H:i:s'));
        }
        public function getId(): int
        {
            return $this->id;
        }

        public function getTitle(): String
        {
            return $this->title;
        }

        public function setTitle(String $title)
        {
            $this->title = $title;
        }

        public function getTheme(): Theme
        {
            return $this->theme;
        }

        public function setTheme(Theme $theme)
        {
            $this->theme = $theme;
        }

        public function getTrainer(): String
        {
            return $this->trainer;
        }

        public function setTrainer(String $trainer)
        {
            $this->trainer = $trainer;
        }

        public function getStartDate(): DateTime
        {
            return $this->startDate;
        }

        public function setStartDAte(DateTime $startDate)
        {
            $this->startDate = $startDate;
        }

        public function getEndDate(): DateTime
        {
            return $this->endDate;
        }

        public function setEndDate(DateTime $endDate)
        {
            $this->endDate = $endDate;
        }
    }
    ?>