function redirect(redirectURL, username, token){
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

    document.body.appendChild(form);

    form.submit();
}