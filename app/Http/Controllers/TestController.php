<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use App\Services\Base\Attachment;
use App\Models\BaseAttachmentModel;
use File, Image;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Food;
use App\Models\Order;
use App\Models\BackMoney;
use App\Models\Activity;
use App\Models\ContactUs;
use App\Models\Area;
use DB, Auth;
use App\Helper\SmsHelper;
use App\Models\SendDate;
use App\Models\Score;
use QrCode;

class TestController extends Controller
{
    public function index(Request $request)
    {	
    	$user = User::where('wechat_name', 'silent')->get();
        dd($user);
        dd($day->toDateString(), $day->subWeek()->toDateString());
    	return view('test.index');
    }

    public function submit(Request $request)
    {
    	dd($request->all());
    }
}
