<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autorizzato come staff
if (!$IsStaff) {
    header("Location: $BackUrl");
    return;
}

try {
    if (isset($_POST['reopen']) && $_POST['reopen'] == 'yes') {
        // Reapri il ticket se richiesto
        $ticketID = filter_input(INPUT_POST, 'TicketID', FILTER_VALIDATE_INT);
        if (!$ticketID) {
            throw new Exception("Invalid ticket ID");
        }
        
        $query = $conn->prepare('UPDATE PS_Website.dbo.Users_Ticket SET Status = 1 WHERE TicketID = :ticketID AND Status = 2');
        $query->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
        $query->execute();
    } else {
        // Rispondi al ticket
        $ticketID = filter_input(INPUT_POST, 'TicketID', FILTER_VALIDATE_INT);
        $message = $_POST['editor1'];

        if ($message !== '') {
            // Esegui l'aggiornamento del ticket e l'inserimento della risposta
            $query = $conn->prepare('
                DECLARE @UserUID INT
                DECLARE @category INT
                DECLARE @title VARCHAR(20)
                
                SELECT TOP 1 @UserUID = UserUID, @category = Category, @title = Title FROM PS_Website.dbo.Users_Ticket WHERE TicketID = :ticketID AND Status = 1
                UPDATE PS_Website.dbo.Users_Ticket SET Status = 0 WHERE TicketID = :ticketID
                INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, StaffUID, Category, Title, Editor, Status, New, Date)
                VALUES (:ticketID, @UserUID, :userUID, @category, @title, :message, :ticketStatus, 1, GETDATE())');
            $query->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
            $query->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            $query->bindParam(':ticketStatus', $_POST['ticketStatus'], PDO::PARAM_STR);
            $query->execute();
        }
    }

    header("Location: /?p=support&panel=$ticketID");
} catch (Exception $e) {
    // Gestione delle eccezioni
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
}
?>
