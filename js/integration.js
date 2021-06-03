
var annotation_tool_link = "http://localhost/annotation_tool/index.php";
var images = document.getElementsByTagName('img');
var srcList = [];
for(var i = 0; i < images.length; i++) {
    srcList.push(images[i].src);

    var link = annotation_tool_link + "?imgId=" + srcList[i];
    
    console.log(link);

    var a = document.createElement('a');
    var linkText = document.createTextNode("See anotations");
    a.appendChild(linkText);
    a.title = "See anotations";
    a.href = link;

    var parent = images[i].parentNode;

    parent.insertBefore(a, images[i]);
}