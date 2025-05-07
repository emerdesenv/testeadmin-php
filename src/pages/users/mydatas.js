$(function() {

    $fileinput = $("input[type=file]");
    $avatar    = $(".user-avatar-profile");

    $(".change-avatar").on("click", function() {
        $fileinput[0].click();
    });

    $fileinput.change(function() {
        var input  = this;
        var reader = new FileReader();
        var file   = this.files[0];

        reader.onload = function (e) {
            $avatar.attr("src", e.target.result);
            $("#userLogedinDropdown img").removeAttr("avatar").attr("src", e.target.result);

            var formdata = new FormData();
            formdata.append("avatar", file);

            $avatar.parent("span").addClass("loading img");
            
            $.ajax({
                method      : "POST",
                url         : "pages/users/controller?from=mydata",
                data        : formdata,
                processData : false,
                contentType : false
            }).done(function() {
                $avatar.parent("span").removeClass("loading img");
            });
        }

        reader.readAsDataURL(input.files[0]);
    });

    $("form.meusdados-form").on("submit", function() {
        $password  = $("#senha");
        $password2 = $("#senha2");

        if($password.val() != $password2.val()) {
            showFormErrors([
                { field: "senha", message: "Senha não pode ser diferente" },
                { field: "senha2", message: "Senha não pode ser diferente" }
            ]);

            return false;
        } else  {
            return true
        }
    });
});