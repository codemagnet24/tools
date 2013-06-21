<h1>Cause Code Tool</h1>

<form class="form-horizontal well" method="post">
	<div class="control-group">
		<label class="control-label">Cause Code:  </label>
		<div class="control">
			<input type="text" name="rfo_code" placeholder="Cause code" readonly>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Product Type:  </label>
		<div class="control">
			<select></select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Main Outage:  </label>
		<div class="control">
			<select></select>
			<input type="text" name="layer1" placeholder="Main Outage">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Outage Type:  </label>
		<div class="control">
			<select></select>
			<input type="text" name="layer1" placeholder="Outage Type">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Equipment:  </label>
		<div class="control">
			<select></select>
			<input type="text" name="layer1" placeholder="Equipment">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Parts:  </label>
		<div class="control">
			<select></select>
			<input type="text" name="layer1" placeholder="Parts">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>


<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th>Cause Code</th>
				<th>Type</th>
				<th>Main Outage</th>
				<th>Outage Type</th>
				<th>Equipment</th>
				<th>Parts</th>
				<th>Valid</th>
				<th colspan=2>Action</th>
			</tr>
		</thead>
		<tbody id="codeData">
		</tbody>
	</table>
	<div class="pagination cpage"></div>

</div>

<form class="form-inline well" method="post">
	<label>Fault: </label>
	<input type="text" name="fault" placeholder="Fault">
	<button type="submit" class="btn btn-primary" >Add</button>
</form>
<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th>Number</th>
				<th>Name</th>
				<th>Valid</th>
				<th colspan=2>Action</th>
			</tr>
		</thead>
		<tbody id="faults">
		</tbody>
	</table>
	<div class="pagination fpage"></div>
</div>
<script type="text/javascript">

function gData(min,max,cur){
		var page = dcode.code.length;
			page /= 10;
			$('#codeData').html('');
			$('.cpage').html('');
			for(min;min<=max;min++){
			if((dcode.code[min] != undefined)){
				$('#codeData').append("<tr>");
				$('#codeData').append("<td>"+dcode.code[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.type[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.layer_1[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.layer_2[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.layer_3[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.layer_4[min]+"</tr>");
				$('#codeData').append("<td>"+dcode.valid[min]+"</tr>");
				$('#codeData').append("<td><a href=# title=Edit><i class=icon-pencil></i></a></tr>");
				$('#codeData').append("<td><a href=# title=Remove><i class=icon-trash></i></a></tr>");
				$('#codeData').append("</tr>");
			}
			}
	var as = "";
		for(var x=0;x<=page;x++){
				am = x*10;
				ax = am + 9;
        if((x+1)==cur){
          az = "class=active";
        }else{
          az = "";
        }

				as += "<li "+az+"><a href='javascript:void(0);' onclick=\"gData("+am+","+ax+","+(x+1)+")\">"+(x+1)+"</a></li>";	
		}
				$('.cpage').html("<ul>"+as+"</ul>");	

}


function fData(min,max,cur){
    var page = dcode.fault.length;
      page /= 10;
      $('#faults').html('');
      $('.fpage').html('');
      for(min;min<=max;min++){
      if((dcode.fault[min] != undefined)){
        $('#faults').append("<tr>");
        $('#faults').append("<td>"+dcode.fid[min]+"</tr>");
        $('#faults').append("<td>"+dcode.fault[min]+"</tr>");
        $('#faults').append("<td>"+dcode.fvalid[min]+"</tr>");
        $('#faults').append("<td><a href=# title=Edit><i class=icon-pencil></i></a></tr>");
        $('#faults').append("<td><a href=# title=Remove><i class=icon-trash></i></a></tr>");
        $('#faults').append("</tr>");
      }
      }
  var as = "";
    for(var x=0;x<=page;x++){
        am = x*10;
        ax = am + 9;
				if((x+1)==cur){
					az = "class=active";
				}else{
					az = "";	
				}
        as += "<li "+az+"><a href='javascript:void(0);' onclick=\"fData("+am+","+ax+","+(x+1)+")\">"+(x+1)+"</a></li>";
    }
        $('.fpage').html("<ul>"+as+"</ul>");

}


	var dcode = "";
	$.ajax({
		url: "./pages/codeData.php",
		data: {action: 'data'},
		type: "post",
		success: function (out) {
			dcode = JSON.parse(out);
			gData(0,9,1);
			fData(0,9,1);
		}
	});
</script>
