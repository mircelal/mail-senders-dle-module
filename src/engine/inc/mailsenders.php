<?php 
/*
=====================================================
 Mail-Senders - ehmedP
-----------------------------------------------------
 http://okmedia.az/
-----------------------------------------------------
 Copyright (c) 2024 Ehmedli Ehmed
=====================================================
 File: /engine/inc/mailsenders.php
=====================================================
*/

if (!defined('DATALIFEENGINE') or !defined('LOGGED_IN')) {
	header("HTTP/1.1 403 Forbidden");
	header('Location: ../../');
	die("Hacking attempt!");
}

global $db, $config, $dle_login_hash, $_TIME;
$headingTitle = "Mail Senders";

$version = [
	'name'      => 'Mail-Senders',
	'descr'     => 'List of users who sent emails from the site',
	'version'   => '0.0.1',
	'changelog' => [],
	'id'        => 'mailsenders',
];

function showTable($data) : string {
    $rows = showRow($data);
    
return <<<HTML
    <table class="mail-sender-table table table-xs table-hover" id="mail-sender-table">
        <thead>
            <tr>
                <th> # </th>
                <th> Name </th>
                <th> Surname </th>
                <th> Telephone </th>
                <th> Email </th>
                <th> Date </th>
                <th> <input name="master_box" onclick="javascript:ckeck_uncheck_all(this)" title="Select All" type="checkbox" class="icheck"> </th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>  
    
    <script type="text/javascript">
    
        function ckeck_uncheck_all(el) {
    	    const checkboxs = document.querySelectorAll(".check-element");
            
            [...checkboxs].forEach(checkbox => {
                checkbox.checked = el.checked;
                el.checked ? checkbox.parentElement.classList.add("checked") : checkbox.parentElement.classList.remove("checked");
            });
    	}
    	
	</script>
HTML;

}

function showRow($rows) : string {
    $rowContent = "";
    
    foreach($rows as $row) {
        $rowContent .= 
<<<HTML
    <tr>
        <td class="col-sm-1">
            <h6 class="media-heading text-semibold"> {$row['id']} </h6>
        </td>
        <td class="col-sm-2"> {$row['name']} </td>
        <td class="col-sm-3"> {$row['surname']} </td>
        <td class="col-sm-2"> <a href="tel:{$row['phone']}">{$row['phone']}</a> </td>
        <td class="col-sm-3"> <a href="mailto:{$row['mail']}">{$row['mail']}</a> </td>
        <td class="col-sm-2"> {$row['current_time_stamp']} </td>
        <td><input name="selected_keys[]" value="{$row['id']}" type="checkbox" class="icheck check-element"></td>
    </tr>
HTML;

    }

    return $rowContent;

}

