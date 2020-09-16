$('#customFile').on('change', function(){
  files = $(this)[0].files; name = '';
  for(var i = 0; i < files.length; i++){
    name += '\"' + files[i].name + '\"' + (i != files.length-1 ? ", " : "");
  } $(".custom-file-label").html(name);
});