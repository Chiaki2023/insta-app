<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    // create() - view the Create Post Page
    public function create()
    {
        $all_categories = $this->category->all(); 
        return view('users.posts.create')
            ->with('all_categories', $all_categories);
    }

    // store() - save the post to DB
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        # Save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save();

        # Save the categories to the category_post pivot table
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
            /*
                $category_post = [
                    [category_id => 1],
                    [category_id => 2],
                    [category_id => 3]
                ];
            */
        }

        $this->post->categoryPost()->createMany($category_post);
            /*
                category_post table:
                    ['post_id' => 1, 'category_id' => 1],
                    ['post_id' => 1, 'category_id' => 2],
                    ['post_id' => 1, 'category_id' => 3],
            */

        # Go back to homepage
        return redirect()->route('index');
    }

    // show() - view Show Post Page
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
            ->with('post', $post);
    }

    // edit() - view the Edit Post Page and display details of a post
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        # If the Auth user is NOT the owner of the post, redirect o homepage
        if(Auth::user()->id != $post->user->id)
        {
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        # Get all the vategory IDs of this post. Save in an array
        $selected_categories = [];
        foreach($post->categoryPost as $category_post)
        {
            $selected_categories[] = $category_post->category_id;
            // $selected_categories = [
            // [3],
            // [4],
            //];
        }

        return view('users.posts.edit')
            ->with('post', $post)  //holds the record of a post
            ->with('all_categories', $all_categories)  // holds all the categories from the table
            ->with('selected_categories', $selected_categories);  //holds all category ids of a post
    }

    // update() - update the post
    public function update(Request $request, $id)
    {
        # 1. Validate the data from the view
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        # 2. Update the post
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;

        // If thereis a new image...
        if($request->image)
        {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        # 3. Delete all records from categoryPost related to this post
        $post->categoryPost()->delete();

        # 4. Save the new categories to category_post pivot table
        foreach($request->category as $category_id)
        {
            $category_post[] = [
                'category_id' => $category_id
            ];

            /*
                $category_post = [
                    [category_id => 3],
                    [category_id => 4] 
                ];
             */
        }

        $post->categoryPost()->createMany($category_post);

        return redirect()->route('post.show', $id);
    }

    # destroy() - delete or remove the post 
    public function destroy($id)
    {
        // $this->post->destroy($id);

        // return redirect()->route('index');

        $post = $this->post->findOrFail($id);
        $post->forceDelete();

        return redirect()->route('index');
    }
}
