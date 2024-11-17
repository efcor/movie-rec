<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Top 500 in the movies table
    $movies = DB::table('movies')->where('timesRated', '>=', 10)->orderBy('rating', 'desc')->take(500)->get();

    // Get a random sample of 20 movies from the top 500
    $movies = $movies->shuffle()->take(20);
    $movieIds = $movies->pluck('movieId')->all();

    // Now simulate what I would do to determine a similar user to pass to the model:
    // select userId, sum(rating), count(*)
    // from ratings
    // where movieId in (2288,55118,116897,2731,3275,1704,54190,5903,104879,33166,1203,2010,4306,3260,905,1204,46578,1884,67255,6350)
    // and rating > 0.5
    // group by userId
    // order by sum(rating) desc
    $users = DB::table('ratings')
        ->select('userId', DB::raw('sum(rating) as sum_rating'), DB::raw('count(*) as num_rated'))
        ->whereIn('movieId', $movieIds)
        ->whereNotIn('userId', [414,599,474,448,274,610,68,380,606,288,249,387,182,307,603,298,177,318,232,480,608,600,483,590,105,19,305,489,111,438,217,140,477])
        ->where('rating', '>', 0.5)
        ->groupBy('userId')
        ->orderBy(DB::raw('sum(rating)'), 'desc')
        ->get();

    $user = $users[0];
    echo "$user->userId $user->sum_rating $user->num_rated<br>";
    return '';

    $similarUser = $users[0]->userId;

    return $similarUser;
});

// function csvToArray($filename) {
//     $rows = [];
//     $handle = fopen($filename, 'r');
//     if ($handle) {
//         while (($line = fgetcsv($handle)) !== FALSE) {
//             $rows[] = $line;
//         }
//         fclose($handle);
//     }
//     array_shift($rows);
//     return $rows;
// }

// populate ratings table
// Route::get('/', function () {
//     $ratings = csvToArray(base_path().'/../dataset/ratings-normalized.csv');

//     foreach ($ratings as $rating) {
//         DB::table('ratings')->insert([
//             'userId' => $rating[0],
//             'movieId' => $rating[1],
//             'rating' => $rating[2],
//         ]);
//     }
//     dd('done');
// });

// populate movies table
// Route::get('/', function () {
//     $movies = csvToArray(base_path().'/../dataset/movies2.csv');

//     foreach ($movies as $movie) {
//         DB::table('movies')->insert([
//             'movieId' => $movie[0],
//             'title' => $movie[1],
//             'genres' => $movie[2],
//             'rating' => $movie[3],
//             'timesRated' => $movie[4],
//         ]);
//     }
//     dd('done');
// });
