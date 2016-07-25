<ul class="nav">
	
	<li class="dropdown <?php echo checkPage('index'); ?>">
		<a href="index.php">Home</a>
	</li>
	
	<li class="dropdown <?php echo checkPage('loans,addLoan,debtor'); ?>">
		<a href="loans.php">Loans <b class="caret"></b></a>
	   	<ul class="dropdown-menu">
	    	<li><a data-toggle="modal" href="forms/loan.php" data-target="#newLoan">Add Loan</a></li>
	    </ul>
	</li>
	
	<li class="dropdown <?php echo checkPage('payments,createBatchPayment'); ?>">
		<a href="payments.php">Payments <b class="caret"></b></a>
	   	<ul class="dropdown-menu">	
			<li><a data-toggle="modal" href="forms/payment.php" data-target="#newPayment">Add Payment</a></li>
	    	<li><a href="createBatchPayment.php">Create Batch Payment</a></li>
	    </ul>
	</li>
	
	<li class="dropdown <?php echo checkPage('clients,sortClients,addClient'); ?>">
		<a href="clients.php">Clients <b class="caret"></b></a>
	   	<ul class="dropdown-menu">
			<li><a href="addClient.php">Add Client</a></li>
	    	<li><a href="sortClients.php">Sort Clients</a></li>
	    </ul>
	</li>	

<li class="dropdown <?php echo checkPage('reports'); ?>">
		<a href="reports.php">Reports</a>
	</li>	
</ul>