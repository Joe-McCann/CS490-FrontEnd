function redirect(redirectURL, username, token, eid=null){
    var form = document.createElement("form");
    var user = document.createElement("input");
    var tokn = document.createElement("input");

    form.method = "POST";
    form.action = redirectURL;

    user.value=username;
    user.name="username";
    form.appendChild(user);  

    tokn.value=token;
    tokn.name="token";
    form.appendChild(tokn);

    if (eid != null){
        var id = document.createElement("input");
        id.value=eid;
        id.name="eid";
        form.appendChild(id);
    }

    document.body.appendChild(form);

    form.submit();
}