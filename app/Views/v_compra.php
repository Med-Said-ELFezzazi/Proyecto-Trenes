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
            <a href="<?= site_url('home'); ?>">Volver a la página Home</a>
        </div>
    </div>
</div>