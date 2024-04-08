<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

// Verifica se l'utente è autenticato
if (!$UserUID) {
    header("Location: $BackUrl");
    return;
}

try {
    if ($_POST['new'] == 1) {
        // Nuovo ticket
        $category = htmlentities($_POST['opzione']);
        $title = htmlentities($_POST['oggetto']);
        $message = $_POST['editor1'];

        if ($message !== '') {
            // Verifica se è passato almeno 1 minuto dall'ultimo ticket inviato
            $query = $conn->prepare('SELECT DATEDIFF(SECOND, Date, CURRENT_TIMESTAMP) AS [Seconds] FROM PS_Website.dbo.Users_Ticket WHERE UserUID = :userUID ORDER BY Date DESC');
            $query->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
            $query->execute();
            $seconds = $query->fetchColumn();
            if ($seconds < 60) {
                header("Location: /?p=support&tickets");
                return;
            }

            // Inserisci il nuovo ticket nel database
            $query = $conn->prepare('
                DECLARE @ticketID INT
                SELECT TOP 1 @ticketID = TicketID FROM PS_Website.dbo.Users_Ticket ORDER BY TicketID DESC
                IF @ticketID IS NOT NULL
                    SET @ticketID = @ticketID + 1
                ELSE
                    SET @ticketID = 1
                INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, Category, Title, Editor, Status, New, Date)
                VALUES (@ticketID, :userUID, :category, :title, :message, 1, 0, GETDATE())');
            $query->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
            $query->bindParam(':category', $category, PDO::PARAM_INT);
            $query->bindParam(':title', $title, PDO::PARAM_STR);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            $query->execute();
        }

        // Reindirizza alla lista dei ticket
        header("Location: /?p=support&tickets");
    } else {
        // Rispondi a un ticket esistente
        $ticketID = $_POST['TicketID'];
        $message = $_POST['editor1'];

        if ($message !== '') {
            // Verifica se è passato almeno 1 minuto dall'ultimo ticket inviato
            $query = $conn->prepare('SELECT DATEDIFF(SECOND, Date, CURRENT_TIMESTAMP) AS [Seconds] FROM PS_Website.dbo.Users_Ticket WHERE UserUID = :userUID ORDER BY Date DESC');
            $query->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
            $query->execute();
            $seconds = $query->fetchColumn();
            if ($seconds < 60) {
                header("Location: /?p=support&tickets");
                return;
            }

            // Aggiorna il ticket esistente e inserisci la risposta
            $query = $conn->prepare('
                DECLARE @category INT
                DECLARE @title VARCHAR(20)
                SELECT TOP 1 @category = Category, @title = Title FROM PS_Website.dbo.Users_Ticket WHERE TicketID = :ticketID AND Status = 1
                UPDATE PS_Website.dbo.Users_Ticket SET Status = 0 WHERE TicketID = :ticketID
                INSERT INTO PS_Website.dbo.Users_Ticket (TicketID, UserUID, Category, Title, Editor, Status, New, Date)
                VALUES (:ticketID, :userUID, @category, @title, :message, 1, 0, GETDATE())');
            $query->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
            $query->bindParam(':userUID', $UserUID, PDO::PARAM_INT);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            $query->execute();
        }

        // Reindirizza al ticket
        header("Location: /?p=support&tickets=$ticketID");
    }
} catch (Exception $e) {
    // Gestione delle eccezioni
    SetErrorAlert($e->getMessage());
    header("Location: $BackUrl");
}
?>
