<?php
require_once __DIR__ . "/../../../database/database_connection.php";
require_once __DIR__ . "/../../lib/php_functions.php";

$courseCode = isset($_GET['course']) ? trim($_GET['course']) : '';
$unitCode   = isset($_GET['unit']) ? trim($_GET['unit']) : '';
$section    = isset($_GET['section']) ? trim($_GET['section']) : '7E';

if (empty($courseCode) || empty($unitCode)) {
    die("Course and Unit are required.");
}

/*
|--------------------------------------------------------------------------
| Get Course Name
|--------------------------------------------------------------------------
*/
$coursename = "";
$stmtCourse = $pdo->prepare("SELECT name FROM tblcourse WHERE courseCode = :courseCode LIMIT 1");
$stmtCourse->execute([':courseCode' => $courseCode]);
$courseRow = $stmtCourse->fetch(PDO::FETCH_ASSOC);
if ($courseRow) {
    $coursename = $courseRow['name'];
}

/*
|--------------------------------------------------------------------------
| Get Unit Name
|--------------------------------------------------------------------------
*/
$unitname = "";
$stmtUnit = $pdo->prepare("SELECT name FROM tblunit WHERE unitCode = :unitCode LIMIT 1");
$stmtUnit->execute([':unitCode' => $unitCode]);
$unitRow = $stmtUnit->fetch(PDO::FETCH_ASSOC);
if ($unitRow) {
    $unitname = $unitRow['name'];
}

/*
|--------------------------------------------------------------------------
| Get distinct attendance dates
|--------------------------------------------------------------------------
*/
$stmtDates = $pdo->prepare("
    SELECT DISTINCT dateMarked
    FROM tblattendance
    WHERE course = :courseCode AND unit = :unitCode
    ORDER BY dateMarked ASC
");
$stmtDates->execute([
    ':courseCode' => $courseCode,
    ':unitCode'   => $unitCode
]);
$dates = $stmtDates->fetchAll(PDO::FETCH_COLUMN);

/*
|--------------------------------------------------------------------------
| Get students with names
|--------------------------------------------------------------------------
*/
$stmtStudents = $pdo->prepare("
    SELECT DISTINCT 
        s.registrationNumber,
        CONCAT(s.firstName, ' ', s.lastName) AS studentName
    FROM tblstudents s
    INNER JOIN tblattendance a 
        ON a.studentRegistrationNumber = s.registrationNumber
    WHERE a.course = :courseCode
      AND a.unit   = :unitCode
    ORDER BY s.registrationNumber ASC
");
$stmtStudents->execute([
    ':courseCode' => $courseCode,
    ':unitCode'   => $unitCode
]);
$students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Download as Excel
|--------------------------------------------------------------------------
*/
$filename = 'attendance_sheet_' . $unitCode . '_' . date('Y-m-d') . '.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Sheet</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
        }

        .heading {
            text-align: center;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .subheading {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .meta {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .meta td {
            padding: 6px 10px;
            font-size: 16px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        .attendance-table th {
            font-weight: bold;
        }

        .left {
            text-align: left !important;
        }

        .signature {
            margin-top: 40px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="heading">Northern University of Business and Technology, Khulna</div>
    <div class="subheading">Attendance Sheet</div>

    <table class="meta">
        <tr>
            <td><strong>Section:</strong> <?php echo htmlspecialchars($section); ?></td>
            <td><strong>Course Code:</strong> <?php echo htmlspecialchars($unitCode); ?></td>
            <td><strong>Course Title:</strong> <?php echo htmlspecialchars($coursename); ?></td>
        </tr>
        <tr>
            <td><strong>Unit:</strong> <?php echo htmlspecialchars($unitname); ?></td>
            <td><strong>Date:</strong> <?php echo date('Y-m-d'); ?></td>
            <td></td>
        </tr>
    </table>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Student ID</th>
                <th>Name of the Students</th>
                <?php foreach ($dates as $date): ?>
                    <th><?php echo htmlspecialchars($date); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php $sl = 1; ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $sl++; ?></td>
                        <td><?php echo htmlspecialchars($student['registrationNumber']); ?></td>
                        <td class="left"><?php echo htmlspecialchars($student['studentName']); ?></td>

                        <?php foreach ($dates as $date): ?>
                            <?php
                            $stmtAttendance = $pdo->prepare("
                                SELECT attendanceStatus
                                FROM tblattendance
                                WHERE studentRegistrationNumber = :regNo
                                  AND dateMarked = :dateMarked
                                  AND course = :courseCode
                                  AND unit = :unitCode
                                LIMIT 1
                            ");
                            $stmtAttendance->execute([
                                ':regNo'      => $student['registrationNumber'],
                                ':dateMarked' => $date,
                                ':courseCode' => $courseCode,
                                ':unitCode'   => $unitCode
                            ]);
                            $attendance = $stmtAttendance->fetch(PDO::FETCH_ASSOC);

                            $status = 'A';
                            if ($attendance && strtolower($attendance['attendanceStatus']) === 'present') {
                                $status = 'P';
                            } elseif ($attendance && strtoupper($attendance['attendanceStatus']) === 'P') {
                                $status = 'P';
                            }
                            ?>
                            <td><?php echo $status; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo 3 + count($dates); ?>">No attendance data found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">Signature of the Instructor: ____________________</div>

</body>
</html>