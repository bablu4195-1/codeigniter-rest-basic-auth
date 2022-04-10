<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoryModel;
use App\Models\BlogModel;

class ApiController extends ResourceController
{
    private $db;
    public function __construct(){
        $this->db = db_connect();
    }
    //post
   public function createCategory()
   {
     $rules = [
            'name' => 'required|is_unique[categories.name]|min_length[3]|max_length[20]',
     ];
     if(!$this->validate($rules)){
         $response = [
                'status' => 500,
                'message' => $this->validator->getErrors(),
                'error' => true,
                'data' => []
         ];
     } else {
         $category_obj = new CategoryModel();
         $data = [
           "name" => $this->request->getVar('name'),
            "status" => $this->request->getVar('status')
         ];
         if($category_obj->insert($data)){
         
             $response = [
                'status' => 200,
                'message' => 'Category created successfully',
                'error' => false,
                'data' => []
             ];
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Category creation failed',
                    'error' => true,
                    'data' => []
                ];
            }
     }
        return $this->respondCreated($response);
   }
   //get
   public function listCategory()
   {
     $category_obj = new CategoryModel();
     $response = [
            'status' => 200,
            'message' => 'Category list',
            'error' => false,   
            'data' => $category_obj->findAll()
     ];
     return $this->respondCreated($response);
   }

   //post
    public function createBlog()
    {
      $rules = [
        "category_id" => "required",
        "title" => "required",
      ];
      if(!$this->validate($rules)){
            $response = [
                    'status' => 500,
                    'message' => $this->validator->getErrors(),
                    'error' => true,
                    'data' => []
            ];
    } else {
        $category_obj = new CategoryModel();
        $is_exists = $category_obj->find($this->request->getVar("category_id"));
        if(!empty($is_exists)){
            //category exists
            $blog_obj = new BlogModel();
            $data = [
                "category_id" => $this->request->getVar("category_id"),
                "title" => $this->request->getVar("title"),
                "content" => $this->request->getVar("content")
            ];
           if($blog_obj->insert($data)){
              //blog created
              $response = [
                    'status' => 200,
                    'message' => 'Blog created successfully',
                    'error' => false,
                    'data' => []
                ];
           } else {
               //blog creation failed
                $response = [
                      'status' => 500,
                      'message' => 'Blog creation failed',
                      'error' => true,
                        'data' => []
                ];  
           }
            
        } else {
            //category does not exists
            $response = [
                    'status' => 404,
                    'message' => 'Category does not exists',
                    'error' => true,
                    'data' => []
            ];
        }
     }
     return $this->respondCreated($response);
    }
    //get
    public function listBlog()
    {
     $builder = $this->db->table('blogs as b');
     $builder->select("b.*,c.name as category_name");
     $builder->join('categories as c', 'c.id = b.category_id');
     $data = $builder->get()->getResult();
     $response = [
            'status' => 200,
            'message' => 'Blog list',
            'error' => false,
            'data' => $data
     ];
     return $this->respondCreated($response);
    }
    public function singleDetailBlog($blog_id)
    {
      $builder = $this->db->table("blogs as b");
      $builder ->select("b.*,c.name as category_name");
      $builder ->join('categories as c', 'c.id = b.category_id');  
      $builder->where("b.id",$blog_id);
      $data = $builder->get()->getRow();

      $response = [
            'status' => 200,
            'message' => 'Blog list',
            'error' => false,
            'data' => $data
      ];
      return $this->respondCreated($response);
    }
    //post->put
    public function updateBlog($blog_id){
      
        $blog_obj = new BlogModel();
        $blog_exists = $blog_obj->find($blog_id);
        if(!empty($blog_exists)){
            //blog exists
            $rules = [
                "category_id" => "required",
                "title" => "required",
            ];
            if(!$this->validate($rules)){
                $response = [
                    'status' => 500,
                    'message' => $this->validator->getErrors(),
                    'error' => true,
                    'data' => []
                ];
            } else {
                $category_obj = new CategoryModel();
                $category_exists = $category_obj->find($this->request->getVar("category_id"));
                if(!empty($category_exists)){
                 //category exists
                    // $blog_obj = new BlogModel();
                    $data = [
                        "category_id" => $this->request->getVar("category_id"),
                        "title" => $this->request->getVar("title"),
                        "content" => $this->request->getVar("content")
                    ];
                    $blog_obj->update($blog_id,$data);
                    $response = [
                        'status' => 200,
                        'message' => 'Blog updated successfully',
                        'error' => false,
                        'data' => []
                    ];

                } else {
                  //category does not exists
                  $response= [
                    'status' => 404,
                    'message' => 'Category does not exists',
                    'error' => true,
                    'data' => []
                  ];
                }
            }
        } else {
            //blog does not exists
        $response = [
         'status' => 404,
            'message' => 'Blog does not exists',
            'error' => true,
            'data' => []
        ];
        }
        return $this->respondCreated($response);
    }
    //delete
    public function deleteBlog($blog_id){
      $blog_obj = new BlogModel();
      $blog_exists = $blog_obj->find($blog_id);
      if(!empty($blog_exists)){
        $blog_obj->delete($blog_id);
        $response = [
            'status' => 200,
            'message' => 'Blog deleted successfully',
            'error' => false,
            'data' => []
        ];
      } else  {
        $response = [
          'status' => 404,
          'message' => 'Blog does not exists',
          'error' => true,
        ];
      }
      return $this->respondCreated($response);
    }
}
