<?php
session_start();

// 세션에 사용자 정보가 있는지 확인
if (isset($_SESSION['name']) && isset($_SESSION['grade']) && isset($_SESSION['class']) && isset($_SESSION['number'])) {
    // 세션에 사용자 정보가 있으면 환영 메시지 출력
    $name1 = $_SESSION['name'];
    $department = $_SESSION['department'];
    $grade = $_SESSION['grade'];
    $class = $_SESSION['class'];
    $number = $_SESSION['number'];
    $username = $_SESSION['username'];
} else {
    // 세션에 정보가 없으면 로그인 페이지로 리다이렉트
    header("Location: main/login/login.html");
    exit();
}

// 데이터베이스 연결
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "hanbom";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>입사지원서 등록</title>
    <link href="imge/logo.png" rel="shortcut icon" type="imge/x-icon"> <!--서버 아이콘 변경-->
    <link rel="stylesheet" href="resume_styles.css">
    <script>
        // 자격증 행 추가 함수
        function addCertificateRow() {
            const table = document.getElementById("certificateTable");
            const newRow = table.insertRow(-1); // 마지막에 행 추가

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3); // 제거 버튼 셀 추가

            cell1.innerHTML = '<input type="date" name="certificate_date[]">';
            cell2.innerHTML = '<input type="text" name="certificate_name[]">';
            cell3.innerHTML = '<input type="text" name="certificate_authority[]">';
            cell4.innerHTML = '<button type="button" onclick="removeCertificateRow(this)">제거</button>'; // 제거 버튼
        }

        // 자격증 행 제거 함수
        function removeCertificateRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row); // 해당 행 삭제
        }

        // 수상 행 추가 함수
        function addAwardRow() {
            const table = document.getElementById("awardTable");
            const newRow = table.insertRow(-1); // 마지막에 행 추가

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3); // 제거 버튼 셀 추가

            cell1.innerHTML = '<input type="date" name="award_date[]">';
            cell2.innerHTML = '<input type="text" name="award_name[]">';
            cell3.innerHTML = '<input type="text" name="award_authority[]">';
            cell4.innerHTML = '<button type="button" onclick="removeAwardRow(this)">제거</button>'; // 제거 버튼
        }

        // 수상 행 제거 함수
        function removeAwardRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row); // 해당 행 삭제
        }

        // 교육 및 연수 활동 행 추가 함수
        function addEventRow() {
            const table = document.getElementById("eventTable");
            const newRow = table.insertRow(-1); // 마지막에 행 추가

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3); // 제거 버튼 셀 추가

            cell1.innerHTML = '<input type="date" name="event_date[]">';
            cell2.innerHTML = '<input type="text" name="event_name[]">';
            cell3.innerHTML = '<input type="text" name="event_authority[]">';
            cell4.innerHTML = '<button type="button" onclick="removeEventRow(this)">제거</button>'; // 제거 버튼
        }

        // 교육 및 연수 활동 행 제거 함수
        function removeEventRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row); // 해당 행 삭제
        }

        // 사진 미리보기 함수
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById("photoPreview");
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }
                reader.readAsDataURL(file);
            }
        }

        fetch('http://localhost/submit_resume.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data), // URLSearchParams로 변환
        })
        .then(response => {
            console.log('응답 수신:', response); // 응답 로그
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // JSON으로 변환
        })
        .then(data => {
            console.log('서버 응답 데이터:', data); // 서버 응답 데이터 로그
            if (data.message === '자기소개서 등록이 완료되었습니다') {
                // 알림 표시
                alert('자기소개서 등록이 완료되었습니다.');

                // 1초 후 로그인 페이지로 이동
                setTimeout(() => {
                    window.location.href = 'localhost/index.php'; // 로그인 페이지로 이동
                }, 1000); // 1000ms = 1초
            } else {
                errorMessage.innerHTML = data.message; // 서버에서 전달된 메시지
                errorMessage.style.display = 'block'; // 오류 메시지 표시
            }
        })
        .catch(error => {
            console.error('Error:', error); // 에러 메시지를 콘솔에 출력
        });
    </script>
</head>
<body>

