<?php
use FilippoFinke\Profile;
use FilippoFinke\Categorizer;

require __DIR__ . '/vendor/autoload.php';
$start = round(microtime(true) * 1000);

$cateogories = array();
$trainingFiles = glob(__DIR__ . '/data/training/*.txt');
foreach ($trainingFiles as $file) {
    $fileName = basename($file, '.txt');
    $modelFile = __DIR__ . '/data/models/'.$fileName.'.profile';
    if (file_exists($modelFile)) {
        $category = Profile::load($modelFile);
        printf("➡️ Loaded category %s from file %s!".PHP_EOL, $category->getName(), $fileName.'.txt');
        $cateogories[] = $category;
    } else {
        printf("➡️ Training model %s from file %s!".PHP_EOL, $fileName, $fileName.'.txt');
        $category = Categorizer::createProfile($file, $fileName, 2850);
        $category->save($modelFile);
        printf("➡️ Model saved to %s!".PHP_EOL, $modelFile);
        $cateogories[] = $category;
    }
}
printf("➡️ Loaded %d models!".PHP_EOL, count($cateogories));

$languages = array();
$langTraining = glob(__DIR__ . '/data/languages/*.txt');
foreach ($langTraining as $file) {
    $fileName = basename($file, '.txt');
    $modelFile = __DIR__ . '/data/models/'.$fileName.'.profile';
    if (file_exists($modelFile)) {
        $category = Profile::load($modelFile);
        printf("➡️ Loaded category %s from file %s!".PHP_EOL, $category->getName(), $fileName.'.txt');
        $languages[] = $category;
    } else {
        printf("➡️ Training model %s from file %s!".PHP_EOL, $fileName, $fileName.'.txt');
        $category = Categorizer::createProfile($file, $fileName, 300, 1, 5);
        $category->save($modelFile);
        printf("➡️ Model saved to %s!".PHP_EOL, $modelFile);
        $languages[] = $category;
    }
}
printf("➡️ Loaded %d languages!".PHP_EOL, count($languages));

$testDirs = scandir(__DIR__ . '/data/test');
$totalFiles = 0;
$rightFiles = 0;
$wrongFiles = 0;
foreach ($testDirs as $dir) {
    if ($dir == '.' || $dir == '..' || $dir == '.DS_Store') {
        continue;
    }
    $testFiles = glob(__DIR__ . '/data/test/'.$dir.'/*.txt');
    printf("➡️ Testing files for category %s: %d!".PHP_EOL, $dir, count($testFiles));
    $totalFiles += count($testFiles);
    foreach ($testFiles as $file) {
        $result = Categorizer::categorize($file, $cateogories);
        $language = Categorizer::categorize($file, $languages);
        printf('➡️ File: %s, Guessed: %s, Expected: %s, Language: %s'.PHP_EOL, basename($file), $result->getName(), $dir, $language->getName());
        if ($result->getName() == $dir || $language->getName() == $dir) {
            $rightFiles += 1;
        } else {
            $wrongFiles += 1;
        }
    }
}

$percent = $rightFiles / $totalFiles * 100;

printf("Total files: %d, Right guesses: %d, Wrong guesses: %d".PHP_EOL, $totalFiles, $rightFiles, $wrongFiles);
printf("Accuracy: %d%%".PHP_EOL, $percent);
printf("⏱️ Took %d milliseconds".PHP_EOL, round(microtime(true) * 1000) - $start);
