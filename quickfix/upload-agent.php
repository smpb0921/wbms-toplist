<?php
ini_set('max_execution_time', 30000);
include("../config/connection.php");
include("../config/functions.php");

require_once '../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$reader = ReaderFactory::create(Type::XLSX);
$reader->open('excel/upload-agent-sample-for-uat.xlsx');

$headerColumns = array(
    'CODE',              // 0
    'COMPANY NAME',      // 1
    'AREA',              // 2
    'REMARKS',           // 3
    'REGION/PROVINCE',   // 4
    'CITY',              // 5
    'DISTRICT/BARANGAY', // 6
    'STREET',            // 7
    'COUNTRY',           // 8
    'CONTACT PERSON',    // 9
    'PHONE NUMBER',      // 10
    'EMAIL',             // 11
    'MOBILE NUMBER'      // 12
);

$line = 1;
$headerformatflag = 1;
$now = date('Y-m-d H:i:s');

foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {

        if ($line == 1) {
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
            $code            = trim($row[0]) == '' ? null : strtoupper(trim($row[0]));
            $companyname     = trim($row[1]) == '' ? null : trim($row[1]);
            $area            = trim($row[2]) == '' ? null : strtoupper(trim($row[2]));
            $remarks         = trim($row[3]) == '' ? null : trim($row[3]);
            $region          = trim($row[4]) == '' ? null : trim($row[4]);
            $city            = trim($row[5]) == '' ? null : trim($row[5]);
            $district        = trim($row[6]) == '' ? null : trim($row[6]);
            $street          = trim($row[7]) == '' ? null : trim($row[7]);
            $country         = trim($row[8]) == '' ? null : trim($row[8]);
            $contactperson   = trim($row[9]) == '' ? null : trim($row[9]);
            $phonenumber     = trim($row[10]) == '' ? null : trim($row[10]);
            $email           = trim($row[11]) == '' ? null : trim($row[11]);
            $mobilenumber    = trim($row[12]) == '' ? null : trim($row[12]);

            if (empty($code) || empty($companyname)) {
                $line++;
                continue;
            }

            // === Escape for SQL ===
            $codeEsc          = escapeString($code);
            $companynameEsc   = escapeString($companyname);
            $areaEsc          = $area     ? escapeString($area)     : null;
            $remarksEsc       = $remarks  ? escapeString($remarks)  : null;
            $regionEsc        = $region   ? escapeString($region)   : null;
            $cityEsc          = $city     ? escapeString($city)     : null;
            $districtEsc      = $district ? escapeString($district) : null;
            $streetEsc        = $street   ? escapeString($street)   : null;
            $countryEsc       = $country  ? escapeString($country)  : null;
            $contactEsc       = $contactperson ? escapeString($contactperson) : null;
            $phoneEsc         = $phonenumber   ? escapeString($phonenumber)   : null;
            $emailEsc         = $email         ? escapeString($email)         : null;
            $mobileEsc        = $mobilenumber  ? escapeString($mobilenumber)  : null;

            // SQL NULL helpers
            $areaVal      = $areaEsc     ? "'$areaEsc'"     : 'NULL';
            $remarksVal   = $remarksEsc  ? "'$remarksEsc'"  : 'NULL';
            $regionVal    = $regionEsc   ? "'$regionEsc'"   : 'NULL';
            $cityVal      = $cityEsc     ? "'$cityEsc'"     : 'NULL';
            $districtVal  = $districtEsc ? "'$districtEsc'" : 'NULL';
            $streetVal    = $streetEsc   ? "'$streetEsc'"   : 'NULL';
            $countryVal   = $countryEsc  ? "'$countryEsc'"  : 'NULL';
            $contactVal   = $contactEsc  ? "'$contactEsc'"  : 'NULL';
            $phoneVal     = $phoneEsc    ? "'$phoneEsc'"    : 'NULL';
            $emailVal     = $emailEsc    ? "'$emailEsc'"    : 'NULL';
            $mobileVal    = $mobileEsc   ? "'$mobileEsc'"   : 'NULL';

            // === CHECK IF AGENT EXISTS (by code) ===
            $checkRs = query("SELECT id FROM agent WHERE code='$codeEsc' LIMIT 1");

            if (getNumRows($checkRs) > 0) {
                // UPDATE existing agent
                $obj = fetch($checkRs);
                $agentid = $obj->id;

                query("UPDATE agent SET
                            company_name='$companynameEsc',
                            area=$areaVal,
                            remarks=$remarksVal,
                            company_state_province=$regionVal,
                            company_city=$cityVal,
                            company_district=$districtVal,
                            company_street_address=$streetVal,
                            company_country=$countryVal
                       WHERE id=$agentid");

                echo "Updated: $code - $companyname <br>";

            } else {
                // INSERT new agent
                query("INSERT INTO agent (
                            code,
                            company_name,
                            company_street_address,
                            company_district,
                            company_city,
                            company_state_province,
                            company_country,
                            area,
                            remarks,
                            created_by,
                            created_date
                       ) VALUES (
                            '$codeEsc',
                            '$companynameEsc',
                            $streetVal,
                            $districtVal,
                            $cityVal,
                            $regionVal,
                            $countryVal,
                            $areaVal,
                            $remarksVal,
                            1,
                            '$now'
                       )");

                $agentid = mysql_insert_id();
                echo "Inserted: $code - $companyname <br>";
            }

            // === INSERT CONTACT PERSON (if provided) ===
            // Check if agent has a contact table — insert contact person separately if needed
            if ($contactEsc || $phoneEsc || $emailEsc || $mobileEsc) {
                $checkContact = query("SELECT id FROM agent_contact WHERE agent_id='$agentid' LIMIT 1");
                if (getNumRows($checkContact) == 0) {
                    query("INSERT INTO agent_contact (
                                agent_id,
                                contact_name,
                                phone_number,
                                email_address,
                                mobile_number,
                                default_flag
                           ) VALUES (
                                '$agentid',
                                $contactVal,
                                $phoneVal,
                                $emailVal,
                                $mobileVal,
                                1
                           )");
                } else {
                    query("UPDATE agent_contact SET
                                contact_name=$contactVal,
                                phone_number=$phoneVal,
                                email_address=$emailVal,
                                mobile_number=$mobileVal
                           WHERE agent_id='$agentid'");
                }
            }
        }

        $line++;
    }
}

$reader->close();
echo "Done!";
?>