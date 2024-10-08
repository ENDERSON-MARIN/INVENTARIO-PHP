<?php namespace App\Http\Controllers;

    use CRUDBooster;
    use Session;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Carbon;
    use RealRashid\SweetAlert\Facades\Alert;

    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\Redirect;

    use App\Models\Cliente;
    use App\Http\Requests\ClienteFormRequest;

	class AdminClientesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "clientes";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Identificacion","name"=>"identificacion"];
			$this->col[] = ["label"=>"Nombres","name"=>"nombres"];
			$this->col[] = ["label"=>"Estado","name"=>"estado"];
			$this->col[] = ["label"=>"Correo","name"=>"correo"];
			$this->col[] = ["label"=>"Telefonos","name"=>"telefonos"];
			$this->col[] = ["label"=>"Direccion","name"=>"direccion"];
			$this->col[] = ["label"=>"Observaciones","name"=>"observaciones"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Identificacion','name'=>'identificacion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Nombres','name'=>'nombres','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Estado','name'=>'estado','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Correo','name'=>'correo','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Telefonos','name'=>'telefonos','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Direccion','name'=>'direccion','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Observaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Responsable Creacion','name'=>'responsable_creacion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Responsable Edicion','name'=>'responsable_edicion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Imagen','name'=>'imagen','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Identificacion","name"=>"identificacion","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Nombres","name"=>"nombres","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Estado","name"=>"estado","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Correo","name"=>"correo","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Telefonos","name"=>"telefonos","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Direccion","name"=>"direccion","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Observaciones","name"=>"observaciones","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Responsable Creacion","name"=>"responsable_creacion","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Responsable Edicion","name"=>"responsable_edicion","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Imagen","name"=>"imagen","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			# OLD END FORM

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
	        $this->addaction = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert        = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();
            $this->table_row_color[] = ['condition'=>'[estado] == "ACTIVO"', 'color'=>'success'];
			$this->table_row_color[] = ['condition'=>'[estado] == "INACTIVO"', 'color'=>'danger'];


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

	    //By the way, you can still create your own method in here... :)

        public function getAdd (){
			$data['page_title'] = "Registrar Nuevo Cliente";

			$date = Carbon::now();
			$fecha_hora = $date->format('d-m-Y H:i:s a');
			$responsable_creacion = CRUDBooster::myId();

			$estado_cliente= [
				"1" => "ACTIVO",
				"2" => "INACTIVO"
			];

            //dd($fecha_hora, $responsable_creacion, $estado_cliente);

			$this->cbView('clientes.crear', compact('data', 'fecha_hora', 'responsable_creacion', 'estado_cliente'));

		}

		public function store(ClienteFormRequest $request)
		{
			try {
				DB::beginTransaction();

				$cliente = new Cliente;
				$cliente->identificacion=$request->input('txtIdentificacion');
				$cliente->nombres=$request->input('txtNombres');
				$cliente->estado=$request->input('slEstado');
				$cliente->correo=$request->input('txtCorreo');
				$cliente->telefonos=$request->input('txtTelefonos');
				$cliente->direccion=$request->input('txtDireccion');
				$cliente->observaciones=$request->input('txtObservaciones');
				$cliente->responsable_creacion=$request->input('txtResponsableCreacion');
				$cliente->imagen=$request->input('imagen');

                if(Input::hasFile('imagen')){
					$file=Input::File('imagen');
					$file->move(public_path().'/imagenes/clientes',$file->getClientOriginalName());
					$cliente->imagen=$file->getClientOriginalName();
				}

				//dd($cliente);

				$cliente->save();

				DB::commit();

				Alert::success('CLIENTE GUARDADO EXITOSAMENTE!', '');

				return back();

			}catch(\Exception $e){

				dd($e);

				DB::rollBack();

				Alert::error('ERROR AL AGREGAR EL CLIENTE, POR FAVOR VERIFIQUE!', '');

				return back();
		}
		}


		public function getEdit ($id){
			$data['page_title'] = "Editar Cliente";

			$cliente = Cliente::findOrFail($id);

			$date = Carbon::now();
			$fecha_hora = $date->format('d-m-Y H:i:s a');
			$responsable_edicion = CRUDBooster::myId();

			$estado_cliente= [
				"1" => "ACTIVO",
				"2" => "INACTIVO"
			];

			//dd($cliente, $fecha_hora, $responsable_edicion, $estado_cliente);

			$this->cbView('clientes.edit', compact('data', 'cliente',  'fecha_hora', 'responsable_edicion', 'estado_cliente'));

		}


		public function postClienteEdit(Request $request, $id)
			{
                $cliente = Cliente::findOrFail($id);

				//dd($cliente);

				try {
					DB::beginTransaction();
					$cliente->identificacion=$request->input('txtIdentificacion');
                    $cliente->nombres=$request->input('txtNombres');
                    $cliente->estado=$request->input('slEstado');
                    $cliente->correo=$request->input('txtCorreo');
                    $cliente->telefonos=$request->input('txtTelefonos');
                    $cliente->direccion=$request->input('txtDireccion');
                    $cliente->observaciones=$request->input('txtObservaciones');
                    $cliente->responsable_edicion=$request->input('txtResponsableEdicion');
                    //$cliente->imagen=$request->input('imagen');

                    if(Input::hasFile('imagen')){
                        $file=Input::File('imagen');
                        $file->move(public_path().'/imagenes/clientes',$file->getClientOriginalName());
                        $cliente->imagen=$file->getClientOriginalName();
                    }

                    //dd($cliente);

					$cliente->update();

					//dd($cliente);

					DB::commit();

					Alert::success('CLIENTE EDITADO EXITOSAMENTE!', '');

					return back();

				}catch(\Exception $e){

					dd($e);

					DB::rollBack();

					Alert::error('ERROR AL EDITAR EL CLIENTE, POR FAVOR VERIFIQUE!', '');

					return back();
		}

		}

 		public function getDetail ($id){
			$data['page_title'] = "Ver Detalles del Cliente";

			$cliente = DB::table('clientes')
			->join('cms_users', 'clientes.responsable_creacion', '=', 'cms_users.id')
			->select('clientes.*', 'cms_users.name as creado_por')
			->where('clientes.id', $id)
			->get();

			//dd($cliente);

			$this->cbView('clientes.show', compact('id', 'data', 'cliente'));

		}

		public function destroy($id)
		{
			$proveedor = Proveedor::findOrFail($id);

			//dd($proveedor);

			$proveedor->delete();

			Alert::success('PROVEEDOR ELIMINADO EXITOSAMENTE!', '');

			return back();
		}



	}
