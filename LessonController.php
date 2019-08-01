public function index(Request $request)
    {
        $trades = Trade::all();
        $lessons = Lesson::orderBy('created_at', 'desc')->paginate(10);

        if ($request->input('trade_id')) {
          
            $lessons = Lesson::where('trade_id', $request->input('trade_id'))->orderBy('lesson_title', 'DESC')->paginate(10);

        }
        if ($request->input('lesson_id')) {
          
            $lessons = Lesson::where('id', $request->input('lesson_id'))->orderBy('lesson_title', 'DESC')->paginate(10);

        }

        return view('admin.lessons.index', compact('lessons', 'trades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $trades = Trade::all();

        return view('admin.lessons.create', compact('trades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'trade_id' => 'required|integer',
            'section_id' => 'required|integer',
            'lesson_title' => 'required',
            'local_video_link' => 'sometimes',
            'remote_video_link' => 'sometimes',
        ]);

        // create lesson
        $lesson = Lesson::create([
            'trade_id' => $request->input('trade_id'),
            'section_id' => $request->input('section_id'),
            'lesson_title' => $request->input('lesson_title'),
            'slug' => Str::kebab(strtolower( $request->input('lesson_title'))),
            'lesson_text' => $request->input('lesson_text'),
            'lesson_duration' => $request->input('lesson_duration'),
            'is_published' => $request->input('is_published'),
            'position' => Lesson::where('trade_id',$request->trade_id)->max('position')+1,
            'remote_video_link' => $request->input('video_location') == "remote" ? $request->input('remote_video_link') : '',
            'created_by' => auth()->user()->getFullName(),
        ]);

        //Handle fileuploads
        if ($request->hasFile('lesson_image')) {
            $lesson_image = $request->file('lesson_image');

            $filename = time().'.'.$lesson_image->getClientOriginalExtension();

            $location = public_path('img/lesson_images/'.$filename);

            Image::make($lesson_image)->resize(600, 600)->save($location);

            $lesson->update([
                'lesson_image' => 'img/lesson_images/'.$filename,
            ]);
        } else {
            $filename = 'default.jpeg';
            $lesson->update([
                'lesson_image' => 'img/lesson_images/'.$filename,
            ]);
        }
        //Lesson Video Upload
        if($request->input('video_location') == "local"){
            if ($request->hasFile('local_video_link')) {
                $lesson_video = $request->file('local_video_link');
                $file = $lesson_video->getClientOriginalName();
                $filename = $file.'-'.time().'.'.$lesson_video->getClientOriginalExtension();
                $location = public_path('videos/');
                $lesson_video->move($location, $filename);
                $lesson->update([
                    'local_video_link' => 'videos/'.$filename,
                ]);
            }
        }
        // // downloadables files
        // for ($i = 0; $i < count($request->file('downloadable_files')); ++$i) {
        //     $downloadable_files = $request->file('downloadable_files')[$i];
        //     $files = $downloadable_files->getClientOriginalName();
        //     $filename = $files.'.'.time().'.'.$downloadable_files->getClientOriginalExtension();
        //     $location = public_path('videos/'.$filename);
        //     $downloadable_files->move($location, $filename);
        //     $lessons->downloadable_files = $filename;
        // }

        //success flash message
        flash('Lesson successfully created!!!')->success();

        //redirect to back
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        $trades = Trade::all();
        return view('admin.lessons.edit', compact('lesson', 'trades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        $trades = Trade::all();
        return view('admin.lessons.edit', compact('lesson', 'trades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $this->validate($request, [
            'trade_id' => 'required|integer',
            'section_id' => 'required|integer',
            'lesson_title' => 'required',
            'local_video_link' => 'sometimes',
            'remote_video_link' => 'sometimes',
        ]);

            // dd($request->all());
        // create lesson
        $lesson->update([
            'trade_id' => $request->input('trade_id'),
            'section_id' => $request->input('section_id'),
            'lesson_title' => $request->input('lesson_title'),
            'slug' => Str::kebab(strtolower( $request->input('lesson_title'))),
            'lesson_text' => $request->input('lesson_text'),
            'lesson_duration' => $request->input('lesson_duration'),
            'is_published' => $request->input('is_published'),
            'remote_video_link' => $request->input('video_location') == "remote" ? $request->input('remote_video_link') : '',
            'created_by' => auth()->user()->getFullName(),
        ]);

        //Handle fileuploads
        if ($request->hasFile('lesson_image')) {
            $lesson_image = $request->file('lesson_image');

            $filename = time().'.'.$lesson_image->getClientOriginalExtension();

            $location = public_path('img/lesson_images/'.$filename);

            Image::make($lesson_image)->resize(600, 600)->save($location);

            $lesson->update([
                'lesson_image' => 'img/lesson_images/'.$filename,
            ]);
        }
       
        //Lesson Video Upload
        if($request->input('video_location') == "local"){
            if ($request->hasFile('local_video_link')) {
                $lesson_video = $request->file('local_video_link');
                $file = $lesson_video->getClientOriginalName();
                $filename = $file.'-'.time().'.'.$lesson_video->getClientOriginalExtension();
                $location = public_path('videos/');
                $lesson_video->move($location, $filename);
                $lesson->update([
                    'local_video_link' => 'videos/'.$filename,
                ]);
            }
        }

         //success flash message
         flash('Lesson successfully updated!!!')->success();

         //redirect to back
         return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lessons = Lesson::find($id);
        $lessons->delete();

        return response()->json(['status'=>'success','message'=>'successfully deleted!']);
    }
