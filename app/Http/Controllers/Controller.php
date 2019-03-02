<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getTablePaginate($request, $tableName, $filter = 'name') {
        $table = DB::table($tableName);
        if ($request->filter) {
            $table->where($filter, 'like' ,'%'.$request->filter.'%');
        }
        if ($request->sort) {
            $sort = explode("|", $request->sort);
            $table->orderBy($sort[0], $sort[1]);
        }
        return $table->paginate();
    }
}
