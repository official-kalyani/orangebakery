<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Helpers\Helper;


class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $data = Blog::orderBy('id','DESC')->paginate(10);
        return view('admin.blogs.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = BlogCategory::all();
        return view('admin.blogs.create',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0; 

        if(isset($_POST['id']) && $_POST['id']!='') 
        {
            $id = $_REQUEST['id'];
            $blog = Blog::find($id);
            if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                @unlink('uploads/blogs/'.$blog->image);
                $image = $request->image;
                $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/blogs/';
                if (!is_dir($user_home_dir)) {
                    mkdir($user_home_dir, 0777);
                }
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $image = base64_decode($image);
                $image_name= time().'.png';
                $path = public_path('uploads/blogs/'.$image_name);
                file_put_contents($path, $image);
                $blog->image = $image_name;
            }
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->meta_title = $request->meta_title;
            $blog->meta_desc = $request->meta_desc;
            $blog->category_id = $request->category_id;
            $blog->save();
            $arr['succ'] = 1;
        }
        else
        {
            $blog = new Blog;
            $slug = Helper::getBlogUrl($request->title);
            if (Blog::where('slug', '=', $slug)->count() > 0)
            {
                $arr['succ'] = 2;
            }
            else{
                if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                    @unlink('uploads/blogs/'.$blog->image);
                    $image = $request->image;
                    $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/blogs/';
                    if (!is_dir($user_home_dir)) {
                        mkdir($user_home_dir, 0777);
                    }
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    $image_name= time().'.png';
                    $path = public_path('uploads/blogs/'.$image_name);
                    file_put_contents($path, $image);
                    $blog->image = $image_name;
                    $blog->title = $request->title;
                    $blog->description = $request->description;
                    $blog->meta_title = $request->meta_title;
                    $blog->meta_desc = $request->meta_desc;
                    $blog->category_id = $request->category_id;
                    $blog->slug = $slug; 
                    $blog->save();
                    $arr['succ'] = 1;
                }
            } 
        }
        return response()->json($arr);
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
        $category = BlogCategory::all();
        $data = Blog::findOrFail($id);
        return view('admin.blogs.edit',compact('data','category'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        @unlink('uploads/blogs/'.$blog->image);
        $blog->delete();
        return back()->with('flash_success', 'Blog Deleted Successfully!');
    }
}
