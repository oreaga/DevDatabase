window.onload = function() {
        var buttons = document.getElementsByClassName("updateDesc");
        for(var i = 0; i < buttons.length; i++) {
            var b = buttons[i];
            b.onclick = setDesc;
        }
};

function setDesc(){
    var newDesc = window.prompt("Description: ");
    document.getElementByName("newitemDesc").value = newDesc;
    document.getElementByName("descUpdate").submit();
}