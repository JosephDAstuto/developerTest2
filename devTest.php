<?php
// error_reporting(1);
// ini_set('display_errors', true);

$colours = ['Blue', 'Red', 'Green', 'Yellow'];

function firstColour($title, $colourArray) {

    $lowCol = "";
    $lowPos = 100;

    foreach ($colourArray as $colour) {
        
        //returns false if not found
        $loc = stripos($title, $colour, 0);
        if ($loc == false) {
            //do nothing
        } else {
            //check to see if the location of the colour checked is lower
            //then the previous
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
    $link = "http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=".$colour."&page=1";
    //Below for testing multiple colours best outline for multple colours and showing the firstColour() funciton
    //$link = "http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=blue+green";
    //$url = file_get_contents($link);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $url = curl_exec($ch);

    $json = json_decode($url, true);

    // print_r($json);

    if ($json['Response'] == "False"){
        echo "Something went wrong in finding the movies: ".$json['Error'];
    } else {

        foreach ($json['Search'] as $movies) {   
            
            $firstColour = firstColour(" ".$movies['Title'], $colours);
            $movies += ['colours' => $firstColour];
            
            array_push($moviesArray, $movies );
        };
    }
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
                    echo '<tr><td><span class="dot_'.$movie['colours'].'"></span></td> <td>'.$movie['Title'].'</td><td>'.$movieJson['Released'].'</td><td>'.$movieJson['Runtime'].'</td></tr>'; 
                };
            ?>
        </table>`
    </div>
</body>
</html>

