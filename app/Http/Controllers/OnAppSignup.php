<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class OnAppSignup extends Controller
{
    public function store(Request $request)
    {
	//Create Organization
	$create_org = new Client();
	$org = $create_org->request('POST', ''.config('onapp_signup.onapp_protocol').'://'.config('onapp_signup.onapp_url').'/organizations.json', [
		'auth' => [config('onapp_signup.onapp_username'), config('onapp_signup.onapp_password')],
		'verify' => false,
		'body' => '{"vcloud_organization":{
		"label": "'.$request->input('company').'",
		"hypervisor_id": "'.config('onapp_signup.onapp_compute_resource_id').'",
		"create_user_group": true,
		"company_billing_plan_id": "'.config('onapp_signup.onapp_bucket_id').'"
		}}',
		'headers' => ['Content-Type' => 'application/json'],
		'http_errors' => false
	]);
	if ($org->getStatusCode() == 422) {
		\Session::flash('alert-danger', 'Company Name has already been taken. Please try again.');
		return redirect()->back();
	}
	else {
	$created_user_group = json_decode($org->getBody())->vcloud_organization->user_group_id;
	
	//Edit Created User Group
	$edit_user_group = new Client();
        $user_group = $edit_user_group->request('PUT', ''.config('onapp_signup.onapp_protocol').'://'.config('onapp_signup.onapp_url').'/user_groups/'.$created_user_group.'.json', [
                'auth' => [config('onapp_signup.onapp_username'), config('onapp_signup.onapp_password')],
                'verify' => false,
                'body' => '{"user_group":{
		"company_billing_plan_id": "'.config('onapp_signup.onapp_bucket_id').'",
                "billing_plan_ids": ["'.config('onapp_signup.onapp_bucket_id').'"]
                }}',
                'headers' => ['Content-Type' => 'application/json'],
                'http_errors' => true
        ]);
sleep(2);
	//Create User
	$create_user = new Client();
        $user = $create_user->request('POST', ''.config('onapp_signup.onapp_protocol').'://'.config('onapp_signup.onapp_url').'/users.json', [
                'auth' => [config('onapp_signup.onapp_username'), config('onapp_signup.onapp_password')],
                'verify' => false,
                'body' => '{"user":{
                "first_name":"'.$request->input('first_name').'",
                "last_name":"'.$request->input('last_name').'",
                "email":"'.$request->input('email').'",
                "login":"'.$request->input('email').'",
                "password":"'.$request->input('password').'",
                "bucket_id":"'.config('onapp_signup.onapp_bucket_id').'",
                "user_group_id":"'.$created_user_group.'",
		"time_zone":"'.$request->input('time_zone').'",
		"role_ids":["'.config('onapp_signup.onapp_role_id').'"]
                }}',
                'headers' => ['Content-Type' => 'application/json'],
                'http_errors' => false
	]);
	$full_user = $user->getBody()->getContents();
	if ($user->getStatusCode() == 422) {
	$failed_user = $user->getBody()->getContents();
	\Session::flash('alert-danger', 'An error occured while creating the user. <br /> '.$failed_user.''); 
	return redirect()->back();
	}elseif ($user->getStatusCode() == 201) {
	$created_user = json_decode($user->getBody())->user->login;
	\Session::flash('alert-success', 'User has been created successfully.<br /><br /><strong><a href="'.config('onapp_signup.onapp_protocol').'://'.config('onapp_signup.onapp_url').'/users/sign_in?user[login]='.$created_user.'" target="_blank">Login here</a></strong><br /><br /><strong>Password: </strong>'.$request->input('password').'');
	return redirect()->back();
	}else {
		\Session::flash('alert-danger', 'An OnApp Server error has occured. Error: '.$user->getStatusCode().'<br />Please check the OnApp logs.');
		return redirect()->back();
	}}

    }
}
