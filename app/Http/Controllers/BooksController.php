<?php

namespace App\Http\Controllers;

use App\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function getBooksJson () {
        $data = Book::getQuery();

        if (request()->filled('query') && request('query')) {
            $data = $data->where('isbn', 'LIKE', '%'.request('query').'%');
            $data = $data->orWhere('title', 'LIKE', '%'.request('query').'%');
        }

        $count = $data->count();

        $data->limit(request('limit'))
            ->skip(request('limit') * (request('page') - 1));

        if (isset(request()->orderBy)) {
            $direction = request()->ascending == 1 ? 'ASC' : 'DESC';
            $data->orderBy(request()->orderBy, $direction);
        }

        $results = $data->get()->toArray();

        return collect([
            'data' => $results,
            'count' => $count,
        ]);
    }
}
