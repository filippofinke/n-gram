# n-gram based text categorization
---

## Description
Implementation of the [N-Gram-Based Text Categorization Research](research.pdf) written in PHP.

This implementation allows you to categorize news articles by category:
- business
- entertainment
- politics
- sport
- tech

## Create a profile
```php
<?php
use FilippoFinke\Categorizer;
require __DIR__ . '/vendor/autoload.php';

// Raw data used for training
$rawData = 'fileName.txt';
// N-Grams to keep
$grams = 500;
// Create a profile based on the training data
$category = Categorizer::createProfile($rawData, 'profile_name', $grams);
// Save the profile to disk
$category->save('destination/name.profile');
```

## Categorize news
```php
<?php
use FilippoFinke\Profile;
use FilippoFinke\Categorizer;
require __DIR__ . '/vendor/autoload.php';

$cateogories = array();
$cateogories[] = Profile::load('profiles/cat1.profile');
$cateogories[] = Profile::load('profiles/cat2.profile');
$cateogories[] = Profile::load('profiles/cat3.profile');

// File to categorize
$file = 'news.txt';
// Get the category
$result = Categorizer::categorize($file, $cateogories);
// Print the category name
echo $result->getName().PHP_EOL;
```

## Example of accuracy
```
➡️ Loaded category business from file business.txt!
➡️ Loaded category entertainment from file entertainment.txt!
➡️ Loaded category politics from file politics.txt!
➡️ Loaded category sport from file sport.txt!
➡️ Loaded category tech from file tech.txt!
➡️ Loaded 5 models!
➡️ Testing files for category business: 60!
➡️ Testing files for category entertainment: 66!
➡️ Testing files for category politics: 47!
➡️ Testing files for category sport: 61!
➡️ Testing files for category tech: 51!
Total files: 285, Right guesses: 275, Wrong guesses: 10
Accuracy: 96%
⏱️ Took 12256 milliseconds
```

## Dataset used
https://www.bbc.co.uk/blogs/bbcbackstage/dataset