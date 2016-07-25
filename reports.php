<?php
   	include('config.php');
    
    //Compute the receivables
    $receivables = 0;
	$clients = $db->selectObjects("clients", "active = 1");
    foreach($clients as $item) {
     
        $receivables += getClientBalance($item->id);
    }
 
	//to make pagination
    
    $loans = $db->selectObjectBySql("SELECT sum(capital) total FROM loans where active = 1");
    $total_loan = $loans->total;
    $payments = $db->selectObjectBySql("SELECT sum(capital+interest) total FROM payments where active = 1");
    $total_collection = $payments->total;
    
    $starting_capital = 105000;
    
    $current_money = ( $starting_capital + $total_collection ) - $total_loan;

	$data = array();
	
		
		//for the month of december 2015
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2015-12-01 00:00:00' AND '2015-12-31 00:00:00'");
  
		$data['december_2015_income'] = $interest->totalInterest;
        $data['december_2015_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2015-12-01 00:00:00' AND '2015-12-31 00:00:00'");
        
        $data['december_2015_collection'] = $collection->totalCollection;
        
		//for the month of january 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-01-01 00:00:00' AND '2016-01-31 00:00:00'");
  
		$data['january_2016_income'] = $interest->totalInterest;
        $data['january_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-01-01 00:00:00' AND '2016-01-31 00:00:00'");
        
        $data['january_2016_collection'] = $collection->totalCollection;
        
		//for the month of February 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-02-01 00:00:00' AND '2016-02-29 00:00:00'");
  
		$data['february_2016_income'] = $interest->totalInterest;
        $data['february_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-02-01 00:00:00' AND '2016-02-29 00:00:00'");
        
        $data['february_2016_collection'] = $collection->totalCollection;
        
        //for the month of March
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-03-01 00:00:00' AND '2016-03-31 00:00:00'");
  
		$data['march_2016_income'] = $interest->totalInterest;
        $data['march_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-03-01 00:00:00' AND '2016-03-31 00:00:00'");
        
        $data['march_2016_collection'] = $collection->totalCollection;
        
        
        //for the month of April 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-04-01 00:00:00' AND '2016-04-30 00:00:00'");
  
		$data['april_2016_income'] = $interest->totalInterest;
        $data['april_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-04-01 00:00:00' AND '2016-04-30 00:00:00'");
        
        $data['april_2016_collection'] = $collection->totalCollection;
        
        //for the month of May 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-05-01 00:00:00' AND '2016-05-31 00:00:00'");
  
		$data['may_2016_income'] = $interest->totalInterest;
        $data['may_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-05-01 00:00:00' AND '2016-05-31 00:00:00'");
        
        $data['may_2016_collection'] = $collection->totalCollection;
        
        
        //for the month of June 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-06-01 00:00:00' AND '2016-06-30 00:00:00'");
  
		$data['june_2016_income'] = $interest->totalInterest;
        $data['june_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-06-01 00:00:00' AND '2016-06-30 00:00:00'");
        
        $data['june_2016_collection'] = $collection->totalCollection;
        
        //for the month of July 2016
		$interest = $db->selectObjectBySql("SELECT sum(capital*interest * monthsToPay) totalInterest, sum(capital) totalLoan FROM loans where active = 1 AND loanDate BETWEEN '2016-07-01 00:00:00' AND '2016-07-31 00:00:00'");
  
		$data['july_2016_income'] = $interest->totalInterest;
        $data['july_2016_loan'] = $interest->totalLoan;
        
        
        $collection = $db->selectObjectBySql("SELECT sum(capital+interest) totalCollection FROM payments where active = 1 AND paymentDate BETWEEN '2016-07-01 00:00:00' AND '2016-07-31 00:00:00'");
        
        $data['july_2016_collection'] = $collection->totalCollection;
	
	
	
	//Set the pageheader and include the header
	$pageheader = "Report";
	include('header.php');
?>

	<div class="row-fluid">
	
			
		<div class="span12">
				<table class="table table-hover client span12">
					<thead>
						<tr>
							<th>Month</th>
                            <th>Total Loan</th>
                            <th>Total Collection</th>
							<th>Total Income</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>December 2015</td>
                            <td>P<?php echo number_format($data['december_2015_loan'],2); ?></td>	
                            <td>P<?php echo number_format($data['december_2015_collection'],2); ?></td>	
							<td>P<?php echo number_format($data['december_2015_income'],2); ?></td>		
						</tr>
                        
						<tr>
							<td>January 2016</td>
							<td>P<?php echo number_format($data['january_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['january_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['january_2016_income'],2); ?></td>		
						</tr>
						<tr>
                            
							<td>February 2016</td>
							<td>P<?php echo number_format($data['february_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['february_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['february_2016_income'],2); ?></td>		
						</tr>
                        
						<tr>
                            
							<td>March 2016</td>
							<td>P<?php echo number_format($data['march_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['march_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['march_2016_income'],2); ?></td>		
						</tr>
                        
                        
						<tr>
                            
							<td>April 2016</td>
							<td>P<?php echo number_format($data['april_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['april_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['april_2016_income'],2); ?></td>		
						</tr>
                        
						<tr>
                            
							<td>May 2016</td>
							<td>P<?php echo number_format($data['may_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['may_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['may_2016_income'],2); ?></td>		
						</tr>
                        
						<tr>
                            
							<td>June 2016</td>
							<td>P<?php echo number_format($data['june_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['june_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['june_2016_income'],2); ?></td>		
						</tr>
                        
						<tr>
                            
							<td>July 2016</td>
							<td>P<?php echo number_format($data['july_2016_loan'],2); ?></td>
                            <td>P<?php echo number_format($data['july_2016_collection'],2); ?></td>
                            <td>P<?php echo number_format($data['july_2016_income'],2); ?></td>		
						</tr>
						
					</tbody>

                    <tfoot>
                        <tr>
                            <th colspan=4>Total Money: P<?php echo number_format($current_money, 2); ?></th>
                        </tr>
                        <tr>
                            <th colspan=4>Net Worth: P<?php echo number_format(($receivables + $current_money), 2); ?></th>
                        </tr>
                    </tfoot>
			</table>
		</div>
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>