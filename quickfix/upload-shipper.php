<?php
ini_set('max_execution_time', 30000);
include("../config/connection.php");
include("../config/functions.php");

require_once '../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$reader = ReaderFactory::create(Type::XLSX);
$reader->open('excel/upload-shipper-sample-for-uat.xlsx');

$headerColumns = array(
    'COMPANY NAME',       // 0
    'TIN',                // 1
    'BILLING IN CHARGE',  // 2
    'ACCOUNT EXECUTIVE',  // 3
    'LINE OF BUSINESS',   // 4
    'PAY MODE',           // 5
    'REGION/PROVINCE',    // 6
    'STREET',             // 7
    'COUNTRY'             // 8
);

$line = 1;
$headerformatflag = 1;
$now = date('Y-m-d H:i:s');

foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {

        if ($line == 1) {
            // Check header format
            echo "Checking Header format....<br>";
            for ($i = 0; $i < count($headerColumns); $i++) {
                echo strtoupper(trim($headerColumns[$i])) . "---->" . strtoupper(trim($row[$i])) . "<br>";
                if (strtoupper(trim($row[$i])) != strtoupper(trim($headerColumns[$i]))) {
                    $headerformatflag = 0;
                    echo "Invalid Header Format: " . strtoupper(trim($row[$i])) . " ----> " . strtoupper(trim($headerColumns[$i])) . "<br>";
                }
            }
        }

        if ($headerformatflag != 1) {
            echo "Invalid format";
            break;
        } else if ($line > 1) {

            // === MAP COLUMNS ===
            $companyname     = trim($row[0]) == '' ? null : strtoupper(trim($row[0]));
            $tin             = trim($row[1]) == '' ? null : trim($row[1]);
            $billingName     = trim($row[2]);
            $accountExecName = trim($row[3]);
            $lineofbusiness  = trim($row[4]) == '' ? null : strtoupper(trim($row[4]));
            $payModeCode     = trim($row[5]);
            $region          = trim($row[6]) == '' ? null : strtoupper(trim($row[6]));
            $street          = trim($row[7]) == '' ? null : strtoupper(trim($row[7]));
            $country         = trim($row[8]) == '' ? null : strtoupper(trim($row[8]));

            $accountname = $companyname;

            if (empty($companyname) || empty($accountname)) {
                $line++;
                continue;
            }

            // === LOOKUP: pay_mode_id ===
            $payModeId = null;
            $payModeEscaped = escapeString($payModeCode);
            $pmRs = query("SELECT id FROM pay_mode WHERE description LIKE '%$payModeEscaped%' OR code LIKE '%$payModeEscaped%' LIMIT 1");
            if (getNumRows($pmRs) > 0) {
                $pmObj = fetch($pmRs);
                $payModeId = $pmObj->id;
            }

            // === LOOKUP: billing_in_charge (from user table) ===
			$billingInChargeId = null;
			if (!empty($billingName)) {
				$billingEscaped = escapeString($billingName);
				$bicRs = query("SELECT id FROM user 
								WHERE CONCAT(first_name, ' ', last_name) LIKE '%$billingEscaped%' 
								LIMIT 1");
				if (getNumRows($bicRs) > 0) {
					$bicObj = fetch($bicRs);
					$billingInChargeId = $bicObj->id;
				} else {
					echo "WARNING - Billing In Charge not found in user table: $billingName <br>";
				}
			}

			// === LOOKUP: account_executive (from account_executive table) ===
			// $accountExecId = null;
			// if (!empty($accountExecName)) {
			// 	$aeEscaped = escapeString($accountExecName);
			// 	$aeRs = query("SELECT id FROM account_executive 
			// 				WHERE name LIKE '%$aeEscaped%' 
			// 				LIMIT 1");
			// 	if (getNumRows($aeRs) > 0) {
			// 		$aeObj = fetch($aeRs);
			// 		$accountExecId = $aeObj->id;
			// 	} else {
			// 		echo "WARNING - Account Executive not found: $accountExecName <br>";
			// 	}
			// }

			// === LOOKUP: account_executive (from account_executive table) ===
			$accountExecId = null;
			if (!empty($accountExecName)) {
				$aeEscaped = escapeString($accountExecName);
				$aeRs = query("SELECT id FROM account_executive 
							WHERE name LIKE '%$aeEscaped%' 
							LIMIT 1");
				if (getNumRows($aeRs) > 0) {
					$aeObj = fetch($aeRs);
					$accountExecId = $aeObj->id;
				} else {
					// Auto-generate email from name: "Sam Guevarra" → "sam.guevarra@gmail.com"
					$nameParts = explode(' ', trim($accountExecName));
					$firstName = isset($nameParts[0]) ? strtolower($nameParts[0]) : '';
					$lastName  = isset($nameParts[1]) ? strtolower($nameParts[1]) : '';
					$generatedEmail    = $firstName . '.' . $lastName . '@gmail.com';
					$generatedMobile   = '09111111111';
					$generatedUsername = $firstName . '.' . $lastName;

					// Generate next code (AE01, AE02, etc.)
					$lastCodeRs = query("SELECT code FROM account_executive ORDER BY id DESC LIMIT 1");
					if (getNumRows($lastCodeRs) > 0) {
						$lastCodeObj = fetch($lastCodeRs);
						$lastNum = intval(substr($lastCodeObj->code, 2));
						$newCode = 'AE' . str_pad($lastNum + 1, 2, '0', STR_PAD_LEFT);
					} else {
						$newCode = 'AE01';
					}

					$nameEscaped     = escapeString($accountExecName);
					$emailEscaped    = escapeString($generatedEmail);
					$mobileEscaped   = escapeString($generatedMobile);
					$usernameEscaped = escapeString($generatedUsername);

					query("INSERT INTO account_executive (
								code, name, email_address, 
								mobile_number, username, created_by, created_date
						) VALUES (
								'$newCode', '$nameEscaped', '$emailEscaped',
								'$mobileEscaped', '$usernameEscaped', 1, '$now'
						)");

					$accountExecId = mysql_insert_id();
					echo "Created Account Executive: $accountExecName | $generatedEmail | $generatedMobile <br>";
				}
			}

            // === Escape for SQL ===
            $companynameEsc    = escapeString($companyname);
            $accountnameEsc    = escapeString($accountname);
            $tinEsc            = $tin ? escapeString($tin) : null;
            $lineofbusinessEsc = $lineofbusiness ? escapeString($lineofbusiness) : null;
            $regionEsc         = $region ? escapeString($region) : null;
            $streetEsc         = $street ? escapeString($street) : null;
            $countryEsc        = $country ? escapeString($country) : null;

            // === CHECK IF SHIPPER EXISTS ===
            $checkRs = query("SELECT id FROM shipper WHERE account_name='$accountnameEsc' LIMIT 1");

            if (getNumRows($checkRs) > 0) {
                // UPDATE existing shipper
                $obj = fetch($checkRs);
                $shipperid = $obj->id;

                $updateFields = [];
                if ($tinEsc)            $updateFields[] = "tin='$tinEsc'";
                if ($lineofbusinessEsc) $updateFields[] = "line_of_business='$lineofbusinessEsc'";
                if ($payModeId)         $updateFields[] = "pay_mode_id=$payModeId";
                if ($billingInChargeId) $updateFields[] = "billing_in_charge=$billingInChargeId";
                if ($accountExecId)     $updateFields[] = "account_executive=$accountExecId";
                if ($regionEsc)         $updateFields[] = "company_state_province='$regionEsc'";
                if ($streetEsc)         $updateFields[] = "company_street_address='$streetEsc'";
                if ($countryEsc)        $updateFields[] = "company_country='$countryEsc'";

                if (!empty($updateFields)) {
                    query("UPDATE shipper SET " . implode(', ', $updateFields) . " WHERE id=$shipperid");
                }

                $checkPickup = query("SELECT id FROM shipper_pickup_address WHERE shipper_id='$shipperid' LIMIT 1");
                if (getNumRows($checkPickup) == 0 && $streetEsc) {
                    query("INSERT INTO shipper_pickup_address(shipper_id, default_flag, pickup_street_address)
                           VALUES('$shipperid', 1, '$streetEsc')");
                }

                echo "Updated: $accountname <br>";

            } else {
                // INSERT new shipper
                $accountnumber = getTransactionNumber(6);

                $payModeIdVal       = $payModeId        ? $payModeId         : 'NULL';
                $billingInChargeVal = $billingInChargeId ? $billingInChargeId : 'NULL';
                $accountExecVal     = $accountExecId    ? $accountExecId     : 'NULL';
                $tinVal             = $tinEsc            ? "'$tinEsc'"        : 'NULL';
                $lobVal             = $lineofbusinessEsc ? "'$lineofbusinessEsc'" : 'NULL';
                $regionVal          = $regionEsc         ? "'$regionEsc'"     : 'NULL';
                $streetVal          = $streetEsc         ? "'$streetEsc'"     : 'NULL';
                $countryVal         = $countryEsc        ? "'$countryEsc'"    : 'NULL';

                query("INSERT INTO shipper (
                            account_number, account_name, company_name,
                            tin, line_of_business, pay_mode_id,
                            billing_in_charge, account_executive,
                            company_state_province, company_street_address,
                            company_country, created_by, created_date
                       ) VALUES (
                            '$accountnumber', '$accountnameEsc', '$companynameEsc',
                            $tinVal, $lobVal, $payModeIdVal,
                            $billingInChargeVal, $accountExecVal,
                            $regionVal, $streetVal,
                            $countryVal, 1, '$now'
                       )");

                $shipperid = mysql_insert_id();

                if ($streetEsc) {
                    query("INSERT INTO shipper_pickup_address(shipper_id, default_flag, pickup_street_address)
                           VALUES('$shipperid', 1, '$streetEsc')");
                }

                echo "Inserted: $accountnumber - $accountname <br>";
            }
        }

        $line++;
    }
}

$reader->close();

query("UPDATE shipper SET non_pod_flag=0, vat_flag=1 WHERE non_pod_flag IS NULL OR vat_flag IS NULL");
echo "Done!";
?>