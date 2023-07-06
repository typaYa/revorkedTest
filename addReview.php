<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<style>
    .text{
        color: #656565;
        margin-top: 30px;
        flex-direction: column;
        min-width: 500px;
        outline: none;
        border: none;
        border-bottom: 1px solid black;
    }
    .submit{
        background: none;
        border: 1px solid #a2a2a2;
        margin-top: 20px;
        transition: 0.3s;
        min-width: 500px;
    }
    .submit:hover{
        background-color: #d2d1d1;
    }
    .form{
        background-color: #ffffff;
        margin:auto;

        max-width: 500px;

    }
</style>
<form class="form"id="feedback-form" action="" method="post">
    <p style="margin-top: 120px;color: #656565">Ваш отзыв</p>
    <input class="text" type="text" name="text" placeholder="Введите ваш отзыв">
    <input class="submit" type="submit" name="submit" value="Отправить">
</form>
<script>
    $(document).ready(function () {
        $("form").submit(function () {
            // Получение ID формы
            var formID = $(this).attr('id');
            // Добавление решётки к имени ID
            var formNm = $('#' + formID);
            $.ajax({
                type: "POST",
                url: '/send.php',
                data: formNm.serialize(),
                beforeSend: function () {
                    // Вывод текста в процессе отправки
                    $(formNm).html('<p style="text-align:center">Отправка...</p>');
                },
                success: function (data) {
                    // Вывод текста результата отправки
                    $(formNm).html('<p style="text-align:center">'+data+'</p>');
                },
                error: function (jqXHR, text, error) {
                    // Вывод текста ошибки отправки
                    $(formNm).html(error);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>