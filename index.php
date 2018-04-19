<?php
declare(strict_types=1);

error_reporting(-1);
ini_set('display_errors', 'On');

require_once 'src/MinQueue.php';
require_once 'src/Dijkstra.php';
require_once 'src/Maze.php';

foreach (glob('./mazes/*.txt') as $file) {
    $maze = Maze::fromString(file_get_contents($file));

    $start = $maze->find('S');
    $goal = $maze->find('T');

    $helper = new Dijkstra(
        function ($a) use ($maze) {
            return $maze->getNeighbors($a, ['W']);
        },
        function ($a, $b) use ($maze) {
            return $maze->getDistance($a, $b);
        }
    );

    $tStart = microtime(true);
    $path = $helper->findPath($start, $goal);
    $tEnd = microtime(true);

    $mazeStrWithPath = $maze->toString(function ($tile) use ($path) {
        return in_array($tile, $path, true) && !in_array($tile->value, ['S', 'T'])
            ? '.'
            : $tile->value
            ;
    });

    printf("%s:\n%s\nin: %.5fs\n\n", $file, $mazeStrWithPath, $tEnd - $tStart);
}
