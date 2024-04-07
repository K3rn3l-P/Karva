<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
// Not staff
if (!$IsStaff) {
    header("Location:$BackUrl");
    return;
}

if (isset($_POST['reopen']) && $_POST['reopen'] == 'yes') {
    $ticketid = $_POST['TicketID'];
    $query1 = $conn->prepare('UPDATE PS_Website.dbo.Users_Ticket SET Status = 1 WHERE TicketID = ? AND Status = 2');
    $query1->bindValue(1, $ticketid, PDO::PARAM_INT);
    $query1->execute();
    
} else {
    $ticketid = $_POST['TicketID'];
    $mess = $_POST['editor1'];

    if ($mess != ''){			
		$query1 = $conn->prepare('
		DECLARE @UserUID INT
		DECLARE @ticketID INT
		DECLARE @category INT
		DECLARE @title varchar (20)
		
		SELECT TOP 1 @ticketID = TicketID, @UserUID = UserUID, @category = Category, @title = Title FROM PS_Website.dbo.Users_Ticket WHERE TicketID = ? AND Status = 1
		UPDATE PS_Website.dbo.Users_Ticket SET Status = 0 WHERE TicketID = @ticketID
		INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, StaffUID, Category, Title, Editor, Status, New, Date) VALUES (@ticketID, @UserUID, ?, @category, @title, ?, ?, 1, GETDATE())');
		$query1->bindValue(1, $ticketid, PDO::PARAM_INT);
		$query1->bindValue(2, $UserUID, PDO::PARAM_INT);
		$query1->bindValue(3, $mess, PDO::PARAM_STR);
		$query1->bindValue(4, $_POST['ticketStatus'], PDO::PARAM_STR);
		$query1->execute();
	}
}

header("location: /?p=support&panel=$ticketid");