<?php
namespace FilippoFinke;

class Profile
{
    private $grams;

    private $name;

    public function getGrams()
    {
        return $this->grams;
    }

    public function getName()
    {
        return $this->name;
    }

    public function save($path)
    {
        file_put_contents($path, serialize($this));
    }

    public function __construct($grams, $name = null)
    {
        $this->grams = $grams;
        $this->name = $name;
    }

    public static function load($path)
    {
        return unserialize(file_get_contents($path));
    }
}
