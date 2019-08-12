<?php
// error_reporting(1);
// ini_set('display_errors', true);

$colours = ['Blue', 'Red', 'Green', 'Yellow'];

function firstColour($title, $colourArray) {
    echo $title;
    $lowCol = "";
    $lowPos = 50;

    foreach ($colourArray as $colour) {
        
        $loc = strpos($title, $colour, 0);
        if ($loc == false) {
            //do nothing
        } else {
            echo "'$colour' exists at '$loc' with previous pos $lowPos \n";
            if ($loc < $lowPos){
                $lowCol = $colour;
                $lowPos = $loc;
            }

        }
    }
    return $lowCol;
}



$moviesArray=array();

$tCol = "Red White & Blue";
print_r(firstColour($tCol, $colours));

foreach ($colours as $colour) {
    $url = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=".$colours[0]."&page=21");
    // $url = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=blue+red");
    $json = json_decode($url, true);

    //print_r($json);



    foreach ($json['Search'] as $movies) {   
        
        $firstColour = firstColour(" ".$movies['Title'], $colours);
        $movies += ['colours' => $firstColour];
        
        array_push($moviesArray, $movies );
    };


    // print_r($moviesArray);

    // $movie_title = $json['Rated'];

    // echo $movie_title;
}



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Colourful Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <h1>Colourful Movies</h1>
</head>
<body>
<table id="movieTable">
    <tr>
        <th>Colour</th>
        <th>Movie Title</th>
        <th>Release</th>
        <th>Runtime</th>
    </tr>
    <?php
        
        foreach ($moviesArray as $movie){
            //$movieUrl = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&i=".$movie['imdbID']."&type=movie");
            //$movieJson = json_decode($movieUrl, true);
            //print_r($movieJson);
            echo '<tr><td bgcolor="'.$movie['colours'].'">'. $movie['colours'] .'</td> <td>'.$movie['Title'] .'</td>'; /* <td>'.$movieJson['Released'] .'</td><td>'.$movieJson['Runtime'] .'</td></tr>'; */
        };
    ?>
</table>
    
</body>
</html>

