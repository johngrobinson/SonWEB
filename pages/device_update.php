<?php
	//	var_dump( $_POST );
	$localIP = $Config->read( "ota_server_ip" );
	
	$otaServer = "http://".$localIP.":".$_SERVER[ "SERVER_PORT" ]."/";
	
	$ota_minimal_firmware_url = $otaServer."data/firmwares/sonoff-minimal.bin";
	$ota_new_firmware_url     = $otaServer."data/firmwares/sonoff-full.bin";
	
	$device_ips = isset( $_POST[ "device_ips" ] ) ? $_POST[ "device_ips" ] : FALSE;
?>
<div class='center'>
	<?php if ( !$device_ips ): ?>
	<br/>
	<br/>
		<p class='warning'>
			<?php echo __( "NO_DEVICES_SELECTED", "DEVICE_UPDATE" ); ?>
		</p>
	
	<?php else: ?>
		<div id='progressbox'>
		
		</div>
	
	<input type='hidden' id='ota_minimal_firmware_url' value='<?php echo $ota_minimal_firmware_url; ?>'>
	<input type='hidden' id='ota_new_firmware_url' value='<?php echo $ota_new_firmware_url; ?>'>
		
		
		<script>
			var device_ips = '<?php echo json_encode( $device_ips ); ?>';
		
		</script>
		
		
		<script type='text/javascript' src='<?php echo _RESOURCESDIR_; ?>js/device_update.js'></script>
	<?php endif; ?>
</div>