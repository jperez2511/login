<?php namespace Csgt\Login\Http\Controllers;

use Illuminate\Routing\Controller, View, Auth, Redirect;
use Config, Validator, Input,  Otp\Otp, Otp\GoogleAuthenticator;
use Base32\Base32, Hash, URL, Session, DB, Carbon\Carbon;

class perfilController extends Controller {

	public function index() {		
		return view('csgtlogin::perfil')
			->with('templateincludes',['formvalidation']);
	}

	public function save() {
		$campopassword = config('csgtlogin.password.campo');
		$campousuario  = config('csgtlogin.usuario.campo');

		if(Hash::check(Input::get($campopassword), Auth::user()->$campopassword)){
			$userarray = array();

			if(Input::get('newpassword') <> '') {
				$newpwd = Input::get('newpassword')

				if($config('csgtlogin.repetirpasswords.habilitado')) {
					$historiales = DB::table(config('csgtlogin.repetirpasswords.tabla'))
						->where(config('csgtlogin.repetirpasswords.campousuario'), Auth::id())
						->lists(config('csgtlogin.repetirpasswords.campopassword'))

					foreach($historiales as $historial) {
						if(Hash::check($newpwd, $historial)) {
							Session::flash('message', trans('csgtlogin::reinicio.repetida'));
							Session::flash('type', 'danger');
							return Redirect::to(Config::get('csgtlogin.redirecteditarperfil'));	
						}
					}
				}

				$userarray[$campopassword] = Hash::make($newpwd);

				//Si tienen vencimiento las passwords, se lo asignamos
				if (config('csgtlogin.vencimiento.habilitado')) {
						$dias = (int)config('csgtlogin.vencimiento.dias');
				 		if ($dias == 0) {
				 			$fecha = null;
				 		}
				 		else {
							$fecha = Carbon::now('America/Guatemala')->addDays($dias);
						}
						$userarray[config('csgtlogin.vencimiento.campo')] = $fecha;
				}
			}

			if(config('csgtlogin.usuario.editable'))
				$userarray[$campousuario] = Input::get($campousuario);
			
			foreach(config('csgtlogin.camposeditarperfil') as $campo)
				$userarray[$campo['campo']] = Input::get($campo['campo']);

			$userarray['updated_at'] = Carbon::now('America/Guatemala');
			//dd($userarray);
			DB::table(config('csgtlogin.tabla'))
				->where(config('csgtlogin.tablaid'), Auth::id())
				->update($userarray);

			if(isset($userarray[$campopassword])) {
				DB::table(config('csgtlogin.repetirpasswords.tabla'))->insert([
					config('csgtlogin.repetirpasswords.campousuario')  => Auth::id(),
					config('csgtlogin.repetirpasswords.campopassword') => $userarray[$campopassword],
				]);
			}

			Session::flash('message', 'Perfil actualizado exitosamente');
			Session::flash('type', 'success');
			return Redirect::to(Config::get('csgtlogin.redirecteditarperfil'));
		}

		else {
			Session::flash('message', 'La contrase&ntilde actual no es correcta');
			Session::flash('type', 'danger');
			return Redirect::to(Config::get('csgtlogin.redirecteditarperfil'));
		}
	}

}