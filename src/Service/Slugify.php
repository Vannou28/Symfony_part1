<?php

namespace App\Service;


class Slugify
{

    const SPACE = " ";
  /*  private string $input;
    
    public function __construct(string $input)
    {
        $this->setInput($input);
    }

    public function generate(): string
    {   

        $this->setInput( iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $this->input ));   //retire les accents
        $this->setInput(preg_replace("/[^a-zA-Z0-9]/"," ",$this->input )); //garde que les chiffres
        $this->setInput(preg_replace('/\s\s+/', ' ', $this->input )); //retire les espace en trop
        $this->setInput(str_replace(self::SPACE, "-", $this->input ));   //remplace les espaces en -
        return $this->input;
    }

    public function setInput($input)
    {
        $this->input = $input;
    }

    public function getInput(): string
    {
        return $thos->input;
    }*/

        public function generate($input): string
    {   

        $input = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $input);   //retire les accents
        $input = preg_replace("/[^a-zA-Z0-9]/"," ", $input); //garde que les chiffres
        $input = preg_replace('/\s\s+/', ' ', $input); //retire les espace en trop
        $input = str_replace(self::SPACE, "-",$input);   //remplace les espaces en -
        $input = strtolower ($input);
        return $input;
    }
}