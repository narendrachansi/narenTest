<?php
$recipes=array(array('name'=>'grilledcheeseontoast',
				'ingredients'=>array(array('item'=>'bread','amount'=>'2','unit'=>'slices'),array('item'=>'cheese','amount'=>'2','unit'=>'slices'))),
				array('name'=>'saladsandwich',
				'ingredients'=>array(array('item'=>'bread','amount'=>'2','unit'=>'slices'),array('item'=>'mixedsalad','amount'=>'200','unit'=>'grams')))
				);
echo json_encode($recipes);
?>