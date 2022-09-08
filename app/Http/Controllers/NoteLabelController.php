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

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $noteLabels = NoteLabel::with('note', 'label')->orderBy('position')->get()
                ->where('note.user_id', '=', 2)
                ->where('label_id', '=', $request->id);

            return DataTables::of($noteLabels)
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->note->id . '" data-title="' . $row->note->title . '">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->only(['id', 'position', 'note.id', 'note.user_id', 'note.title', 'note.body', 'label.name', 'action'])
                ->toJson();
        }
        abort(403);
    }

    public function savePositions(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->data;
            foreach ($data as $dt) {
                NoteLabel::where('id', $dt['id'])->update(['position' => $dt['position']]);
            }
            return 1;
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
