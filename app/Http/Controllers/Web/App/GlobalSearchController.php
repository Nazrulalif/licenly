<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([]);
        }

        // Use Scout search with TNT Search
        $users = User::search($query)->take(5)->get();

        $results = collect();

        // Map users
        $results = $results->concat(
            $users->map(function ($i) use ($query) {
                $match_details = '';
                $roleName = $i->getRoleName();

                // Check if role name matches the search query
                if (stripos($roleName, $query) !== false) {
                    $match_details = 'Role: ' . $this->highlight($roleName, $query);
                }

                // Check if email matches
                if (stripos($i->email, $query) !== false && empty($match_details)) {
                    $match_details = 'Email: ' . $this->highlight($i->email, $query);
                }

                return [
                    'group' => 'User',
                    'title' => $i->name,
                    'highlighted' => $this->highlight($i->name, $query),
                    'match_info' => $match_details,
                    'url' => route('users.edit', $i->id),
                ];
            }),
        );

        return response()->json($results);
    }

    public function highlight($text, $query)
    {
        $escapedText = e($text); // Escape HTML
        $escapedQuery = preg_quote($query, '/');

        // Check if query is empty to avoid errors with empty pattern
        if (empty($escapedQuery)) {
            return $escapedText;
        }

        return preg_replace("/($escapedQuery)/i", '<strong style="color: orange;">$1</strong>', $escapedText);
    }
}
