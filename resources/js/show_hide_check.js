//{{-- ROW PANEL BOLSILLO EN U --}}
{/* <div class="row" id="bolsillo_U" style="display: none">
    <div class="panel panel-primary">
        <div class="panel-heading text-center">
            <h4>VARIANTES BOLSILLO EN U</h4>
        </div>
        <div class="panel-body">
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="720DPI" value="720DPI" checked>720
                        DPI</label><br>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="1440DPI" value="1440DPI">1440
                        DPI</label><br>
                </div>
            </div>
        </div>
    </div>
</div> */}
 //{{-- ROW PANEL BOLSILLO EN L --}}
{/* <div class="row" id="bolsillo_L" style="display: none">
    <div class="panel panel-primary">
        <div class="panel-heading text-center">
            <h4>VARIANTES BOLSILLO EN L</h4>
        </div>
        <div class="panel-body">
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="720DPI" value="720DPI" checked>720
                        DPI</label><br>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="1440DPI" value="1440DPI">1440
                        DPI</label><br>
                </div>
            </div>
        </div>
    </div>
</div> */}
 //{{-- ROW PANEL BOLSILLO EN T --}}
{/* <div class="row" id="bolsillo_T" style="display: none">
    <div class="panel panel-primary">
        <div class="panel-heading text-center">
            <h4>VARIANTES BOLSILLO EN T</h4>
        </div>
        <div class="panel-body">
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="720DPI" value="720DPI" checked>720
                        DPI</label><br>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label><input type="radio" name="calidad_impresion" id="1440DPI" value="1440DPI">1440
                        DPI</label><br>
                </div>
            </div>
        </div>
    </div>
</div> */}


$("input[name='tipo_producto']").change(function() {
    if ($(this).val() == 'BOLSILLO_U') {
        $('#bolsillo_U').show();
        $('#bolsillo_L').hide();
        $('#bolsillo_T').hide();
    }else if ($(this).val() == 'BOLSILLO_L') {
        $('#bolsillo_U').hide();
        $('#bolsillo_L').show();
        $('#bolsillo_T').hide();
    }else if ($(this).val() == 'BOLSILLO_T') {
        $('#bolsillo_U').hide();
        $('#bolsillo_L').hide();
        $('#bolsillo_T').show();
    }else{
        $('#bolsillo_U').hide();
        $('#bolsillo_L').hide();
        $('#bolsillo_T').hide();
    }
});
