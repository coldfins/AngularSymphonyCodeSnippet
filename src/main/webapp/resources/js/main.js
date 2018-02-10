var serverURL = 'http://localhost:8080/SpringAngular';
var table = null;
function setData(jsonData){
	var jsonArray = jsonData;
	if(null != table){
		table.destroy();
	}
	$('#student-body').html('');
	for(var i=0;i<jsonArray.data.length;i++){
		var name = jsonArray.data[i].name;
		var email = jsonArray.data[i].email;
		var mobile = jsonArray.data[i].mobile;
		$('#student-body').append('<tr><td>'+name+'</td><td>'+email+'</td><td>'+mobile+'</td></tr>');
	}
	table = $('#student-table').DataTable();
}