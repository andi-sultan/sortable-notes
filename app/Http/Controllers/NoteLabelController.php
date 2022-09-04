<?php

namespace App\Http\Controllers;

use App\Models\NoteLabel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NoteLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.note_labels', ['title' => 'Notes in Label']);
    }

    public function viewNotesByLabel(Request $request)
    {
        return view('pages.note_labels', [
            'title' => 'Notes in Label',
            'labelId' => $request->id
        ]);
    }
    // public function viewNotesByLabel(Request $request)
    // {

    //     if ($request->ajax()) {
    //         $noteLabels = NoteLabel::query()
    //             ->where('label_id', 'like', 2)
    //             ->where('user_id', 'like', 2);

    //         return DataTables::eloquent($noteLabels)
    //             ->addIndexColumn()
    //             ->filter(function ($query) use ($request) {
    //                 $keyword = $request->get('search')['value'];
    //                 $query->select('id', 'name')
    //                     ->where('name', 'like', '%' . $keyword . '%');
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
    //                 $actionBtn .= '<a href="' . url('notes') . '/' . $row->id . '" class="btn btn-sm btn-success ml-1">View Notes</a>';
    //                 $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete ml-1" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>';
    //                 return $actionBtn;
    //             })
    //             ->rawColumns(['action'])
    //             ->toJson();
    //     }
    //     abort(403);
    // }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoteLabel  $noteLabel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, NoteLabel $noteLabel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoteLabel  $noteLabel
     * @return \Illuminate\Http\Response
     */
    public function edit(NoteLabel $noteLabel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoteLabel  $noteLabel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NoteLabel $noteLabel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoteLabel  $noteLabel
     * @return \Illuminate\Http\Response
     */
    public function destroy(NoteLabel $noteLabel)
    {
        //
    }
}
