<!DOCTYPE html>
<html>
<head>
    <title>TESTS</title>
</head>
<body>
    <div>
        <h1>Завантажити зображення</h1>
        <input type="file" id="imageInput" accept="image/*">
        <button onclick="uploadImage()">Завантажити</button>
        <div id="result"></div>
        <div>
            Файли зараз: 
            <?php
                foreach (glob("database/images" . "/*.png") as $pngFile) {
                    echo "/" . $pngFile . "<br>";
                }
            ?>
        </div>
        <script>
            function uploadImage() {
                const imageInput = document.getElementById('imageInput');
                const file = imageInput.files[0];
        
                if (!file) {
                    showResult('Виберіть файл зображення для завантаження.');
                    return;
                }
        
                const formData = new FormData();
                formData.append('image', file);
        
                fetch('/t/src/api/upload_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showResult(`Помилка: ${data.error}`);
                    } else {
                        showResult(`Зображення успішно завантажено. Шлях: ${data.path}`);
                    }
                })
                .catch(error => {
                    showResult(`Помилка: ${error}`);
                });
            }
        
            function showResult(message) {
                const resultDiv = document.getElementById('result');
                resultDiv.innerHTML = message;
            }
        </script>
    </div>
</body>
</html>