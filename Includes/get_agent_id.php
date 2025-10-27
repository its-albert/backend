       <?php
        
		
        try {
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name",
                            $db_username, $db_password);
            // execute the stored procedure
            $sql = 'CALL give_agent_id()';
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $pe) {
            die("Error occurred:" . $pe->getMessage());
        }
        ?>
        
        <?php while ($r = $q->fetch()):
		
		$agent_id =  $r['Agent_Id_'];
		 ?>
                
                   
                    
                
        <?php endwhile; ?>
      

