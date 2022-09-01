<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.notes', ['title' => 'Notes']);
    }

    public function getNotes(Request $request)
    {
        if ($request->ajax()) {
            $note = Note::query();

            return DataTables::eloquent($note)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    $keyword = $request->get('search')['value'];
                    $query->select('id', 'title', 'body');
                    $query->where('user_id', 2);
                    if (!empty($request->get('search')) && $keyword != '') {
                        $query->where(function ($q) use ($keyword) {
                            $q->where('title', 'like', '%' . $keyword . '%');
                            $q->orWhere('body', 'like', '%' . $keyword . '%');
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="btn btn-sm btn-primary btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')">Edit</button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Delete</button>';
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        $data = $note->only('id', 'title', 'body');
        echo json_encode($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $validatedData = $request->validate(['body' => 'required']);
        $validatedData['title'] = $request->title;
        $note::where('id', $request->id)->update($validatedData);

        echo json_encode(array('statusCode' => 200));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        //
    }
}
