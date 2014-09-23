@extends('template/template')

@section('content')
	@if(Session::get('message'))
		<div class="alert alert-{{ Session::get('type') }} alert-dismissable .mrgn-top">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			{{ Session::get('message') }}
		</div>
	@endif
	{{ Form::open(array('url' => 'perfil/save', 'method' => 'POST', 'class'=>'form-horizontal', 'role'=>'form', 'id'=>'frmPerfil')) }}
		@foreach(Config::get('login::camposeditarperfil') as $campos)
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">{{ $campos['titulo'] }}</label>
				<div class="col-sm-10">
					<?php $campo = Config::get('login::usuario.campo'); ?>
		      <input 
						type         = "text" 
						class        = "form-control" 
						id           = "{{ $campos['campo'] }}" 
						name         = "{{ $campos['campo'] }}" 
						placeholder  = "{{ $campos['titulo'] }}"  
						value        = "{{ Auth::user()->$campos['campo'] }}" 
						autocomplete = "off" 
		      	data-bv-notempty>
				</div>
    	</div>
    @endforeach
		<div class="form-group">
	    <label for="email" class="col-sm-2 control-label">{{ Config::get('login::usuario.titulo') }}</label>
	    <div class="col-sm-10">
	    	<?php $campo = Config::get('login::usuario.campo'); ?>
	      <input 
					type         = "text" 
					class        = "form-control" 
					id           = "{{ Config::get('login::usuario.campo') }}" 
					name         = "{{ Config::get('login::usuario.campo') }}" 
					placeholder  = "{{ Config::get('login::usuario.titulo') }}"  
					value        = "{{ Auth::user()->$campo }}" 
					autocomplete = "off" 
	      	data-bv-notempty
	      	{{ (Config::get('login::usuario.editable')) ? ' ' : 'readonly="true" ' }}
	      	{{ (Config::get('login::usuario.tipo') == 'email') ? 'data-bv-emailAddress data-bv-emailAddress-message="Correo inválido"': '' }}>
	    </div>
	  </div>
	  <div class="form-group">
      <label for="password" class="col-sm-2 control-label">{{ Config::get('login::password.titulo').' Actual' }}</label>
      <div class="col-sm-10">
        <input 
					type         = "password" 
					class        = "form-control" 
					name         = "{{ Config::get('login::password.campo') }}" 
					id           = "{{ Config::get('login::password.campo') }}" 
					placeholder  = "{{ Config::get('login::password.titulo').' Actual' }}" 
					autocomplete = "off" 
        	data-bv-notempty>
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="col-sm-2 control-label">Password Nueva</label>
      <div class="col-sm-5">
        <input 
					type                      = "password" 
					class                     = "form-control" 
					name                      = "newpassword" 
					id                        = "newpassword" 
					placeholder               = "Password Nueva" 
					autocomplete              = "off" 
					data-bv-identical         = "true" 
					data-bv-identical-field   = "newpassword2" 
					data-bv-identical-message = "Las passwords no concuerdan">
      </div>
       <div class="col-sm-5">
        <input 
					type                      = "password" 
					class                     = "form-control" 
					name                      = "newpassword2" 
					placeholder               = "Repetir Password Nueva" 
					autocomplete              = "off" 
					data-bv-identical         = "true" 
					data-bv-identical-field   = "newpassword" 
					data-bv-identical-message = "Las passwords no concuerdan">
      </div>
    </div>
    <div class="form-group">
	  	<div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	* Dejar en blanco para no cambiar contrase&ntilde;a.
	   	</div>
	  </div>
    <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	      {{ Form::submit('Guardar', array('class' => 'btn btn-primary')) }}
	    </div>
	  </div>
	{{ Form::close() }}
	<script type="text/javascript">
		$(function() {
			$('#frmPerfil').bootstrapValidator({
        message: 'El campo es requerido',
        excluded: [':disabled'],
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        }
      });
		});
	</script>
@stop