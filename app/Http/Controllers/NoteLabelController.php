<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Label;
use App\Models\NoteLabel;
use Illuminate\Support\Str;
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
        //
    }

    public function viewNotesByLabel(Request $request)
    {
        $label = Label::whereId($request->id)->first();

        if ($label->user_id !== auth()->user()->id) {
            abort(403);
        }

        return view('pages.note_labels', [
            'title' => 'Notes in Label',
            'labelId' => $request->id,
            'labelName' => $label->name
        ]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $noteLabels = NoteLabel::with('note', 'label')->orderBy('position')->get()
                ->where('note.user_id', '=',  auth()->user()->id)
                ->where('label_id', '=', $request->id);

            return DataTables::of($noteLabels)
                ->addColumn('content', function ($row) {
                    $title = Str::words($row->note->title, 20);
                    $body  = Str::words($row->note->body, 20);
                    $body  = Str::replace("\n", "<br>", $body);
                    $body  = Str::replace(" ", "&nbsp", $body);

                    $content = '<div class="d-none d-md-block">';
                    $content .= '<span style="font-size:1.1em;font-weight:600;">' . $title . '</span><hr>' . $body;
                    $content .= '</div>';

                    $title_mobile = Str::words($row->note->title, 10);
                    $body_mobile  = Str::words($row->note->body, 10);

                    $content .= '<div class="d-md-none">';
                    $content .= '<span style="font-size:1.1em;font-weight:600;">' . $title_mobile . '</span><hr>' . $body_mobile;
                    $content .= '</div>';
                    return $content;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="d-none d-md-flex align-items-center">';
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

                    $actionBtn .= '<div class="d-md-none">';
                    $actionBtn .= '<button class="btn btn-sm btn-success ml-1 mb-2 px-3 btn-edit" data-toggle="modal" data-target="#modal"
                                    onclick="editData(' . $row->note->id . ')" title="Edit"><i class="ion-edit"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger ml-1 mb-2 px-3 btn-delete" data-id="' . $row->note->id . '" data-title="' . $row->note->title . '"
                                    title="Delete"><i class="ion-trash-b"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 mb-2 d-flex align-items-center" data-toggle="modal" data-target="#modal"
                                    onclick="insertData(\'above\',' . $row->position . ')" title="Add above">
                                    <i class="ion-android-add-circle mr-2"></i><i class="ion-android-arrow-up"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 mb-2 d-flex align-items-center" data-toggle="modal" data-target="#modal"
                                    onclick="insertData(\'below\',' . $row->position . ')" title="Add below">
                                    <i class="ion-android-add-circle mr-2"></i><i class="ion-android-arrow-down"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['content', 'action'])
                ->only(['id', 'content', 'action'])
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
            return response($errors, 403);
        }

        DB::transaction(function () use ($data) {
            $noteData['user_id'] =  auth()->user()->id;
            $noteData['title'] = $data['title'];
            $noteData['body'] = $data['body'];
            $note = Note::create($noteData);
            $lastInsertedNoteId = $note::orderBy('id', 'DESC')->first()->id;

            $newNoteLabelData['note_id'] = $lastInsertedNoteId;
            $newNoteLabelData['label_id'] = $data['label_id'];

            $toBeOccupiedPosition = 0;
            $lastPosition = NoteLabel::with('note')
                ->where('position', '>=', $toBeOccupiedPosition)
                ->orderBy('position', 'asc')
                ->get()
                ->where('note.user_id', '=',  auth()->user()->id)
                ->where('label_id', $data['label_id'])
                ->max('position');
            $pos = $data['position'] ?: $lastPosition;

            if ($data['insertTo'] == 'above') {
                $toBeOccupiedPosition = $pos;
            } elseif ($data['insertTo'] == 'below') {
                $toBeOccupiedPosition = $pos + 1;
            } else {
                abort(403, 'Note InsertTo should be specified');
            }

            // shift below notes position
            $otherNotes = NoteLabel::with('note')
                ->where('position', '>=', $toBeOccupiedPosition)
                ->orderBy('position', 'asc')
                ->get()
                ->where('note.user_id', '=',  auth()->user()->id)
                ->where('label_id', $data['label_id']);

            $curentPosition = $toBeOccupiedPosition;
            $newPosition = $toBeOccupiedPosition + 1;
            foreach ($otherNotes as $note) {
                NoteLabel::where('note_id', $note->note_id)
                    ->where('label_id', $data['label_id'])
                    ->where('position', $curentPosition)
                    ->update(['position' => $newPosition]);
                $curentPosition += 1;
                $newPosition += 1;
            }

            $newNoteLabelData['position'] = $toBeOccupiedPosition;
            NoteLabel::create($newNoteLabelData);
            echo json_encode(['lastId' => $lastInsertedNoteId]);
        });
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
