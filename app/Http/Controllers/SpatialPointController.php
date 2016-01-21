<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Spatialpoint;
use Auth;
use App\City;

class SpatialPointController extends Controller {


	public function __construct()
	{
		$this->middleware('auth', ['only' => [
            'store',
            'destroy'
        ]]);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$minX = $request->input('minX');
		$minY = $request->input('minY');
		$maxX = $request->input('maxX');
		$maxY = $request->input('maxY');

		if (Auth::user() !== null) {
			$user = Auth::user()->id;
		} else {
			$user = 0;
		}

		print(json_encode(Spatialpoint::getByBoundary(compact('minX', 'minY', 'maxX', 'maxY', 'user'))));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if ($request->file('image')) {
			if ($request->file('image')->isValid()) {
				$fileName = (integer)(round(microtime(true) * 1000));
				$fileName .= '.'.$request->file('image')->guessExtension();
				$path = public_path().'/img/uploads/';
			    $request->file('image')->move($path, $fileName);
			    $image = $fileName;
			}
		} else {
			$image = null;
		}

		$latitude  = $request->input('latitude');
		$longitude = $request->input('longitude');

		$city    = $request->input('city');
		$state   = $request->input('state');
		$country = $request->input('country');

		$city = City::findOrCreate(compact('city', 'state', 'country'))->id;

		$point = new Spatialpoint();
		$point->point    = Spatialpoint::geom($latitude, $longitude);
		$point->species  = $request->input('species');
		$point->comments = $request->input('comments');
		$point->type     = $request->input('type');
		$point->address  = $request->input('address');
		$point->image    = $image;
		$point->user_id  = Auth::user()->id;
		$point->city_id  = $city;

		$point->save();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = Auth::user()->id;

		$spatialPoint = Spatialpoint::findOrFail($id)->toArray();

		if (isset($user)) {
			if($user == $spatialPoint['user_id']) {
				$spatialPoint['isOwner'] = true;
			} else {
				$spatialPoint['isOwner'] = false;
			}
		}

		switch ($spatialPoint['type']) {
			case 'VEGETACAO':
				$spatialPoint['type'] = 'Vegetação';
				break;
			case 'LOCAL_PLANTIO':
				$spatialPoint['type'] = 'Local para plantio';
				break;
			case 'PODA_DRASTICA':
				$spatialPoint['type'] = 'Poda Drástica';
				break;
		}

		print(json_encode($spatialPoint));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = Auth::user()->id;

		if (Spatialpoint::findOrFail($id)->toArray()['user_id'] == $user) {
			var_dump(Spatialpoint::find($id)->delete());
		} else {
			http_response_code(403);
		}
	}

}