<div class="container">
    <h1><?php echo $name1?>님의 입사지원서</h1>
    <!-- <h1>입 사 지 원 서</h1> -->
    <!-- <h2><?php echo $name1?>님의 입사지원서</h2> -->
    <form action="submit_resume.php" id="resumeForm" method="POST" enctype="multipart/form-data">
        <!-- 연락사항 -->
        <fieldset>
            <legend>[연락사항]</legend>
            <div class="contact-info">
                <div class="photo">
                    <label for="photo">사진 (3 x 4)</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
                    <!-- 미리보기 이미지 추가 -->
                    <img id="photoPreview" src="#" alt="사진 미리보기" style="display:none; margin-top: 15px; width: 200px; height: 250px; border: 1px solid #ccc;">
                </div>
                <div class="info">
                    <label for="name">성명</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="birthdate">생년월일</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                    
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="phone">휴대폰</label>
                    <input type="text" id="phone" name="phone" required>

                    <label for="address">주소</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>
        </fieldset>

        <!-- 학력사항 -->
        <fieldset>
            <legend>[학력사항]</legend>
            <table>
                <thead>
                    <tr>
                        <th>입학년월</th>
                        <th>졸업년월</th>
                        <th>학교명</th>
                        <th>학과</th>
                        <th>비고</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="date" name="entrance_date_1" required></td>
                        <td><input type="date" name="graduation_date_1" required></td>
                        <td><input type="text" name="school_name_1" value="OO고등학교" required></td>
                        <td><input type="text" name="major_1" require></td>
                        <td><input type="text" name="note_1" value="졸업 예정" required></td>
                    </tr>
                    <tr>
                        <td><input type="date" name="entrance_date_2" required></td>
                        <td><input type="date" name="graduation_date_2" required></td>
                        <td><input type="text" name="school_name_2" value="OO중학교" required></td>
                        <td><input type="text" name="major_2" ></td>
                        <td><input type="text" name="note_2" value="졸업" required></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- 자격증 및 특기사항 -->
        <fieldset>
            <legend>[자격증 및 특기사항]</legend>
            <table id="certificateTable">
                <thead>
                    <legend>자격증</legend>
                    <tr>
                        <th>해당년월일</th>
                        <th>관련 내용</th>
                        <th>시험처</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="date" name="certificate_date[]"></td>
                        <td><input type="text" name="certificate_name[]"></td>
                        <td><input type="text" name="certificate_authority[]"></td>
                        <td><button type="button" onclick="removeCertificateRow(this)">제거</button></td> <!-- 제거 버튼 추가 -->
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addCertificateRow()">자격증 추가</button>
            <table id="awardTable">
                <thead>
                    <legend>수상</legend>
                    <tr>
                        <th>해당년월일</th>
                        <th>관련 내용</th>
                        <th>시험처</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="date" name="award_date[]"></td>
                        <td><input type="text" name="award_name[]"></td>
                        <td><input type="text" name="award_authority[]"></td>
                        <td><button type="button" onclick="removeAwardRow(this)">제거</button></td> <!-- 제거 버튼 추가 -->
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addAwardRow()">수상 추가</button>
            <table id="eventTable">
                <thead>
                    <legend>교육 및 연수 활동</legend>
                    <tr>
                        <th>해당년월일</th>
                        <th>관련 내용</th>
                        <th>시험처</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="date" name="event_date[]"></td>
                        <td><input type="text" name="event_name[]"></td>
                        <td><input type="text" name="event_authority[]"></td>
                        <td><button type="button" onclick="removeEventRow(this)">제거</button></td> <!-- 제거 버튼 추가 -->
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addEventRow()">교육 및 연수 활동 추가</button>
        </fieldset>

        <!-- 자기소개서 -->
        <fieldset>
            <legend>[자기소개서]</legend>

            <label for="motivation">지원 동기 및 입사 후 포부:</label>
            <textarea id="motivation" name="motivation" rows="4" cols="50" required></textarea><br>

            <label for="experience">학창 시절의 경험과 역량 개발을 위한 노력:</label>
            <textarea id="experience" name="experience" rows="4" cols="50" required></textarea><br>

            <label for="strengths">장점과 보완점:</label>
            <textarea id="strengths" name="strengths" rows="4" cols="50" required></textarea><br>

            <label for="growth">성장 과정과 가치관:</label>
            <textarea id="growth" name="growth" rows="4" cols="50" required></textarea><br>
        </fieldset>

        <!-- 서명 -->
        <fieldset class="declaration">
            <legend style="text-align: left;">[서명]</legend>
            <p>위 내용은 사실과 다름이 없습니다.</p>
            <label style="text-align: left;" for="signature_date">날짜:</label>
            <input type="date" id="signature_date" name="signature_date" required>
            <br>
            <label style="text-align: left;" for="signature_name">지원자 이름: </label>
            <input type="text" id="signature_name" name="signature_name" value="<?php echo $name1; ?>" required>
        </fieldset>

        <!-- 제출 버튼 -->
        <button type="submit">제출</button>
    </form>
</div>

</body>
</html>
