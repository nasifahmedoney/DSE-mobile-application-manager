<?php
include 'connect.php';
//
session_start();

$user_email = '';
$userGroup = '';
if(!isset($_SESSION['email']))
{
	header("Location:logout_end_session.php");
}
else if($_SESSION['userGroup'] != "Settlement")
{
	header("Location:logout_end_session.php");
}
else
{
	$user_email = $_SESSION['email'];
	$userGroup = $_SESSION['userGroup'];
}
//
function before ($this_, $inthat)
{
    return substr($inthat, 0, strpos($inthat, $this_));
};

//search results on both databases
$query_result1 = '';
$query_result2 = '';

//display on table
$inv_code = '';
$status = '';
$initiated_by = '';
$app_dir = '';
$purchase_power = '';
$open_date = '';
$client_email = '';

if(isset($_POST['investor_code']))
{
	if($user_email != '')
	{
		$investor_code_userentry = $_POST['investor_code'];
		$investor_code = strtoupper($investor_code_userentry);
		$query1 = "SELECT * FROM documents WHERE investor_code = '$investor_code'";
		$result1 = mysqli_query($link,$query1);
		if($result1)
		{
			$row = $result1->fetch_array(MYSQLI_ASSOC);
			if(is_array($row))
			{
				$query_result1 = $row;
			}
			
		}


		$query2 = "SELECT * FROM old_documents WHERE investor_code = '$investor_code'";
		$result2 = mysqli_query($link,$query2);

		if($result2)
		{
			$row = $result2->fetch_array(MYSQLI_ASSOC);
			if(is_array($row))
			{
				$query_result2 = $row;
			}
			
		}


		//else if
		if(is_array($query_result1))
		{
		  $inv_code = $query_result1['investor_code'];
		  $status = $query_result1['status'];
		  $initiated_by = $query_result1['email'];
		  $app_dir = $query_result1['app_dir'];
		  $purchase_power = $query_result1['purchase_power'];
		  $open_date = $query_result1['open_date'];
		  $client_email = $query_result1['client_email'];
		  //assignment
		  
		}
		else if(is_array($query_result2))//runs on old table
		{
		  $inv_code = $query_result2['investor_code'];
		  $status = $query_result2['status'];
		  $initiated_by = "Not Available";
		  $app_dir = "Not Available";
		  $purchase_power = $query_result2['purchase_power'];
		  $open_date = "Not Available";
		  if($query_result2['client_email'] == null)
		  {
			  $client_email = "Not Available";
		  }
		  else
		  {
			  $client_email = $query_result2['client_email'];
		  }
		}
		else
		{
				?>
					<script type="text/javascript">
						window.alert("Investor Code Not found.");
				//document.forms["investor_code_search"]["investor_code"].value = '';
						</script>
					<?php
		}
	}
	else
	{
		header("Location:logout_end_session.php");
	}
}

?>

<html>
<head>
<title></title>

<script>
    function validateForm() {
      var x = document.forms["investor_code_search"]["investor_code"].value;
      if (x.includes(" ") || x.includes("-") || x.includes("_") || x.includes("/") || x.includes("\\") || x.includes(".")) {
        alert("Space, - , _ not allowed in Investor Code");
        document.forms["investor_code_search"]["investor_code"].value = '';
        return false;
      }
    }

</script>
<link rel="stylesheet" href="css/center.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="userGroup_Settlement.php" ><?php echo before('@',$user_email); ?></a>
    
    <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
	  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Options
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="userGroup_Settlement_Pending.php">Pending Codes</a><!--done-->
          <a class="dropdown-item" href="userGroup_Settlement_Approved.php">Approved Codes</a><!--done-->
          <a class="dropdown-item" href="userGroup_Settlement_Reupload.php">Reupload Requested</a><!--done-->
		  <a class="dropdown-item" href="#">Search Code</a>
		  <a class="dropdown-item" href="userGroup_Settlement_ChangePassword.php">Change Password</a>
        </div>
      </li>
	  
	  <li class="nav-item">
        <a class="nav-link" href="#"><b>Search Code</b></a>
      </li>
	  
    </ul>
    </div>
    <span class="nav navbar-nav navbar-right">
	<a href='logout_end_session.php'><button type="button" class="btn btn-light">Logout</button></a>
    <span>
</nav>

<br>

<form class="form-inline" name = "investor_code_search" id = "investor_code_search" action = "userGroup_Settlement_SearchCode.php" method = "POST">
  <div class="form-group mx-sm-3 mb-2">
    <input type="text" class="form-control" name = "investor_code" id="investor_code" placeholder="Enter investor code" onchange="return validateForm()" required>
  </div>
  <button type="submit" class="btn btn-primary mb-2">Search</button>
</form>


<center>
<table class="table">
<tbody>
    <tr>
      <th scope="row">Investor Code: </th>
      <td><?php echo $inv_code; ?></td>
    </tr>
    <tr>
      <th scope="row">Status: </th>
      <td><?php echo $status; ?></td>
    </tr>
    <tr>
      <th scope="row">Initiated by: </th>
      <td><?php echo $initiated_by; ?></td>
    </tr>
    <tr>
      <th scope="row">Purchase Power: </th>
      <td><?php echo $purchase_power; ?></td>
    </tr>
	<tr>
      <th scope="row">Open Date: </th>
      <td><?php echo $open_date; ?></td>
    </tr>
	<tr>
      <th scope="row">Open Date: </th>
      <td><?php echo $client_email; ?></td>
    </tr>
    <tr>
      <th scope="row">View Application: </th>
      <td><a href="user_openpdf.php?app_dir=<?php echo $app_dir; ?>" target = "_blank"><?php echo $app_dir; ?></a></td>
    </tr>
    </tbody>
</table>
</center>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>


</html>

