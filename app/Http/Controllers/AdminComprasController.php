<?php namespace App\Http\Controllers;

    use Session;
	use CRUDBooster;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Carbon;
    use RealRashid\SweetAlert\Facades\Alert;

    use App\Models\Compra;
    use App\Models\CompraDetalles;
    use App\Models\Producto;
    use App\Http\Requests\CompraFormRequest;


	class AdminComprasController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "compras";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Proveedor","name"=>"proveedor_id","join"=>"proveedores,id"];
			$this->col[] = ["label"=>"Número de  Factura","name"=>"num_factura"];
			$this->col[] = ["label"=>"Responsable","name"=>"responsable_creacion", "join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Observaciones","name"=>"observaciones"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Proveedor Id','name'=>'proveedor_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'proveedor,id'];
			$this->form[] = ['label'=>'Num Factura','name'=>'num_factura','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Responsable Creacion','name'=>'responsable_creacion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Responsable Edicion','name'=>'responsable_edicion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Observaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Proveedor Id','name'=>'proveedor_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'proveedor,id'];
			//$this->form[] = ['label'=>'Num Factura','name'=>'num_factura','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Responsable Creacion','name'=>'responsable_creacion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Responsable Edicion','name'=>'responsable_edicion','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Observaciones','name'=>'observaciones','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
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
	    | Hook for manipulate data get before add data is execute
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
	    | Hook for manipulate data get before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = get post data
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
			$data['page_title'] = "REGISTRAR NUEVA COMPRA A ALMACÉN";

			$proveedores = DB::table('proveedores')->get();

  			$productos = DB::table('productos as prod')
  			->select(DB::raw('CONCAT(prod.id, "---",prod.nombre) AS producto_completo'),'prod.id', 'prod.referencia', 'prod.stock')
  			->where('prod.estado','=','ACTIVO')
  			->get();

			$date = Carbon::now();
			$fecha_hora = $date->format('Y-m-d H:i:s');
			$responsable_creacion = CRUDBooster::myId();
			$privilegio = CRUDBooster::myPrivilegeId();

			//dd($proveedores, $materiales);

			$this->cbView('compras.crear', compact('data', 'proveedores', 'productos', 'fecha_hora', 'responsable_creacion'));


		}

        public function store(CompraFormRequest $request){
                        //dd($request->all());
					try{
						DB::beginTransaction();
						$compra=new Compra;
						$compra->proveedor_id=$request->get('slProveedor');
						$compra->num_factura=$request->get('txtFactura');
						$compra->fecha_compra=$request->get('txtFechaCompra');
						$compra->responsable_creacion=$request->get('txtResponsableCreacion');
						$compra->observaciones=$request->get('txtObservaciones');

						//dd($compra);

						$compra->save();

                        $idproducto = $request->get('idproducto');
						$cantidad = $request->get('cantidad');
						$precio = $request->get('precio');
						$observaciones = $request->get('observaciones');

                        //dd($idproducto, $cantidad, $precio, $observaciones);

						$cont = 0;

						while($cont < count($idproducto)){
							$detalle = new CompraDetalles();
							$detalle->compra_id= $compra->id;
							$detalle->producto_id= $idproducto[$cont];
							$detalle->cantidad= $cantidad[$cont];
							$detalle->precio_compra= $precio[$cont];
							$detalle->observaciones= $observaciones[$cont];

							//dd($detalle);

							$detalle->save();
							$cont=$cont+1;
						}
						DB::commit();

						Alert::success('COMPRA GUARDADA EXITOSAMENTE!', '');

						return back();

					}catch(\Exception $e){

						DB::rollBack();

						Alert::error('ERROR AL GUARDAR LA COMPRA, POR FAVOR VERIFIQUE!', '');

						dd($e);

						return back();
			}

		}

 		public function getEdit ($id){
			$data['page_title'] = "EDITAR COMPRA";

            $responsable_edicion = CRUDBooster::myId();

			$proveedores = DB::table('proveedores')->get();

            //$productos = DB::table('productos')->get();

            $productos = DB::table('productos as p')
            ->select('p.*')
            ->where([
                    ['p.estado', '=', 'ACTIVO'],
                    ['p.deleted_at', NULL],
                   ])
            ->groupBy('p.id')
            ->get();


			$compra=DB::table('compras as c')
			->join('proveedores as p','c.proveedor_id','=','p.id')
			->join('compra_detalles as cd','c.id','=','cd.compra_id')
			->join('cms_users as u','c.responsable_creacion','=','u.id')
			->select('c.id','c.num_factura', 'c.fecha_compra', 'c.responsable_creacion','c.observaciones', 'c.proveedor_id', 'p.nombres as proveedor_compra', 'u.name as responsable_compra', DB::raw('sum(cd.cantidad*cd.precio_compra) as total_compra'))
			->where('c.id','=',$id)
			->groupBy('c.id')
			->first();

			$compraDetalles = DB::table('compra_detalles')
			->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
			->join('productos', 'compra_detalles.producto_id', '=', 'productos.id')
			->select('compra_detalles.*', 'compras.id as compra_id', 'productos.nombre as nombre_producto')
			->where('compras.id', $id)
			->get();

			//dd($compra, $compraDetalles);

            //dd($productos);

			$this->cbView('compras.edit', compact('id', 'data', 'proveedores', 'productos', 'compra',  'responsable_edicion', 'compraDetalles'));

		}

        public function postComprasEdit(Request $request, $id){
            $compra = Compra::findOrFail($id);

			$compraDetalles = DB::table('compra_detalles')
			->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
			->join('productos', 'compra_detalles.producto_id', '=', 'productos.id')
			->select('compra_detalles.*', 'productos.nombre as nombre_producto')
			->where('compras.id', $id)
			->get();

            //dd($compra, $compraDetalles);

            try {
                DB::beginTransaction();

                $compra->proveedor_id=$request->get('slProveedor');
                $compra->num_factura=$request->get('txtFactura');
                $compra->fecha_compra=$request->get('txtFechaCompra');
                $compra->responsable_edicion=$request->get('txtResponsableEdicion');
                $compra->observaciones=$request->get('txtObservaciones');

                //dd($compra);

                $compra->save();

                DB::commit();

                Alert::success('ENCABEZADO DE COMPRA EDITADO EXITOSAMENTE!', '');

                return back();

                }catch(\Exception $e){

                    DB::rollBack();

                    Alert::error('ERROR AL EDITAR EL ENCABEZADO DE LA COMPRA, POR FAVOR VERIFIQUE!', '');


                    return back();
                }

        }

        public function AgregarProductoCompra(Request $request){

                  //dd($request->all());

            try {
                DB::beginTransaction();

                $idproducto = $request->get('pslProducto');
                $idcompra = $request->get('ptxtIdCompra');
                $precio_compra = $request->get('ptxtPrecioCompra');
                $cantidad = $request->get('ptxtCantidad');
                $observaciones = $request->get('ptxtObservacionesDetalles');

                $detalle = new CompraDetalles();
                $detalle->compra_id= $idcompra;
                $detalle->producto_id= $idproducto;
                $detalle->precio_compra= $precio_compra;
                $detalle->cantidad= $cantidad;
                $detalle->observaciones= $observaciones;

                    //dd($detalle);

                    $detalle->save();

                    DB::commit();

                    Alert::success('PRODUCTO GUARDADO EXITOSAMENTE!', '');

                    return back();

                }catch(\Exception $e){

                    DB::rollBack();

                    Alert::error('ERROR AL GUARDAR EL PRODUCTO, POR FAVOR VERIFIQUE!', '');

                    dd($e);

                    return back();
                }

        }

        public function EditarProductoCompra (Request $request, $id){
                $ingresoDetalles = DetalleIngreso::findOrFail($id);

                //dd($ingresoDetalles);

                try {
                    DB::beginTransaction();

                    $ingresoDetalles->material_id=$request->get('slMaterial');
                    $ingresoDetalles->precio_compra=$request->get('txtPrecio');
                    $ingresoDetalles->cantidad=$request->get('txtCantidad');
                    $ingresoDetalles->observaciones=$request->get('txtObservaciones');

                    //dd($ingresoDetalles);

                    $ingresoDetalles->save();

                    DB::commit();

                    Alert::success('DETALLE DE INGRESO EDITADO EXITOSAMENTE!', '');

                    return back();

                    }catch(\Exception $e){

                        DB::rollBack();

                        Alert::error('ERROR AL EDITAR EL DETALLE DE INGRESO, POR FAVOR VERIFIQUE!', '');

                        return back();
                    }

        }

		public function getDetail ($id){
					$data['page_title'] = "VER DETALLES DE LA COMPRA";

					$compra=DB::table('compras as c')
					->join('proveedores as p','c.proveedor_id','=','p.id')
					->join('compra_detalles as cd','c.id','=','cd.compra_id')
					->join('cms_users as u','c.responsable_creacion','=','u.id')
					->select('c.id','c.num_factura', 'c.fecha_compra', 'c.responsable_creacion','c.observaciones', 'p.nombres as proveedor_compra', 'u.name as responsable_compra', DB::raw('sum(cd.cantidad*cd.precio_compra) as total_compra'))
					->where('c.id','=',$id)
					->groupBy('c.id')
					->first();

					$compraDetalles = DB::table('compra_detalles')
					->join('productos', 'compra_detalles.producto_id', '=', 'productos.id')
					->select('compra_detalles.*', 'productos.nombre as nombre_producto')
					->where('compra_detalles.compra_id', $id)
					->get();

					//dd($compra, $compraDetalles);

					$this->cbView('compras.show', compact('data', 'compra', 'compraDetalles'));

		}

		public function destroyProductoCompra($id){
			$detalle = CompraDetalles::findOrFail($id);

			//dd($detalle);

			$detalle->delete();

			Alert::success('PRODUCTO ELIMINADO EXITOSAMENTE!', '');

			return back();
		}


	}
