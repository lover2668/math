{if condition="$debug eq 0"}
                <h1 align="center">请从学生端登录!</h1>
                <div style="display: none;"><?php print_r(session('')); ?></div>
                <?php die; ?>
    {/if}<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数学</title>
    <link rel="stylesheet" href="__PUBLIC__/static/math/css/login.css">
    <link rel="icon" type="image/png" href="__PUBLIC__/plugin/lib/i/yixue-tt-logo.png">
</head>
<body>
    
<!-- begin -->


    <div id="login">
        <div class="wrapper">
            <div class="login">
                <div class="container offset1 loginform" id="loginform">
                    <div id="owl-login">
                        <div class="hand"></div>
                        <div class="hand hand-r"></div>
                        <div class="arms">
                            <div class="arm"></div>
                            <div class="arm arm-r"></div>
                        </div>
                    </div>
                    <div class="pad">
                        <!--<input type="hidden" name="_csrf" value="9IAtUxV2CatyxHiK2LxzOsT6wtBE6h8BpzOmk=">-->
                        <div class="control-group">
                            <div class="controls">
                                <label for="text" class="control-label fa fa-user"></label>
                                <input id="text" type="text" name="username" placeholder="用户名" tabindex="1"
                                       autofocus="autofocus" class="form-control input-medium">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <label for="password" class="control-label fa fa-asterisk"></label>
                                <input id="password" type="password" name="pwd" placeholder="密码" tabindex="2"
                                       class="form-control input-medium">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <!--<a href="#" tabindex="5" class="btn pull-left btn-link text-muted">Forgot password?</a>-->
                        <!--<a href="#" tabindex="6" class="btn btn-link text-muted">Sign Up</a>-->
                        <button type="submit" tabindex="4" name="login_submit" class="btn btn-primary">登录</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


<script src="__PUBLIC__/plugin/lib/js/jquery.min.js"></script>
<script src="__PUBLIC__/plugin/lib/layer/layer.js"></script>
<script src="__PUBLIC__/static/lib/js/my.ui.js"></script>
<script src="__PUBLIC__/static/math/js/config.js"></script>
<script src="__PUBLIC__/plugin/lib/login/class.Login.js"></script>
<script>
    $(function() {
        var login   = new Login( '#login');
    });

</script>
</body>
</html>