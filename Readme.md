#Summary

This is a utility class for extracting a summary from large texts. 

##Usage

```
<?php

$document = new Aboustayyef\Summarizer;
 
$document->text = <<<EOD
This is where you put a text. It can include <em>tags</em>. But they will be removed.
EOD;

echo $document->summarize(2); // number of sentences wanted

?>

```
