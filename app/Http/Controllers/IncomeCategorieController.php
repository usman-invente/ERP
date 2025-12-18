<?php

namespace App\Http\Controllers;

use App\IncomeCategorie;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IncomeCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $income_category = IncomeCategorie::where('business_id', $business_id)
                        ->select(['name', 'code', 'id', 'parent_id']);

            return Datatables::of($income_category)
                ->addColumn(
                    'action',
                    '<button data-href="{{action(\'App\Http\Controllers\IncomeCategorieController@edit\', [$id])}}" class="btn btn-xs btn-primary btn-modal" data-container=".income_category_modal"><i class="glyphicon glyphicon-edit"></i>  @lang("messages.edit")</button>
                        &nbsp;
                        <button data-href="{{action(\'App\Http\Controllers\IncomeCategorieController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_income_category"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>'
                )
                ->editColumn('name', function ($row) {
                    if (! empty($row->parent_id)) {
                        return '--'.$row->name;
                    } else {
                        return $row->name;
                    }
                })
                ->removeColumn('id')
                ->removeColumn('parent_id')
                ->rawColumns([2])
                ->make(false);
        }

        return view('income_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        $categories = IncomeCategorie::where('business_id', $business_id)
                        ->whereNull('parent_id')
                        ->pluck('name', 'id');

        return view('income_category.create')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only(['name', 'code']);
            $input['business_id'] = $request->session()->get('user.business_id');

            if (! empty($request->input('add_as_sub_cat')) && $request->input('add_as_sub_cat') == 1 && ! empty($request->input('parent_id'))) {
                $input['parent_id'] = $request->input('parent_id');
            }

            IncomeCategorie::create($input);
            $output = ['success' => true,
                'msg' => __('income.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $income_category = IncomeCategorie::where('business_id', $business_id)->find($id);

            $categories = IncomeCategorie::where('business_id', $business_id)
                        ->whereNull('parent_id')
                        ->pluck('name', 'id');

            return view('income_category.edit')
                    ->with(compact('income_category', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'code']);
                $business_id = $request->session()->get('user.business_id');

                $income_category = IncomeCategorie::where('business_id', $business_id)->findOrFail($id);
                $income_category->name = $input['name'];
                $income_category->code = $input['code'];

                if (! empty($request->input('add_as_sub_cat')) && $request->input('add_as_sub_cat') == 1 && ! empty($request->input('parent_id'))) {
                    $income_category->parent_id = $request->input('parent_id');
                } else {
                    $income_category->parent_id = null;
                }

                $income_category->save();

                $output = ['success' => true,
                    'msg' => __('income.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $income_category = IncomeCategorie::where('business_id', $business_id)->findOrFail($id);
                $income_category->delete();

                //delete sub categories also
                IncomeCategorie::where('business_id', $business_id)->where('parent_id', $id)->delete();

                $output = ['success' => true,
                    'msg' => __('income.deleted_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
}
