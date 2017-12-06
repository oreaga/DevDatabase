document.getElementById("updateDesc").onclick = function(){
    var newDesc = window.prompt("Description: ");
    document.getElementById("newitemDesc").value = newDesc;
    document.getElementById("descUpdate").submit();
};