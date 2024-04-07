<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// Not logged
if (!$UserUID) {
    header("Location:$BackUrl");
    return;
}

$new = $_POST['new'];

if ($new == 1) {
    $category = htmlentities($_POST['opzione']);
    $title = htmlentities($_POST['oggetto']);
    $mess = $_POST['editor1'];
    
    if ($mess != ''){
		$result = odbc_exec($odbcConn, "SELECT DATEDIFF(SECOND, Date, CURRENT_TIMESTAMP) AS [Seconds] FROM PS_Website.dbo.Users_Ticket WHERE UserUID=$UserUID ORDER BY Date DESC");
		if (odbc_num_rows($result)) {
			$seconds = odbc_result($result, "Seconds");
			if ($seconds < 60) {
				header("location: /?p=support&tickets");
				return;
			}
		}		
		
		$query1 = $conn->prepare('
		DECLARE @ticketID INT
		SELECT TOP 1 @ticketID = TicketID FROM PS_Website.dbo.Users_Ticket ORDER BY TicketID DESC
		IF @ticketID IS NOT NULL
			BEGIN
				SET @ticketID = @ticketID + 1
			END
		ELSE
			BEGIN
				SET @ticketID = 1
			END
		INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, Category, Title, Editor, Status, New, Date) VALUES (@ticketID, ?, ?, ?, ?, 1, 0, GETDATE())');
		$query1->bindValue(1, $UserUID, PDO::PARAM_INT);
		$query1->bindValue(2, $category, PDO::PARAM_INT);
		$query1->bindValue(3, $title, PDO::PARAM_STR);
		$query1->bindValue(4, $mess, PDO::PARAM_STR);
		$query1->execute();
    }
	
	// Go to ticket list
	header("location: /?p=support&tickets");
} else {
	$result = odbc_exec($odbcConn, "SELECT DATEDIFF(SECOND, Date, CURRENT_TIMESTAMP) AS [Seconds] FROM PS_Website.dbo.Users_Ticket WHERE UserUID=$UserUID ORDER BY Date DESC");
	if (odbc_num_rows($result)) {
		$seconds = odbc_result($result, "Seconds");
		if ($seconds < 60) {
			header("location: /?p=support&tickets");
			return;
		}
	}
		
    $ticketid = $_POST['TicketID'];
    $mess = $_POST['editor1'];

    if ($mess != '') {
		$query1 = $conn->prepare('
		DECLARE @ticketID INT
		DECLARE @category INT
		DECLARE @title varchar (20)
		
		SELECT TOP 1 @ticketID = TicketID, @category = Category, @title = Title FROM PS_Website.dbo.Users_Ticket WHERE TicketID = ? AND Status = 1
		UPDATE PS_Website.dbo.Users_Ticket SET Status = 0 WHERE TicketID = @ticketID
		INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, Category, Title, Editor, Status, New, Date) VALUES (@ticketID, ?, @category, @title, ?, 1, 0, GETDATE())');
		$query1->bindValue(1, $ticketid, PDO::PARAM_INT);
		$query1->bindValue(2, $UserUID, PDO::PARAM_INT);
		$query1->bindValue(3, $mess, PDO::PARAM_STR);
		$query1->execute();
	}

	// Go to ticket 
	header("location: /?p=support&tickets=$ticketid");
}
