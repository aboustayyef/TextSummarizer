<?php namespace Aboustayyef;
/**
 *  Extracts the content
 */
class Summarizer
{
  
  public $text;
  protected $sentences = [];

  function __construct() {
    #nothing for now
  }

  public function summarize($howmany = 3){
    $this->text = strip_tags($this->text);
    $this->text = html_entity_decode($this->text, ENT_NOQUOTES);
    $this->extractSentences();
    $this->scoreSentences();
    $paragraph = '';
    $index = 0;
    foreach ($this->top_scoring_sentences() as $sentence => $value) {
      $paragraph .= $sentence . ' ';
      $index += 1;
      if ($index == $howmany) {
        return $paragraph;
      }
    }
    
  }

// Helper functions

  public function extractSentences(){

      // Returns a collection of Keyphrases;

      // split by punctuation delimiters into sentences
      $pattern =  '/(?<=[.?!;])\s*/';
      $sentences = preg_split( $pattern, $this->text );
      foreach ($sentences as $key => $sentence) {
        array_push($this->sentences, ['sentence' => trim($this->removeWhiteSpaces($sentence)), 'order' => $key, 'score' => 0]);
      }
  }

  public function scoreSentences(){

    foreach ($this->sentences as $key1 => $sentence1) {
      $score = 0;
      foreach ($this->sentences as $key2 => $sentence2) {
        if ($sentence1['sentence'] === $sentence2['sentence']) {
          continue;
        }
        $score += $this->compare($sentence1['sentence'], $sentence2['sentence']);        
      }
      $this->sentences[$key1]['score'] = $score;
    }
    // array_multisort($sentenceScores);
    // $sentenceScores = array_reverse($sentenceScores);
  }

  public function removeWhiteSpaces($text){
    return preg_replace("#\\s+#um", " ", $text);
  }

  public function split_sentence_into_words($sentence){
    $raw = preg_split('#\s+#', $sentence);
    $result = [];
    foreach ($raw as $key => $word) {
      if (strlen(trim($word)) > 0) {
        $result[] = \Porter::stem($word);
      }
    }
    return $result;
  }

  public function compare($sentence1, $sentence2){

    $words1 = $this->split_sentence_into_words(strtolower($sentence1));
    $words2 = $this->split_sentence_into_words(strtolower($sentence2));
   
    $union = array_unique(array_intersect($words1, $words2));

    $combination = array_unique(array_merge($words1, $words2));
    if ((count($words1) < 3) || (count($words2) < 3)) {
      # sentences too short, 
      return 0;
    }
    $jaccard = count($union)/count($combination);
    return $jaccard;
  }

  public function top_scoring_sentences($howmany = 5, $sorted = true){
    $scored = [];
    foreach ($this->sentences as $key => $sentence) {
      $scored[$sentence['sentence']] = $sentence['score'];
    }
    asort($scored);
    $scored = array_reverse($scored);

    // we no how the top scoring phrases in order of strength;
    $topscoring = array_slice($scored, 0, $howmany);


    // now we order them in order of appearance;
    $inorder = [];
    foreach ($topscoring as $phrase => $score) {
      foreach ($this->sentences as $key => $sentence) {
        if ($phrase == $sentence['sentence']) {
          $inorder[$phrase] = $sentence['order'];
          continue;
        }
      }
    }

    asort($inorder);
    $topscoringSorted = $inorder;

    if ($sorted) {
      return $topscoringSorted;
    } else {
      return $topscoring;
    }

  }

}



?>
