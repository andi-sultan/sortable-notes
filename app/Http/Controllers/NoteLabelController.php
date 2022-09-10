<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
                    $actionBtn = '<div class="d-flex align-items-center">';
                    $actionBtn .= '<button class="btn btn-sm btn-success ml-1 px-3 btn-edit" data-toggle="modal" data-target="#modal"
                                    onclick="editData(' . $row->note->id . ')" title="Edit"><i class="ion-edit"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger ml-1 px-3 btn-delete" data-id="' . $row->note->id . '" data-title="' . $row->note->title . '"
                                    title="Delete"><i class="ion-trash-b"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 d-flex align-items-center" data-toggle="modal" data-target="#modal"
                                    onclick="insertData(\'above\',' . $row->position . ')" title="Add above">
                                    <i class="ion-android-add-circle mr-2"></i><i class="ion-android-arrow-up"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 d-flex align-items-center" data-toggle="modal" data-target="#modal"
                                    onclick="insertData(\'below\',' . $row->position . ')" title="Add below">
                                    <i class="ion-android-add-circle mr-2"></i><i class="ion-android-arrow-down"></i></button>';
                    $actionBtn .= '</div>';
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
        $data = $request->data;

        // * get data from string
        parse_str($request->data, $data);

        $validator = Validator::make($data, [
            'body' => 'required',
            'label_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = $validator->errors();
        }

        DB::transaction(function () use ($data) {
            $noteData['user_id'] = 2;
            $noteData['title'] = $data['title'];
            $noteData['body'] = $data['body'];
            $note = Note::create($noteData);
            $lastInsertedId = $note::orderBy('id', 'DESC')->first()->id;

            $noteLabelData['note_id'] = $lastInsertedId;
            $noteLabelData['label_id'] = $data['label_id'];
            NoteLabel::create($noteLabelData);
            echo json_encode(['lastId' => $lastInsertedId]);
        });
        // return response('failed', 403);
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
