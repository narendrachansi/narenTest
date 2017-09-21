<?php

define('BASE_PATH',__DIR__.DIRECTORY_SEPARATOR);


function readline( $prompt = '' )
{
    echo $prompt;
    return rtrim( fgets( STDIN ), "\n" );
}


/**
 * debug
 */
function r($data){
	print_r($data);
}


// input file name
$fridgeDataFileName = trim(readline("Enter Friddge Data file name :"));

$fridgeDataFileName = BASE_PATH.$fridgeDataFileName;


// read data from given file
if(file_exists($fridgeDataFileName)) {
	$frideData = array_map('str_getcsv', file($fridgeDataFileName));	
} else {
	exit("$fridgeDataFileName file not found!");
}


$recipeFileName = trim(readline("Enter Recipe Data file name :"));
$recipeFileName = BASE_PATH.$recipeFileName;

// read data from given file
if(file_exists($recipeFileName)) {
	$string = file_get_contents($recipeFileName);
	$recipeData = json_decode($string, true);
} else {
	exit("$recipeFileName file not found!");
}




// sort by used by date asc



foreach($frideData as $key=>$item){
	
	
	
	// scan and skip expired items
	

	$datePart = array_reverse(explode('/', $item[3]));
	
	$useByDate = date('Y-m-d', strtotime(implode('-', $datePart)));
	if($useByDate<date('Y-m-d')) {
		// skip unusable item
		
		echo "This ingredient can not be used :";
		r($frideData[$key]);
		
		unset($frideData[$key]);
		continue;
	}
	
	
	$frideData[$key][3] = $useByDate;
	
	
}


// sort by used by date
function compareByUseDate($a, $b)
{
    if ($a[3] == $b[3]) {
        return 0;
    }
    return ($a[3] < $b[3]) ? -1 : 1;
}


//r($frideData);
usort($frideData, "compareByUseDate");


echo "\n Prefered Item to cook tonight :\n";
$preferedItemToCook = $frideData[0];
r($preferedItemToCook);


$preferedItemsIngredients = explode(' ', $preferedItemToCook[0]);



// check if recipes exist for prefered item

$ingredentFound = array();
foreach($recipeData as $key=>$item){
	foreach($item['ingredients'] as $k=> $v){
		$ingredients = array_column($v,'item');
		
		$matchingIngredients = array_intersect($preferedItemsIngredients, $ingredients);
		
		if(count($matchingIngredients)==count($preferedItemsIngredients)){
			$ingredentFound[] = 1;
		}
	}
}


		
if(count($ingredentFound)){
		
	echo "\n Matching Ingredients ".implode(',',preferedItemsIngredients)." found, start cooking!";
	r($preferedItemToCook);
	
} else {
	echo "\nMatching Ingredients not found.\n";
	echo "\nOrder Takeout\n";
}

exit("........................");



