function previewImg(input){
    if (input.files && input.files[0]!=""){
        var reader = new FileReader();
        reader.onload=function(e){
            $(".image-preview").css('background-image','url('+e.target.result+')');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on("click",".view",function(){
    var id = $(this).data('id');
    var str = '<img src = "uploads/'+id+'">';
    $(".modal-body").html(str);
});