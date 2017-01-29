<!-- MENU -->
    <nav id="navigation" class="clearfix">
        <ul>
            <?php foreach($array_enlaces as $nombre => $enlace):?>
                <li><a href="<?= $enlace; ?>"><?= $nombre; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>