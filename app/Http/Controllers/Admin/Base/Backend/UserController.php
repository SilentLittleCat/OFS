<?php

namespace App\Http\Controllers\Admin\Base\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Services\Base\Attachment;
use App\Models\User;

class UserController extends Controller
{
    private $_serviceAttachment;

    public function __construct()
    {
        if( !$this->_serviceAttachment ) $this->_serviceAttachment = new Attachment();
    }
    
    public function index()
    {
    	$users = User::all();
    	return view('admin.backend.users.index', compact('users'));
    }
}
