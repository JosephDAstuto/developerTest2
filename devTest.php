<?php
// error_reporting(1);
// ini_set('display_errors', true);

$colours = ['Blue', 'Red', 'Green', 'Yellow'];

function firstColour($title, $colourArray) {

    $lowCol = "";
    $lowPos = 50;

    foreach ($colourArray as $colour) {
        
        $loc = stripos($title, $colour, 0);
        if ($loc == false) {
            //do nothing
        } else {

            if ($loc < $lowPos){
                $lowCol = $colour;
                $lowPos = $loc;
            }

        }
    }
    return $lowCol;
}



$moviesArray=array();

//for testing purposes and and sanity checking for dev note the space in the begining
// $tCol = " Red White & Blue";
// print_r(firstColour($tCol, $colours));

foreach ($colours as $colour) {
    $url = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=".$colour."&page=1");
    //Below for testing multiple colours
    // $url = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=blue+red");
    $json = json_decode($url, true);

    foreach ($json['Search'] as $movies) {   
        
        $firstColour = firstColour(" ".$movies['Title'], $colours);
        $movies += ['colours' => $firstColour];
        
        array_push($moviesArray, $movies );
    };

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Colourful Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    
    <h1>Colourful Movies</h1>
</head>
<body>
    <div class="main">
        <table id="movieTable">
            <tr>
                <th>Colour</th>
                <th>Movie Title</th>
                <th>Release</th>
                <th>Runtime</th>
            </tr>
            <?php
                foreach ($moviesArray as $movie){
                    $movieUrl = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&i=".$movie['imdbID']."&type=movie");
                    $movieJson = json_decode($movieUrl, true);
                    echo '<tr><td bgcolor="'.$movie['colours'].'">'.$movie['colours'].'</td> <td>'.$movie['Title'].'</td><td>'.$movieJson['Released'].'</td><td>'.$movieJson['Runtime'].'</td></tr>'; 
                };
            ?>
        </table>`
    </div>
</body>
</html>

