function onError(xhr, errorType, exception) {
  var responseText;
  $("#dialog").html("");
  var response_json = xhr.responseText;
  var response = JSON.parse(response_json);
  $("#dialog").html("Error : "+response['msg']);
}