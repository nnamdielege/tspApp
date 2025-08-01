# Nearest Neighbour Heuristic for Traveling Salesman Problem with Google Maps Distance Matrix API

This repository provides an implementation of the nearest neighbour heuristic algorithm in PHP for solving the Traveling Salesman Problem (TSP) using data from Google Maps Distance Matrix API.

---

## 🚀 Live Demo

You can try out the live demo of the TSP Nearest Neighbour solution here:

👉 **[View Live Demo](https://tspapp.boundlessanalytics.com.au)**

---

## Introduction

The Traveling Salesman Problem (TSP) is a classic problem in computer science and optimization. Given a list of cities and the distances between each pair of cities, the task is to find the shortest possible route that visits each city exactly once and returns to the starting city.

The nearest neighbour heuristic is a simple and effective algorithm for finding an approximate solution to the TSP. It starts from an arbitrary city and repeatedly selects the nearest unvisited city as the next destination until all cities are visited, forming a tour.

## Google Maps Distance Matrix API

Google Maps Distance Matrix API provides the distance and travel time between multiple origins and destinations. You can use this API to retrieve the distance matrix between cities, which serves as input for the TSP solver.

To use the Google Maps Distance Matrix API:

1. [Get an API key](https://developers.google.com/maps/documentation/distance-matrix/get-api-key) from the Google Cloud Console.
2. Construct a request specifying the origins and destinations (cities) for which you want to retrieve distances.
3. Parse the response to extract the distance matrix.

## Algorithm Overview

1. **Initialization**: Choose a starting city.
2. **Main Loop**:
    - Repeat until all cities are visited:
        1. Find the nearest unvisited city to the current city.
        2. Add it to the tour and mark it as visited.
3. **Completion**: Return to the starting city to complete the tour.
4. **Output**: The tour formed is an approximate solution to the TSP.

## Philosophy and Example

Suppose we have 5 cities: A, B, C, D, and E, and we want to find the shortest route that visits each city exactly once and returns to the starting city. We represent the distances between these cities in a matrix:

<!DOCTYPE html>
<html lang="en">
<body>
    <h2>Distance Matrix for the 5 cities are: </h2>
    <table>
        <tr>
            <th></th>
            <th>A</th>
            <th>B</th>
            <th>C</th>
            <th>D</th>
            <th>E</th>
        </tr>
        <tr>
            <td><b>A</b></td>
            <td>0</td>
            <td>3</td>
            <td>2</td>
            <td>4</td>
            <td>1</td>
        </tr>
        <tr>
            <td><b>B</b></td>
            <td>3</td>
            <td>0</td>
            <td>5</td>
            <td>2</td>
            <td>6</td>
        </tr>
        <tr>
            <td><b>C</b></td>
            <td>2</td>
            <td>5</td>
            <td>0</td>
            <td>3</td>
            <td>2</td>
        </tr>
        <tr>
            <td><b>D</b></td>
            <td>4</td>
            <td>2</td>
            <td>3</td>
            <td>0</td>
            <td>4</td>
        </tr>
        <tr>
            <td><b>E</b></td>
            <td>1</td>
            <td>6</td>
            <td>2</td>
            <td>4</td>
            <td>0</td>
        </tr>
    </table>

For example, the distance from city A to city B is 3, from A to C is 2, and so on.

Now, let's apply the nearest neighbour heuristic starting from city A.

Step 1:
Start from city A.
Visit the nearest unvisited city, which is city E (distance 1).

Step 2:
From city E, the nearest unvisited city is city C (distance 2).

Step 3:
From city C, the nearest unvisited city is city D (distance 3).

Step 4:
From city D, the nearest unvisited city is city B (distance 2).

Step 5:
From city B, the nearest unvisited city is city A (distance 3).

Tour:
The tour formed is: A -> E -> C -> D -> B -> A.

This tour visits each city exactly once and returns to the starting city.

Note:
The total distance of this tour is the sum of the distances between consecutive cities:
1 + 2 + 3 + 2 + 3 = 11

## Usage

1. Retrieve the distance matrix between cities using Google Maps Distance Matrix API.
2. Parse the response and extract the distances.
3. Call the `nearestNeighbour` method from the `TSPSolver` class, passing the distance matrix as an argument.
4. The method returns the tour representing an approximate solution to the TSP.

## Example

Suppose you want to find the shortest route between cities A, B, C, D, and E using Google Maps Distance Matrix API. After retrieving the distances, you can input them into the nearest neighbour heuristic algorithm to find an approximate solution to the TSP.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Created by Nnamdi Elege
