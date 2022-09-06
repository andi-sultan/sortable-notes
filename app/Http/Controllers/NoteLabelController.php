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
            $noteLabels = NoteLabel::with('note', 'label')->get();

            return DataTables::of($noteLabels)
                ->addIndexColumn()
                // ->filter(function ($query) use ($request) {
                //     $keyword = $request->get('search')['value'];
                //     $query->only('id', 'title', 'body', 'name');
                //     if (!empty($request->get('search')) && $keyword != '') {
                //         $query->where(function ($q) use ($keyword) {
                //             $q->where('title', 'like', '%' . $keyword . '%');
                //             $q->orWhere('body', 'like', '%' . $keyword . '%');
                //         });
                //     }
                // })
                ->only(['id','title','body','name'])
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->note->id . '" data-title="' . $row->note->title . '">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        abort(403);
    }
    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $noteLabels = NoteLabel::query()
    //             ->join('notes', 'note_labels.note_id', '=', 'notes.id')
    //             ->join('labels', 'note_labels.label_id', '=', 'labels.id')
    //             ->where('label_id', 'like', $request->id);

    //         return DataTables::eloquent($noteLabels)
    //             ->addIndexColumn()
    //             ->filter(function ($query) use ($request) {
    //                 $keyword = $request->get('search')['value'];
    //                 $query->select('notes.id', 'notes.title', 'notes.body', 'labels.name');
    //                 if (!empty($request->get('search')) && $keyword != '') {
    //                     $query->where(function ($q) use ($keyword) {
    //                         $q->where('notes.title', 'like', '%' . $keyword . '%');
    //                         $q->orWhere('notes.body', 'like', '%' . $keyword . '%');
    //                     });
    //                 }
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
    //                 $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '" data-title="' . $row->title . '">Delete</button>';
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
