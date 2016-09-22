@extends('app')

@section('pagecss')
<style>
	.inputfile {
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
	}

	/*.photobox{
		display: inline!important;
	}*/

	#imagepick li{
		display: inline-block;
		/*position: relative;*/
		/*display: block;*/
	    
	    /*line-height: 22px;*/
	    /*float: left;*/
	    
	}

	.image-container{
		position: relative;
		width: 80px;
	    height: 80px;
	    margin: 0 5px;
	    cursor: pointer;
	    border: 1px dashed #cecece;
	    border-radius: 5px;
	    background: url(../admin/img/add-product.png)no-repeat;
    	background-position: center;
    	line-height: 22px;
    	float: left;
	}

	.image-container .image-preview{
	    border-radius: 5px;
	    height: auto;
	    max-width: 100%;
	    max-height: 100%;
	    vertical-align: middle;
	    border: 0;
	    /*display: block;*/
	    /*background: url(../admin/img/add-product.png)no-repeat;*/
    	/*background-position: center;*/

	}

	 .image-container .image-remove{
		position: absolute;
		top: 2px;
		right: 2px;
		z-index: 100;
		background-color: #FFF;
		padding: 2px 2px 2px;
		color: #000;
		font-weight: bold;
		cursor: pointer;
		opacity: .8;
		text-align: center;
		font-size: 22px;
		line-height: 10px;
		border-radius: 50%;
		/*float: right;*/
		/*float:right;
	}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">{{trans('item.new_item')}}</div>

				<div class="panel-body">
					@if (Session::has('message'))
					<div class="alert alert-info">{{ Session::get('message') }}</div>
					@endif
					{!! Html::ul($errors->all()) !!}

					{!! Form::open(array('url' => 'items', 'files' => true)) !!}

					<div class="form-group">
					{!! Form::label('upc_ean_isbn', trans('item.upc_ean_isbn')) !!}
					{!! Form::text('upc_ean_isbn', Input::old('upc_ean_isbn'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('item_name', trans('item.item_name').' *') !!}
					{!! Form::text('item_name', Input::old('item_name'), array('class' => 'form-control')) !!}
					</div>

					{{-- Edited by Eries Herman --}}
					<div class="form-group">
					{!! Form::label('item_category', trans('item.item_category').' *') !!}
					{!! Form::select('item_category', $selectCategories ,Input::old('item_category'), array('class' => 'form-control','required')) !!}
					{{-- {!! Form::select('item_category',$selectCategories) !!} --}}
					</div>
					{{-- End of edited by Eries Herman --}}

					<div class="form-group">
					{!! Form::label('size', trans('item.size')) !!}
					{!! Form::text('size', Input::old('size'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('weight', trans('item.weight') . ' (Kg)' )  !!}
					{!! Form::text('weight', Input::old('weight'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('length', trans('item.length'))  !!}
					{!! Form::text('length', Input::old('length'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('width', trans('item.width'))  !!}
					{!! Form::text('width', Input::old('width'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('height', trans('item.height'))  !!}
					{!! Form::text('height', Input::old('height'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('description', trans('item.description')) !!}
					{!! Form::textarea('description', Input::old('description'), array('class' => 'form-control textarea ')) !!}
					</div>

					{{-- <div class="form-group">
						
						
					</div> --}}
					<div class="form-group" >
						<label>Product Image</label>
						<ul id="imagepick" >
							<li>
								<div class="image-container" >
									<img class="image-preview" >
								</div>
								<input type="file"  accept="image/*" class="inputfile" name="avatar[]" >
								<br/>
								<a class="btn btn-xs btn-danger btn-image-remove disabled" style="width:80px;margin-left:5px;" ><i class="fa fa-trash-o" ></i></a>
							</li>
							{{-- <li>
								<div class="image-container" >
									<img class="image-preview" >
								</div>
								<input type="file"  accept="image/*" class="inputfile" name="avatar[]" >
								<br/>
								<a class="btn btn-xs btn-danger btn-image-remove hide" style="width:80px;margin-left:5px;" ><i class="fa fa-trash-o" ></i></a>
							</li> --}}
							{{-- <li>
								<div class="image-container" >
									<img class="image-preview">
								</div>
							</li> --}}
						</ul>
					</div>
					{{-- <div class="clearfix" ></div> --}}



					{{-- <div class="form-group">
						<label>Product Image</label>
					{!! Form::file('avatar', Input::old('avatar'), array('class' => 'form-control')) !!}
					</div> --}}

					<div class="form-group">
					{!! Form::label('cost_price', trans('item.cost_price').' *') !!}
					{!! Form::text('cost_price', Input::old('cost_price'), array('class' => 'form-control', 'id' => 'cost_price')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('selling_price', 'Price Ref (Selling Price)'.' *') !!}
					{!! Form::text('selling_price', Input::old('selling_price'), array('class' => 'form-control', 'id' => 'selling_price')) !!}
					</div>

					<div class="form-group">
					<label>Retail Price *</label>
					<input type="text" name="retail_price" class="form-control" id="retail-price" >
					</div>

					<div class="form-group">
					{!! Form::label('quantity', trans('item.quantity')) !!}
					{!! Form::text('quantity', Input::old('quantity'), array('class' => 'form-control')) !!}
					</div>

                     <div class="form-group">
                    {!! Form::label('formula_remarks', 'Remarks of custom formula') !!}
                    {!! Form::text('formula_remarks', Input::old('formula_remarks'), array('class' => 'form-control')) !!}
                    </div>

                    <div class="form-group">
                    {!! Form::label('product_feature', 'Product Feature') !!}
                    {!! Form::select('product_feature', ['Y'=>'Yes','N'=>'No'],Input::old('product_feature'), array('class' => 'form-control')) !!}
                    </div>

					{!! Form::submit(trans('item.submit'), array('class' => 'btn btn-primary')) !!}

					{!! Form::close() !!}


				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('pagejs')
<!-- Tinymce -->
<script src="{{ asset('/admin/js/tinymce/tinymce.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $( "#cost_price" ).change(function() {
        var selectedValue = this.value;
        $( "#selling_price" ).val( (selectedValue * {{ $shippingFactor}} * {{ $marginFactor }}).toFixed(2));
        
        $('#retail-price').val(Number($('#selling_price').val()) * 10 /100);
    });

    //bootstrap WYSIHTML5 - text editor
    // $(".textarea").wysihtml5();

    // Tinymce Editor
    tinymce.init({
	  selector: 'textarea'
	});

	
	(function ($) {

		// Tampilkan dialog pilih image
		$(document).on('click','.image-container',function(){
			// alert('tampilkan image');
			$(this).next('input').trigger('click');
			// $(this).next('input').trigger('click');
		});

		// tampilkan image ke image preview
	    $(document).on('change','input[type=file]',function(){
	    	var imgPrev = $(this).prev().children('img');
	    	var btnRemove = $(this).next().next();

         	var reader = new FileReader();
         	reader.onload = function (e) {
	            imgPrev.attr('src',e.target.result);
	             // tampilkan button remove image
	            btnRemove.removeClass('disabled');

	            // tampilkan input image baru
	            $('#imagepick').append('<li><div class="image-container" ><img class="image-preview" ></div><input type="file"  accept="image/*" class="inputfile" name="avatar[]" ><br/><a class="btn btn-xs btn-danger btn-image-remove disabled" style="width:80px;margin-left:5px;" ><i class="fa fa-trash-o" ></i></a></li>');
	         }

         reader.readAsDataURL($(this)[0].files[0]);
	    });
	                        
	})(jQuery);



</script>
 

 
@endsection