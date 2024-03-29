<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $post;
    private $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    # get the posts of the users that the auth user is following
    public function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; // In case the array $home_posts is empty, it will not return NULL, but empty instead

        foreach($all_posts as $post){
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
               $home_posts[] = $post; 
              /* 
              $home_posts = [
                ['id' => 4, 'description' => 'test', 'image' => 'data:image/..', 'user_id' => 3]
                ['id' => 2, 'description' => 'test', 'image' => 'data:image/..', 'user_id' => 1]
              ]*/
            }
        }

        return $home_posts;
    }

    public function getSuggestedUsers()
    {
        $all_users = $this->user->get();
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
            // If the users are not followed, show them.
        }

        return array_slice($suggested_users, 1, 5);
    }

    public function index()
    {
        // $all_posts = $this->post->latest()->get();

        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
    }

    // To show all suggested users 
    public function getAllSuggestedUsers()
    {
        $all_users = $this->user->get();
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
            // If the users are not followed, show them.
        }

        return $suggested_users;
    }

    public function suggestions()
    {
        $suggested_users = $this->getAllSuggestedUsers();

        return view('users.suggestion')
            ->with('suggested_users', $suggested_users);
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();

        return view('users.search')
            ->with('users', $users)
            ->with('search', $request->search);
    }
}
