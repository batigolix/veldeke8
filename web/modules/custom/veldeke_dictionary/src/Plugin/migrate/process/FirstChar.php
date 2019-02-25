<?php
/**
 * @file
 * Contains Drupal\veldeke_dictionary\Plugin\migrate\process\FirstChar
 */
namespace Drupal\veldeke_dictionary\Plugin\migrate\process;
//use Drupal\migrate\Plugin\migrate\source\SqlBase;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
/**
 * Process plugin for First char.
 *
 * @MigrateProcessPlugin(
 *   id = "first_char"
 * )
 */
class FirstChar extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
//    dd($value);
    $term = strtolower($this->remove_accents($value));
    $first_char = $term[0];

//    dd($first_char);

    return $first_char;
  }

  /**
   * Removes accents.
   */
  function remove_accents($str) {
    $from = array(
      "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï",
      "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â",
      "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô",
      "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç",
    );
    $to = array(
      "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i",
      "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A",
      "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O",
      "O", "O", "U", "U", "U", "U", "C",
    );
    $str = str_replace($from, $to, $str);
    return $str;
  }


}