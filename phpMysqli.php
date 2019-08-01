<?php require_once("includes/layouts/session.php"); ?>
<?php require("includes/layouts/db_connect.php"); ?>
<?php require_once("includes/layouts/functions.php") ?>
<?php confirm_staff_logged_in(); ?>
<?php 
    if (isset($_GET['cmtproapp'])) {
        $trackcode = $_GET['cmtproapp'];

        $query = "SELECT * from commentsprod WHERE cmtid = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "1";


        $subIte = "UPDATE commentsprod SET cmtdisplay='{$status}', cmtdate=now() WHERE cmtid='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Comment status Updated.';

      echo "<script>window.open('comments.php', '_self')</script>";

    }

    if (isset($_GET['cmtprosus'])) {
        $trackcode = $_GET['cmtprosus'];

        $query = "SELECT * from commentsprod WHERE cmtid = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "0";


        $subIte = "UPDATE commentsprod SET cmtdisplay='{$status}', cmtdate=now() WHERE cmtid='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Comment status Updated.';

      echo "<script>window.open('comments.php', '_self')</script>";

    }

    if (isset($_GET['cmtapp'])) {
        $trackcode = $_GET['cmtapp'];

        $query = "SELECT * from comments WHERE cmtid = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "1";


        $subIte = "UPDATE comments SET cmtdisplay='{$status}', cmtdate=now() WHERE cmtid='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Blog Comment status Updated.';

      echo "<script>window.open('comments.php', '_self')</script>";

    }

    if (isset($_GET['cmtsus'])) {
        $trackcode = $_GET['cmtsus'];

        $query = "SELECT * from comments WHERE cmtid = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "0";


        $subIte = "UPDATE comments SET cmtdisplay='{$status}', cmtdate=now() WHERE cmtid='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Blog Comment status Updated.';

      echo "<script>window.open('comments.php', '_self')</script>";

    }

    if (isset($_GET['tesapp'])) {
        $trackcode = $_GET['tesapp'];

        $query = "SELECT * from testimonials WHERE tesID = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "1";


        $subIte = "UPDATE testimonials SET tesDisplay='{$status}', tesDate=now() WHERE tesID='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Testimonial\'s status Updated.';

      echo "<script>window.open('faqtes.php', '_self')</script>";

    }

    if (isset($_GET['tessus'])) {
        $trackcode = $_GET['tessus'];

        $query = "SELECT * from testimonials WHERE tesID = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "0";


        $subIte = "UPDATE testimonials SET tesDisplay='{$status}', tesDate=now() WHERE tesID='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Testimonial\'s status Updated.';

      echo "<script>window.open('faqtes.php', '_self')</script>";

    }

    if (isset($_GET['faqapp'])) {
        $trackcode = $_GET['faqapp'];

        $query = "SELECT * from faq WHERE faqId = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "1";


        $subIte = "UPDATE faq SET faqD='{$status}' WHERE faqId='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Faq\'s status Updated.';

      echo "<script>window.open('faqtes.php', '_self')</script>";

    }

    if (isset($_GET['faqsus'])) {
        $trackcode = $_GET['faqsus'];

        $query = "SELECT * from faq WHERE faqId = '{$trackcode}' Limit 1";
        $run_query = mysqli_query($connect, $query);
        confirm_query($run_query);
        $row = mysqli_fetch_assoc($run_query);

        $status = "0";


        $subIte = "UPDATE faq SET faqD='{$status}' WHERE faqId='{$trackcode}' LIMIT 1 ";

        $run_subIte = mysqli_query($connect, $subIte);

        confirm_query($run_subIte);

    
        $_SESSION['message'] = 'Faq\'s status Updated.';

      echo "<script>window.open('faqtes.php', '_self')</script>";

    }

   ?>
