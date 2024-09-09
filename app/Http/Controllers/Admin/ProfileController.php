<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SimeviTrait;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\DataUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


class ProfileController extends Controller
{

	// use SimeviTrait;

	public $access_token = '';
	public $data_user;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Profile';
		$page_title = 'Myprofile';
		$page_heading = 'Myprofile';
		$heading_class = 'fa fa-user';
		$this->data_user = Auth::user()::find(auth()->id())->data_user;
		$data_user = $this->data_user;
		// dd($data_user);
		return view('admin.profiles.index', compact(
			'module_name',
			'page_title',
			'page_heading',
			'heading_class',
			'data_user'
		));
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateProfileRequest $request, $id)
	{
		//$user = User::find($id);
		$data = $request->all();
		$regdata = [];
		$request->validate([
			'avatar' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/png|max:2048',
			'logo' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/png|max:2048',
			'imagektp' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/png|max:2048',
			'assignment' => 'mimes:pdf|mimetypes:application/pdf|max:2048',
		]);

		$avatar_path = '';
		$realnpwp = $data['npwp_company'];
		$npwp = str_replace('.', '', $realnpwp);
		$npwp = str_replace('-', '', $npwp);
		if (array_key_exists('avatar', $data)) {
			if ($data['avatar'] != null) {
				$file_name = $npwp . '_' . 'avatar.' . $data['avatar']->extension();
				$file_path = $data['avatar']->storeAs('uploads/' . $npwp, $file_name, 'public');
				$avatar_path = $file_path;
				$regdata += array('avatar' => $avatar_path);
			};
		}
		$logo_path = '';
		if (array_key_exists('logo', $data)) {
			if ($data['logo'] != null) {
				$file_name = $npwp . '_' . 'logo.' . $data['logo']->extension();
				$file_path = $data['logo']->storeAs('uploads/' . $npwp, $file_name, 'public');
				$logo_path = $file_path;
				$regdata += array('logo' => $logo_path);
			};
		}
		$ktp_path = '';
		if (array_key_exists('imagektp', $data)) {
			if ($data['imagektp'] != null) {
				$file_name = $npwp . '_' . 'ktp.' . $data['imagektp']->extension();
				$file_path = $data['imagektp']->storeAs('uploads/' . $npwp, $file_name, 'public');
				$ktp_path = $file_path;
				$regdata += array('ktp_image' => $ktp_path);
			};
		}
		$assign_path = '';
		if (array_key_exists('assignment', $data)) {
			if ($data['assignment'] != null) {
				$file_name = $npwp . '_' . 'assignment.' . $data['assignment']->extension();
				$file_path = $data['assignment']->storeAs('uploads/' . $npwp, $file_name, 'public');
				$assign_path = $file_path;
				$regdata += array('assignment' => $assign_path);
			};
		}
		DataUser::updateOrCreate([
			'user_id' =>  $id,
		], $regdata);
		return redirect()->route('admin.profile.show')->with('message', 'Profile updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
