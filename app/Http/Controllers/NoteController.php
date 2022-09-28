<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteLabel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Get data for DataTable
     *
     * @param  mixed $request
     * @return json data
     */
    public function getNotes(Request $request)
    {
        if ($request->ajax()) {
            $note = Note::query()->where('user_id',  auth()->user()->id)->doesntHave('noteLabel');

            return DataTables::eloquent($note)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    $keyword = $request->get('search')['value'];
                    $query->select('id', 'title', 'body');
                    if (!empty($request->get('search')) && $keyword != '') {
                        $query->where(function ($q) use ($keyword) {
                            $q->where('title', 'like', '%' . $keyword . '%');
                            $q->orWhere('body', 'like', '%' . $keyword . '%');
                        });
                    }
                })
                ->addColumn('content', function ($row) {
                    $title = Str::words($row->title, 20);
                    $body  = Str::words($row->body, 20);
                    $body  = Str::replace("\n", "<br>", $body);
                    $body  = Str::replace(" ", "&nbsp", $body);

                    $content = '<div class="d-none d-md-block">';
                    $content .= '<span style="font-size:1.1em;font-weight:600;">' . $title . '</span><hr>' . $body;
                    $content .= '</div>';

                    $title_mobile = Str::words($row->title, 10);
                    $body_mobile  = Str::words($row->body, 10);

                    $content .= '<div class="d-md-none">';
                    $content .= '<span style="font-size:1.1em;font-weight:600;">' . $title_mobile . '</span><hr>' . $body_mobile;
                    $content .= '</div>';
                    return $content;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="d-none d-md-flex align-items-center">';
                    $actionBtn .= '<button class="btn btn-sm btn-success ml-1 px-3 btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')"
                                    title="Edit"><i class="ion-edit"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger ml-1 px-3 btn-delete" data-id="' . $row->id . '" data-title="' . $row->title . '"
                                    title="Delete"><i class="ion-trash-b"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 d-flex align-items-center btn-add-label"
                                    data-id="' . $row->id . '" data-toggle="modal" data-target="#modal-add-label"
                                    title="Add to Label"><i class="ion-android-add-circle mr-2"></i><i class="ion-pricetag"></i></button>';
                    $actionBtn .= '</div>';

                    $actionBtn .= '<div class="d-md-none">';
                    $actionBtn .= '<button class="btn btn-sm btn-success ml-1 mb-2 px-3 btn-edit" data-toggle="modal" data-target="#modal" onclick="editData(' . $row->id . ')"
                                    title="Edit"><i class="ion-edit"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-danger ml-1 mb-2 px-3 btn-delete" data-id="' . $row->id . '" data-title="' . $row->title . '"
                                    title="Delete"><i class="ion-trash-b"></i></button>';
                    $actionBtn .= '<button class="btn btn-sm btn-primary ml-1 mb-2 d-flex align-items-center btn-add-label"
                                    data-id="' . $row->id . '" data-toggle="modal" data-target="#modal-add-label"
                                    title="Add to Label"><i class="ion-android-add-circle mr-2"></i><i class="ion-pricetag"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['content', 'action'])
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
        $validatedData = $request->validate(['body' => 'required']);
        $validatedData['title'] = $request->title;
        $validatedData['user_id'] =  auth()->user()->id;
        $note = Note::create($validatedData);
        $lastInsertedId = $note::orderBy('id', 'DESC')->first()->id;

        echo json_encode(['statusCode' => 200, 'lastId' => $lastInsertedId]);
    }

    public function setLabel(Request $request)
    {
        $validatedData = $request->validate([
            'note_id' => 'required|numeric',
            'label_id' => 'required|numeric'
        ]);

        $lastPosition = NoteLabel::with('note')->get()
            ->where('note.user_id', '=',  auth()->user()->id)
            ->where('label_id', $request->label_id)
            ->max('position');
        $validatedData['position'] = $lastPosition + 1;

        NoteLabel::create($validatedData);
        return 1;
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
        if ($note->user_id !== auth()->user()->id) {
            abort(403);
        }

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
        $note::where('id', $note->id)->update($validatedData);

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
        DB::transaction(function () use ($note) {
            Note::destroy($note->id);
            NoteLabel::where('note_id', $note->id)->delete();
            echo json_encode(['statusCode' => 200]);
        });
    }
}
