<?php

namespace App\Services;

class TSPSolver
{
    public static function nearestNeighbour($points)
    {
        $numPoints = count($points);
        $visited = array_fill(0, $numPoints, false);
        $tour = [];
        $currentPoint = 0; // Start from the first point

        $tour[] = $currentPoint;
        $visited[$currentPoint] = true;

        for ($i = 0; $i < $numPoints - 1; $i++) {
            $nearestNeighbor = null;
            $nearestDistance = INF;

            // Find the nearest unvisited neighbor
            for ($j = 0; $j < $numPoints; $j++) {
                if (!$visited[$j] && $points[$currentPoint][$j] < $nearestDistance) {
                    $nearestNeighbor = $j;
                    $nearestDistance = $points[$currentPoint][$j];
                }
            }

            // Mark the nearest neighbor as visited and add it to the tour
            $visited[$nearestNeighbor] = true;
            $tour[] = $nearestNeighbor;
            $currentPoint = $nearestNeighbor;
        }

        // Complete the tour by returning to the starting point
        $tour[] = 0;

        return $tour;
    }
}