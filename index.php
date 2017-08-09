<?php

if(isset($_GET['input_url']))
	{
	$input_url = @$_GET['input_url'];

	$pattern = '/(id)[0-9]+/';
	preg_match($pattern, $input_url, $matches, PREG_OFFSET_CAPTURE);
	@$input = $matches[0][0];



	$pattern ='/([0-9])+/';
	preg_match($pattern, $input, $match, PREG_OFFSET_CAPTURE);
	@$id = $match[0][0];							//get id in url


	$apple_api_url = "https://itunes.apple.com/rss/customerreviews/id=$id/json";


	$review_data = (@json_decode(file_get_contents("$apple_api_url") , TRUE))['feed']['entry'];



	$i=0;
		if($review_data !='')
			{

			foreach(@$review_data as $key){ 
			if($i == 0)
				{
				$i++;
				continue; 
				}
				$username 			= 	$key['author']['name']['label'];
				$date_of_review		= 	'';
				$review_comment  	=	$key['content']['label'];
				$star_rating 		= 	$key['im:rating']['label'];
				$link_to_review 	= 	$key['link']['attributes']['href'];

				$smaller_data[]		= 	array(
											'username' 			=> $username ,
											'date'				=> $date_of_review , 
											'star_rating' 		=> $star_rating ,
											'review_comment' 	=> $review_comment,
											'link' 				=> $link_to_review
											);
			}


			$filename = 'review.csv';
			header("Content-type: text/csv");
			header("Content-Disposition: attachment;filename=$filename");
			$output = fopen("php://output", "w");
			$header = array_keys($smaller_data[0]);
			fputcsv($output, $header);

			foreach ($smaller_data as $row) {
				fputcsv($output, $row);
			}

				fclose($output);

			}else{

	echo "either url field is empty<br/> or url is not valid <br/>or api error <br/> or check your connection ";

				}


}


?>