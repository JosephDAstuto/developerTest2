<?php
//This is just a little play around /  version upgrade of the previous, i thought of it a little bit late but thought it would be cool

/*
    To explain a little better
    i was unsure of the brief and while i though that getting each colour and concatenating them would work 
    i wanted to give a little more functionality
    i gave myself 2 hours to work on this before either cutting the lose or submitting thankfully i thought i did well
    Its just something extra i thought to do
*/

// error_reporting(1);
// ini_set('display_errors', true);

$colours = ['Blue', 'Red', 'Green', 'Yellow'];
// echo $_GET['col'];


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

function performSearch($colourArray){
    $moviesArray=array();
    $colours = ['Blue', 'Red', 'Green', 'Yellow'];
    $link = "http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=";
    $colourLink = "";
    $lastColour = end($colourArray);
    foreach ($colourArray as $colour){
        if (in_array($colour, $colours)) {
            if ($colour !== $lastColour){
                $link .= $colour."+";
                $colourLink .= $colour."+";
            } else {
                $link .= $colour;
                $colourLink .= $colour;
            }
            
        }
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $url = curl_exec($ch);

    $json = json_decode($url, true);

    // print_r($json);

    //if search is invalid let user know
    if ($json['Response'] == "False"){
        echo "Something went wrong in finding the movies: ".$json['Error'];
    } else {

        //else for each result append the first colour and add to the movie array
        foreach ($json['Search'] as $movies) {   
            
            $firstColour = firstColour(" ".$movies['Title'], $colours);
            $movies += ['colours' => $firstColour];
            
            array_push($moviesArray, $movies );

            
        };

    }

    $_GET['col'] = $colourLink ;
    header("Location: ". $_SERVER['REDIRECT_URI'] . '?' . http_build_query($_GET));

}

//foreach ($colours as $colour) {
if (isset($_GET['col'])){
 
    $coloursToGet = $_GET['col'];
    $link = "http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=".$coloursToGet."&page=1";
    //Below for testing multiple colours best outline for multple colours and showing the firstColour() funciton
    //$link = "http://www.omdbapi.com/?apikey=b5f9ff72&type=movie&s=blue+green";
    //$url = file_get_contents($link);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $url = curl_exec($ch);

    $json = json_decode($url, true);

    // print_r($json);

    //if search is invalid let user know
    if ($json['Response'] == "False"){
        echo "Something went wrong in finding the movies: ".$json['Error'];
    } else {

        //else for each result append the first colour and add to the movie array
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
        <form  method="post">
            <label class="Red"><input class="checkbox" name="check_list[]" value="Red" type="checkbox" />Red </label>
            <label class="Blue"><input class="checkbox" name="check_list[]" value="Blue" type="checkbox" />Blue </label>
            <label class="Green"><input class="checkbox" name="check_list[]" value="Green" type="checkbox" />Green </label>
            <label class="Yellow"><input class="checkbox" name="check_list[]" value="Yellow" type="checkbox" />Yellow </label>
            <br>
            <input type="submit" name="submit" value="Submit"/>
            <?php
            //submit form and reload page
                if(isset($_POST['submit'])){
                    if(!empty($_POST['check_list'])) {
                        // Counting number of checked checkboxes.
                        $checked_count = count($_POST['check_list']);
                        echo "You have selected following ".$checked_count." option(s): <br/>";
                        
                        $selectedColours = array();

                        foreach($_POST['check_list'] as $selected) {
                            echo "<p>".$selected ."</p>";
                            array_push($selectedColours, $selected);
                        }
                        performSearch($selectedColours);
                        
                    } else {
                        echo "<b>Please Select Atleast One Option.</b>";
                    }
                }
            ?>
        </form>
        <table id="movieTable">
            <tr>
                <th>Colour</th>
                <th>Movie Title</th>
                <th>Release</th>
                <th>Runtime</th>
            </tr>
            <?php
                foreach ($moviesArray as $movie){
                    //get the details of each movie inluding release date and runtime
                    $movieUrl = file_get_contents("http://www.omdbapi.com/?apikey=b5f9ff72&i=".$movie['imdbID']."&type=movie");
                    $movieJson = json_decode($movieUrl, true);
                    echo '<tr><td><span class="dot_'.$movie['colours'].'"></span></td> <td>'.$movie['Title'].'</td><td>'.$movieJson['Released'].'</td><td>'.$movieJson['Runtime'].'</td></tr>'; 
                };
            ?>
        </table>`
    </div>
</body>
</html>