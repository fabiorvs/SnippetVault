<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login • CodeVault</title>
    <style>
    body {
        margin: 0;
        font-family: system-ui, Segoe UI, Roboto, Arial;
        background: #0f1115;
        color: #e8eef7;
        display: grid;
        place-items: center;
        height: 100vh
    }

    .card {
        width: min(420px, 92vw);
        background: #151922;
        border: 1px solid #1f2532;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .3)
    }

    .input {
        width: 100%;
        padding: 12px 12px;
        border-radius: 12px;
        border: 1px solid #232a37;
        background: #0f1421;
        color: #e8eef7;
        outline: none
    }

    .btn {
        width: 100%;
        margin-top: 10px;
        padding: 12px;
        border: 0;
        border-radius: 12px;
        background: #5b8cff;
        color: #0b0d12;
        font-weight: 700;
        cursor: pointer
    }

    .msg {
        margin: 0 0 10px;
        color: #ffb3b3
    }

    label {
        display: block;
        margin: 10px 0 6px;
        color: #a7b0c0;
        font-size: 13px
    }

    h1 {
        margin: 0 0 6px;
        font-size: 18px
    }

    p {
        margin: 0 0 14px;
        color: #a7b0c0;
        font-size: 13px
    }
    </style>
</head>

<body>
    <div class="card">
        <h1>CodeVault</h1>
        <p>Entre para acessar seus snippets.</p>

        <?php if ($err = session('error')): ?>
        <p class="msg"><?php echo esc($err) ?></p>
        <?php endif; ?>

        <form method="post" action="<?php echo site_url('login') ?>">
            <?php echo csrf_field() ?>
            <label>Email</label>
            <input class="input" name="email" type="text" autocomplete="username" required>

            <label>Senha</label>
            <input class="input" name="password" type="password" autocomplete="current-password" required>

            <button class="btn" type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>