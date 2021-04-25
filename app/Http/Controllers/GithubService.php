<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubService extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response = Http::get('https://api.github.com/search/repositories', [
            'sort' => 'stars',
            'order' => 'desc',
            'q' => 'created:>2021-03-25',
            'per_page' => '100'
        ]);
        $json = json_decode($response,true);
        $repos = collect($json['items']);
        $languagesHaveRepos = $repos->groupBy('language')->map(function ($value) {
            return[ 'No. of repos' => $value->count(), 'repos' => $value->map(function ($value) {
                    $newValue = collect($value)->only(['name', 'url', 'description']);
                    return $newValue;
                })
            ];
        });
        return $languagesHaveRepos;
    }
}
