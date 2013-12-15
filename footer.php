		</div>
        </div>
		</div>
		</div>

	  </body>
	  
<!-- footer -->
	<?php
		$sql = $bdd->prepare('SELECT DISTINCT version FROM changelog ORDER BY datelog DESC LIMIT 0, 1');
		$sql->execute();
		
	
		while($stid = $sql->fetch()) {
			
			echo '<center><a href="changelog.php">PrivateShare - '.$stid['version'].'</a></center></br>';
		}
		
	?>
</html>