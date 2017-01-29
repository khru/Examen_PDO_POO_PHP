		</div><!-- /div.warp -->
    	<footer class="pie">
        <?php
        if ($array_enlaces) {
            		 foreach ($array_enlaces as $nombre => $enlace) {
	                   echo '<a href="' . $enlace . '">' . $nombre . '</a>';
	                }
            	} ?>
            <p class="autor">Emanuel Valverde Ramos</p>
		</footer><!-- /footer -->

    </body>
</html>