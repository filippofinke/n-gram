<?php
namespace FilippoFinke;

use FilippoFinke\Profile;

class Categorizer
{
    public static function categorize($file, $categories)
    {
        $fileProfile = self::createProfile($file);

        $bestCategory = null;
        $bestDistance = PHP_INT_MAX;
        foreach ($categories as $category) {
            $distance = self::calculateDistance($fileProfile, $category);
            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestCategory = $category;
            }
        }

        return $bestCategory;
    }

    public static function createProfile($file, $name = null, $toKeep = 500)
    {
        $content = file_get_contents($file);
        $content = strtoupper($content);
        $words = str_word_count($content, 1);
        $grams = array();

        foreach ($words as $word) {
            for ($n = 2; $n <= 5; $n++) {
                $currentGrams = self::toNGrams($word, $n);
                foreach ($currentGrams as $gram) {
                    if (isset($grams[$gram])) {
                        $grams[$gram] += 1;
                    } else {
                        $grams[$gram] = 1;
                    }
                }
            }
        }

        arsort($grams);

        $grams = array_slice($grams, 0, $toKeep);

        $keys = array();
        foreach ($grams as $gram => $count) {
            $keys[] = $gram;
        }
        
        $profile = new Profile($keys, $name);
        return $profile;
    }

    private static function calculateDistance($profile, $category)
    {
        $profileGrams = $profile->getGrams();
        $categoryGrams = $category->getGrams();

        $maxDistance = max(count($profileGrams), count($categoryGrams));

        $totalDistance = 0;

        foreach ($profileGrams as $pIndex => $profileGram) {
            foreach ($categoryGrams as $cIndex => $categoryGram) {
                if ($profileGram == $categoryGram) {
                    break;
                }
            }

            if ($cIndex) {
                $distance = abs($pIndex - $cIndex);
            } else {
                $distance = $maxDistance;
            }
            $totalDistance += $distance;
        }
        return $totalDistance;
    }

    private static function toNGrams($word, $n)
    {
        $grams = array();

        $word = '_' . $word;
        
        for ($i = 0; $i < strlen($word); $i++) {
            $gram = substr($word, $i, $n);
            $grams[] = str_pad($gram, $n, '_', STR_PAD_RIGHT);
        }

        return $grams;
    }
}
