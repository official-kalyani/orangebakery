<!DOCTYPE html>
<html>
<head>
	<title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<style type="text/css">
ul.dropdown-menu1 {
    list-style: none outside none;
}

ul.dropdown-menu1 li {
    list-style-type: none;
}
</style>
<body>
<!-- <input class="form-control mr-sm-2 ser-in" type="search" placeholder="Search Products" id="search_product_name"> -->
<div class="autocomplete" style="width:300px;">
	<input type="text" name="search_product_name" id="search_product_name" class="txt-input" placeholder="Search dogs in your city" autocomplete="off">
</div>
<div id="productLists"></div>
</body>
<script>
	$('#search_product_name').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
         var _token = "{{@csrf_token()}}";
         $.ajax({
          url:"{{ url('/fetchProducts') }}",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
            $('#productLists').fadeIn();  
            $('#productLists').html(data);
          }
         });
        }
    });
</script>
</html>