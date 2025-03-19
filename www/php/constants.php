<?php
/*
    Folgende Klassen enthalten Konstanten welche beim Importieren für die importierende Klasse frei verfügbar sind
*/

/*
    Die Klasse Table enthält alle Tabellen Namen
*/
class Table {
    const USER = 'User';
    const THEME = 'Theme';
    const TRAINING = 'Training';
    const TRAINING_USER = 'Training_User';
}

/*
    Die Klasse Groups enthält alle Gruppen Namen
*/
class Groups {
    const STANDARD = 'UNVERIFIED';
}

/*
    Gibt fest definierte Seiten Attribute an
*/
class Page {
    const HOME = 'Home';
    const DEFAULT = 'Dashboard';
}

/*
    Die Klasse TablePrimaryKey enthält von jeder Klasse den primaryKey
*/
class TablePrimaryKey {
    const USER = 'p_username';
    const THEME = 'p_id';
    const TRAINING = 'p_id';
}
?>