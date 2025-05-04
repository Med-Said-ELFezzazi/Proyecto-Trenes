<style>
     .ticket-footer {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .ticket-footer a {
            display: inline-block;
            padding: 8px 20px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .ticket-footer a:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
</style>
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <?php if ($compraOk && $emailOk): ?>
                <h2 style="color: green;">Compra realizada correctamente</h2>
                <img src="<?php echo base_url('/images/compraOk.png') ?>" style="display: block; margin: 0 auto; width: 50%; max-width: 200px;">
                <b><i style="display: block; margin-top: 10px;">Recibirás un correo con los detalles de su compra, ¡Buen viaje!</i></b>
            <?php else: ?>
                <h2 style="color: red;">Error al realizar la compra!</h2>
            <?php endif; ?>

            <br><br>
            <div class="ticket-footer">
                <a btn-back href="<?= site_url('home'); ?>">Volver a la página Home</a>
            </div>
        </div>
    </div>
</div>