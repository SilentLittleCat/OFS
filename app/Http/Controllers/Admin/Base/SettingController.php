<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Admin\Controller;
use App\Models\BaseSettingsModel;

class SettingController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.base.setting.index', [
            'items' => BaseSettingsModel::where('category', '<>', 'category')->orderBy('sort', 'asc')->orderBy('key', 'asc')->get(),
            'categories' => BaseSettingsModel::where('category', '=', 'category')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.setting.create', [
            'categories' => Setting::where('category', '=', 'category')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        if ($data = $request->all()) {
            if (Setting::where(['category' => $data['category'], 'code' => $data['code']])->exists()) {
                return back()->with('errors', ['该类别下已有相同键存在!']);
            }
            $data['sort'] = empty($request->sort) ? 0 : $request->sort;
            $item = Setting::create($data);
            if ($item) {
                Toastr::success('商户添加成功!');
                return redirect(route('admin.setting.index'));
            } else {
                Toastr::success('商户添加失败!');
                return back()->with('errors', ['商户存储失败!']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Breadcrumbs::register('admin-setting-edit', function ($breadcrumbs) use ($id) {
            $breadcrumbs->parent('admin-setting');
            $breadcrumbs->push('编辑配置', route('admin.setting.edit', ['id' => $id]));
        });

        $item = Setting::find($id);
        if(!$item) {
            Toastr::error('配置不存在');
            return redirect(route('admin.setting.index'));
        }

        return view('admin.setting.edit', [
            'item'          => $item,
            'categories'    => Setting::where('category', '=', 'category')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequest $request, $id)
    {
        $item = Setting::find($id);
        if(!$item) {
            return $this->showMessage('配置不存在');
            Toastr::error('配置不存在');
            return redirect(route('admin.setting.index'));
        }

        if ($data = $request->all()) {
            $oldItem = Setting::where(['category' => $data['category'], 'code' => $data['code']])->first();
            if ($oldItem !== null && $oldItem->id != $id) {
                return back()->with('errors', ['该类别下已有相同键存在!']);
            }
            $result = $item->update($data);
            if ($result) {
                Toastr::success('配置编辑成功!');
                return redirect()->route('admin.setting.index');
            } else {
                Toastr::error('配置编辑失败!');
                return back()->with('errors', ['配置编辑失败!']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $app = Setting::find($id);

        if($app->delete()){
            return $this->showMessage('配置删除成功');
        }else{
            return $this->showWarning('配置删除成功');
        }
        return redirect(route('admin.setting.index'));
    }
}
