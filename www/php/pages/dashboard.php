<link rel="stylesheet" href="../../css/dashboard.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <div class="banner">Willkommen zur Fortbildungsdatenbank</div>
        <!--     
        <div class="card">
                <div class="card-header watched">Watched Issues</div>
            <div class="card-body">You are not currently watching any issues.</div>
        </div>
        -->
    
        <div class="card">
            <div class="card-header assigned">Für mich</div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Begin</th>
                        <th>Ende</th>
                        <th>Titel</th>
                    </tr><?php
                    $trainingService = new TrainingService();
                    $trainings = $trainingService->getAll();
                    $html = "";

                    foreach ($trainings as $training) {
                        $theme = $training->getTheme()->getName();
                        $startDate = $training->getStartDate()->format('Y-m-d H:i:s');
                        $endDate = $training->getEndDate()->format('Y-m-d H:i:s');
  
                        $html .= "
                            <tr>
                                <td>{$startDate}</td>
                                <td>{$endDate}</td>
                                <td><a href='#'>{$theme}</a></td>
                            </tr>
                        ";
                    }

                    echo $html;
                    ?>
                </table>
            </div>
        </div>
        
    </div>
</body>