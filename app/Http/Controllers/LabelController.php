<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.labels', ['title' => 'Labels']);
    }

    public function getLabels(Request $request)
    {
        if ($request->ajax()) {
            $label = Label::query();

            return DataTables::eloquent($label)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    $keyword = $request->get('search')['value'];
                    $query->select('id', 'name')
                        ->where('name', 'like', '%' . $keyword . '%');
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(['name' => 'required']);
        $validatedData['user_id'] = 2;
        $label = Label::create($validatedData);
        $lastInsertedId = $label::orderBy('id', 'DESC')->first()->id;

        echo json_encode(['lastId' => $lastInsertedId]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        $data = $label->only('id', 'name');
        echo json_encode($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $validatedData = $request->validate(['name' => 'required']);
        Label::where('id', $label->id)->update($validatedData);
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        Label::destroy($label->id);
        return true;
    }
}
