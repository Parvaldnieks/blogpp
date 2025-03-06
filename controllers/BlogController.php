<?php
require "models/Blog.php";

class BlogController {
    public function index() {
        $posts = Blog::all();

        require "views/blog/index.view.php";
    }

    // Show a single post
    public function show($id) {
        $post = Blog::find($id);
        require "views/blog/show.view.php";
    }

    // Show the form for creating a new post
    public function create() {
        require "views/blog/create.view.php";
    }

    // Handle form submission for creating a new post
    public function store() {
        $content = $_POST["content"];

        Blog::create([
            "content" => $content
        ]);

        header("Location: /");
        exit();
    }

    // Show the form for editing a post
    public function edit($id) {
        $post = Blog::find($id);
        require "views/blog/edit.view.php";
    }

    // Handle form submission for updating a post
    public function update($id) {
        $content = $_POST["content"];

        Blog::save($id, [
            "content" => $content
        ]);

        header("Location: /post/$id");
        exit();
    }

    // Delete a post
    public function destroy($id) {
        Blog::delete($id);
        header("Location: /");
        exit();
    }
}