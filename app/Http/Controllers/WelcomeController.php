<?php namespace App\Http\Controllers;

use App\Cemetery;
use Crypt;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		 $cemetery = Cemetery::first();

		// Encrypt the ID
		$encryptedId = FacadesCrypt::encrypt($cemetery->id);
		 return view('welcome', compact('encryptedId'));
	}



}
