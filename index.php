<?php

ini_set('display_errors', 'Off');
error_reporting(E_ALL);

require_once 'config.php';
require_once 'classes/LiveScoreApi.class.php';

$LiveScoreApi = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
$scores = $LiveScoreApi->getLivescores(['competition_id' => COMPETITION_ID]);

$standings = $LiveScoreApi->getStandings(['competition_id' => COMPETITION_ID]);

?><!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-4">
                    <h2>Groups</h2>
                    <hr />
                        <?php
                            $currentGroupId = null;
                            foreach ($standings as $_standing) {
                                if ($currentGroupId != $_standing['group_id']) {
                                    if ($currentGroupId) {
                                        echo "</table>";
                                    }
                                    $currentGroupId = $_standing['group_id'];
                                ?>

                                    <h4>Group <?=$_standing['group_name']?></h4>
                                    <table class="table">
                                        <tr class="table-info">
                                            <th>#</th>
                                            <th>Country</th>
                                            <th>Pts.</th>
                                            <th>pld.</th>
                                        </tr>
                                <?
                                }
                        ?>


                            <tr>
                                <td <?php if ($_standing['rank'] < 3) { ?>class="table-success"<?php } ?>><?=$_standing['rank']?>.</td>
                                <td><?=$_standing['name']?></td>
                                <td><?=$_standing['points']?></td>
                                <td><?=$_standing['matches']?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="col">
                    <h2>Live Scores</h2>
                    <hr />
                    <?php if ($scores) { ?> 
                        <table class="table">
                            <?php foreach ($scores as $_match) { ?>
                                <tr>
                                    <td><?=$_match['time']?></td>
                                    <td><?=$_match['home_name']?></td>
                                    <td><?=$_match['score']?></td>
                                    <td><?=$_match['away_name']?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-primary" role="alert">
                            There are no matches currently being played!
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
