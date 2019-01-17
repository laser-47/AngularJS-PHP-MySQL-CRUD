<?php

//insert.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$error = '';
$message = '';
$validation_error = '';
$filmname = '';
$genre = '';
$studio ='';
$director ='';
$producer ='';
$lactor ='';


if($form_data->action == 'fetch_single_data')
{
	$query = "SELECT * FROM imtable WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['filmname'] = $row['filmname'];
		$output['genre'] = $row['genre'];
		$output['studio'] = $row['studio'];
		$output['director'] = $row['director'];
		$output['producer'] = $row['producer'];
		$output['lactor'] = $row['lactor'];

	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM imtable WHERE id='".$form_data->id."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Data Deleted';
	}
}
else
{
	if(empty($form_data->filmname))
	{
		$error[] = 'Film Name is Required';
	}
	else
	{
		$filmname = $form_data->filmname;
	}

	if(empty($form_data->genre))
	{
		$error[] = 'Genre type is Required';
	}
	else
	{
		$genre = $form_data->genre;
	}

	if(empty($form_data->studio))
	{
		$error[] = 'Studio Name is Required';
	}
	else
	{
		$studio = $form_data->studio;
	}

	if(empty($form_data->director))
	{
		$error[] = 'Director name is Required';
	}
	else
	{
		$director = $form_data->director;
	}
	if(empty($form_data->producer))
	{
		$error[] = 'Producer Name is Required';
	}
	else
	{
		$producer = $form_data->producer;
	}

	if(empty($form_data->lactor))
	{
		$error[] = 'Leading actor name is Required';
	}
	else
	{
		$lactor = $form_data->lactor;
	}



	if(empty($error))
	{
		if($form_data->action == 'Insert')
		{
			$data = array(
				':filmname'		=>	$filmname,
				':genre'		=>	$genre,
				':studio'		=> 	$studio,
				':director'		=> 	$director,
				':producer'		=>	$producer,
				':lactor'		=>	$lactor
			);
			$query = "
			INSERT INTO imtable 
				(filmname, genre, studio, director, producer, lactor) VALUES 
				(:filmname, :genre, :studio, :director, :producer, :lactor)
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Data Inserted';
			}
		}
		if($form_data->action == 'Edit')
		{
			$data = array(
				':filmname'		=>	$filmname,
				':genre'		=>	$genre,
				':studio'		=> 	$studio,
				':director'		=> 	$director,
				':producer'		=>	$producer,
				':lactor'		=>	$lactor,
				':id'			=>	$form_data->id
			);
			$query = "
			UPDATE imtable 
			SET filmname = :filmname, genre = :genre, studio = :studio, director = :director, producer = :producer, lactor = :lactor 
			WHERE id = :id
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Data Edited';
			}
		}
	}
	else
	{
		$validation_error = implode(", ", $error);
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}



echo json_encode($output);

?>