function showContent(): void {
    global $headingTitle, $db, $version;
    
    $start_from   = (int) $_REQUEST['start_from'];
	if ($start_from < 0) $start_from = 0;
    
    $users_per_page = 10;

    $row = $db->super_query("SELECT * FROM `" . PREFIX . "_mail_senders` ms ORDER BY `current_time_stamp` DESC LIMIT $start_from, $users_per_page", 1);
    
    $tableContent = showTable($row);
    
    $all_count_user = $db->super_query("SELECT COUNT(*) as count FROM `" . PREFIX . "_mail_senders` ms");
    $all_count_user = $all_count_user['count'];  
    
    // Pagination
    $npp_nav = "";

    if ($all_count_user > $users_per_page) {

        $previousPage = max(0, $start_from - $users_per_page);
        $totalPages = ceil($all_count_user / $users_per_page);
        $currentPage = ceil($start_from / $users_per_page) + 1;

        if ($start_from > 0) {
            $npp_nav .= "<li><a onclick=\"javascript:search_submit($previousPage); return(false);\" href=\"#\" title=\"Previous\">&lt;&lt;</a></li>";
        }

        $startPage = max(1, $currentPage - 4);
        $endPage = min($totalPages, $currentPage + 5);

        if ($startPage > 2) {
            $npp_nav .= "<li><a onclick=\"javascript:search_submit(0); return(false);\" href=\"#\">1</a></li><li><span>...</span></li>";
        }

        for ($j = $startPage; $j <= $endPage; $j++) {
            $pageStart = ($j - 1) * $users_per_page;
            if ($pageStart != $start_from) {
                $npp_nav .= "<li><a onclick=\"javascript:search_submit($pageStart); return(false);\" href=\"#\">$j</a></li>";
            } else {
                $npp_nav .= "<li class=\"active\"><span>$j</span></li>";
            }
        }

        if ($endPage < $totalPages - 1) {
            $lastPageStart = ($totalPages - 1) * $users_per_page;
            $npp_nav .= "<li><span>...</span></li><li><a onclick=\"javascript:search_submit($lastPageStart); return(false);\" href=\"#\">$totalPages</a></li>";
        }

        if ($start_from + $users_per_page < $all_count_user) {
            $nextPage = $start_from + $users_per_page;
            $npp_nav .= "<li><a onclick=\"javascript:search_submit($nextPage); return(false);\" href=\"#\" title=\"Next\">&gt;&gt;</a></li>";
        }

        $npp_nav = "<ul class=\"pagination pagination-sm\">" . $npp_nav . "</ul>";
    }

    echo <<<HTML
    <div class="panel panel-default">
        <div class="panel-heading">
            {$headingTitle}
        </div>
        <div class="table-responsive table_holder">
            {$tableContent}
        </div>
        <div class="panel-footer">
            <div class="pull-right"> 
                <a href="javascript:void(0)" class="btn bg-brown-600 btn-sm btn-raised position-left btn-save" id="copy-emails-btn" role="button"> Copy Emails </a>
                <a href="javascript:void(0)" class="btn bg-blue-600 btn-sm btn-raised position-left btn-save" id="copy-phones-btn" role="button"> Copy Phones </a>
            </div>
        </div>
    </div>
    
    {$npp_nav}
    
    <form action="?mod={$version['id']}" method="get" name="navi" id="navi">
        <input type="hidden" name="mod" value="{$version['id']}">
        <input type="hidden" name="start_from" id="start_from" value="{$start_from}">
    </form>
    
    <script type="text/javascript">
        function search_submit(prm){
            document.navi.start_from.value=prm;
            document.navi.submit();
            
            return false;
        }
        
        document.getElementById('copy-emails-btn').addEventListener('click', function() {
            const selectedEmails = [];
            const checkboxes = document.querySelectorAll(".check-element:checked");

            checkboxes.forEach(checkbox => {
                const emailCell = checkbox.closest('tr').querySelector('td:nth-child(5) a');
                if (emailCell) {
                    selectedEmails.push(emailCell.textContent.trim());
                }
            });

            if (selectedEmails.length > 0) {
                const emailString = selectedEmails.join(', ');
                copyToClipboard(emailString);
            } else {
                alert("No emails selected.");
            }
        });

        document.getElementById('copy-phones-btn').addEventListener('click', function() {
            const selectedPhones = [];
            const checkboxes = document.querySelectorAll(".check-element:checked");

            checkboxes.forEach(checkbox => {
                const phoneCell = checkbox.closest('tr').querySelector('td:nth-child(4) a');
                if (phoneCell) {
                    selectedPhones.push(phoneCell.textContent.trim());
                }
            });

            if (selectedPhones.length > 0) {
                const phoneString = selectedPhones.join(', ');
                copyToClipboard(phoneString);
            } else {
                alert("No phones selected.");
            }
        });

        function copyToClipboard(text) {
            const textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
        }
    </script>
HTML;
}

echoheader("<i class=\"fa fa-id-card-o position-left\"></i><span class=\"text-semibold\">{$version['name']} (v{$version['version']})</span>", $version['name']);

showContent();

echofooter();

?>
