        <!-- include footer -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>OES</b>
            </div>
            <strong>Copyright &copy; 2018-2024 <a href="https://google.com">Online Examination System</a>.</strong>
        </footer>
    </div>
    
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- DataTables -->
	<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTable Buttons -->
	<script src="../dist/js/dataTables.buttons.min.js" defer></script>
	<script src="../dist/js/buttons.flash.min.js" defer></script>
	<script src="../dist/js/buttons.html5.min.js" defer></script>
	<script src="../dist/js/vfs_fonts.js" defer></script>
	<script src="../dist/js/buttons.print.min.js" defer></script>
	<script src="../dist/js/jszip.min.js" defer></script>	
	<!-- Select2 -->
	<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
	<!-- FastClick -->
	<script src="../bower_components/fastclick/lib/fastclick.js"></script>
	<!-- datepicker -->
	<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
    <!-- page specific js -->
	<?php if(defined('CUSTOM_JS')) : ?>
	<script src="<?= CUSTOM_JS; ?>"></script>
	<?php endif; ?>
</body>
</html>