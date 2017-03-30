var Login = function() {

    var handleLogin = function() {

        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                username: {
                    required: "需要输入帐号"
                },
                password: {
                    required: "需要输入密码"
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function(form) {
                var username = $('#username').val(),
                    pwd = $('#password').val();
                $.post('',{
                    username: username,
                    pwd: pwd
                },function(result){
                    alert(result.status);
                    jumptoIndex();
                },'json')
            }
        });

        function jumptoIndex(){
            window.location.href = 'ecommerce_index.php';
        }

        $('.login-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    //$('.login-form').submit(); //form validation success, call ajax form submit
                    var username = $('#username').val(),
                        pwd = $('#password').val();
                    $.post('',{
                        username: username,
                        pwd: pwd
                    },function(result){
                        alert(result.status);
                        jumptoIndex();
                    },'json')
                }
                return false;
            }
        });
    };

    var handleRegister = function() {

        $('.register-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {

                fullname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },

                username: {
                    required: true
                },
                password: {
                    required: true
                },
                rpassword: {
                    equalTo: "#register_password"
                },

                tnc: {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
					email: {
	                    required: "此处为必填项"
	                },
	            	username: {
	                    required: "此处为必填项"
	                },
	                password: {
	                    required: "此处为必填项"
	                },
	                rpassword: {
	                	equalTo: "请再次输入相同的值"
	                },
                tnc: {
                    required: "Please accept TNC first."
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   

            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function(error, element) {
                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
                    error.insertAfter($('#register_tnc_error'));
                } else if (element.closest('.input-icon').size() === 1) {
                    error.insertAfter(element.closest('.input-icon'));
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {
                var firstname = $('#firstname').val(),
                    lastname = $('#lastname').val(),
                    username = $('#reg_username').val(),
                    email = $('#email').val(),
                    pwd = $('#register_password').val();
                $.post('',{
                    firstname: firstname,
                    lastname: lastname,
                    username: username,
                    email: email,
                    pwd: pwd
                },function(result){
                    jumptoLogin();
                },'json')
            }
        });

        $('.register-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.register-form').validate().form()) {
                    //$('.register-form').submit();
                    var firstname = $('#firstname').val(),
                        lastname = $('#lastname').val(),
                        username = $('#reg_username').val(),
                        email = $('#email').val(),
                        pwd = $('#register_password').val();
                    $.post('',{
                        firstname: firstname,
                        lastname: lastname,
                        username: username,
                        email: email,
                        pwd: pwd
                    },function(result){
                        if(result.status == 1){
                            jumptoLogin();
                        }
                    },'json')


                }
                return false;
            }
        });

        function jumptoLogin(){
            window.location.href = 'admin_login.php';
        }

        jQuery('#register-btn').click(function() {
            jQuery('.login-form').hide();
            jQuery('.register-form').show();
        });

        jQuery('#register-back-btn').click(function() {
            jQuery('.login-form').show();
            jQuery('.register-form').hide();
        });
    }

    return {
        //main function to initiate the module
        init: function() {

            handleLogin();
            handleRegister();

        }

    };

}();