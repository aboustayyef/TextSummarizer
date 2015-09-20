<?php

require_once('vendor/autoload.php');

$document = new Aboustayyef\Summarizer;
 
$document->text = <<<EOD
This is where you put a text. It can include <em>tags</em>. But they will be removed.
EOD;
echo $document->summarize(2); // number is sentence limit

// 

// $sentences = $document->sentences;

// $document->top_scoring_sentences(5);

// echo "Sorted in order of appearance: \n";
// $orderOfAppearance = $document->top_scoring_sentences(5);
// var_dump($orderOfAppearance);

// echo "Sorted in order of strength: \n";
// var_dump($document->top_scoring_sentences(5,false));

// $paragraph = "";
// foreach ($orderOfAppearance as $sentence => $position) {
// 	$paragraph .= $sentence;
// }
// echo "$paragraph \n";

?>
