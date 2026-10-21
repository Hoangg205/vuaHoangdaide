<?php
require_once 'config/database.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) { die("<h1 style='color:white;text-align:center;'>Không tìm thấy món quà!</h1>"); }
$stmt = $pdo->prepare("SELECT * FROM recipients WHERE id = :id");
$stmt->execute(['id' => $id]);
$gift = $stmt->fetch();
if (!$gift) { die("<h1 style='color:white;text-align:center;'>Món quà không tồn tại!</h1>"); }

// Tách lời chúc thành nhiều câu ngắn cho Scene 5
$messages = explode('.', $gift['message']);
$messages = array_values(array_filter(array_map('trim', $messages)));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Gửi <?= htmlspecialchars($gift['recipient_name']) ?> ❤️</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="stars" id="starsContainer"></div>

    <div class="scene-container">
        <!-- Scene 1: Lồng kính -->
        <div class="scene active" id="scene1">
            <div class="glass-dome" id="dome" onclick="nextScene(2)">
                <div class="rose">🌹</div>
            </div>
            <div class="hint-text">👆 Chạm nhẹ bông hoa nhé</div>
        </div>

        <!-- Scene 2: Lời chúc -->
        <div class="scene" id="scene2" onclick="nextScene(3)">
            <div class="greet">
                <div class="greet-top">
                    <span class="deco">✨</span>
                    <span class="grad-text">Chúc mừng</span>
                    <span class="deco">✨</span>
                </div>
                <div class="grad-text big">20/10</div><br>
                <div class="name-badge">💖 <?= htmlspecialchars($gift['recipient_name']) ?> 💖</div>
                <div class="hint-text">Chạm để tiếp tục</div>
            </div>
        </div>

        <!-- Scene 3: Bó hoa -->
        <div class="scene" id="scene3">
            <div class="bouquet" onclick="nextScene(4)">💐</div>
            <div class="hint-text">🌸 Chạm vào bó hoa nào</div>
        </div>

        <!-- Scene 4: Bức thư -->
        <div class="scene" id="scene4">
            <div class="envelope" id="envelope" onclick="openEnvelope()">
                <div class="seal">💗</div>
                <div class="letter-paper">
                    <b>Gửi <?= htmlspecialchars($gift['recipient_name']) ?>,</b><br>
                    <?= nl2br(htmlspecialchars($gift['message'])) ?><br>
                    <i>- <?= htmlspecialchars($gift['sender_name']) ?> -</i>
                </div>
            </div>
            <div class="hint-text" id="hint-letter">💌 Mở thư nào</div>
        </div>

        <!-- Scene 5: Vũ trụ ảnh và kỷ niệm -->
        <div class="scene" id="scene5">
            <div class="universe" id="universe"></div>
        </div>
    </div>

   <audio id="bgMusic" src="uploads/pepmau.mp3" loop></audio>
    <div class="music-control" onclick="toggleMusic()" id="musicBtn">🎵</div>

    <script>
        const rand = (a, b) => Math.random() * (b - a) + a;
        const pick = arr => arr[Math.floor(Math.random() * arr.length)];

        // --- 1. Sao lấp lánh nhiều màu + sao băng ---
        const starsContainer = document.getElementById('starsContainer');
        const starColors = ['#fff', '#ffe9f0', '#e6dcff', '#fff6c9'];
        for (let i = 0; i < 120; i++) {
            let s = document.createElement('div');
            s.className = 'star';
            let size = rand(1, 3.5);
            s.style.cssText = `width:${size}px;height:${size}px;left:${rand(0,100)}vw;top:${rand(0,100)}vh;
                background:${pick(starColors)};animation-duration:${rand(1.5,4)}s;animation-delay:${rand(0,3)}s`;
            starsContainer.appendChild(s);
        }
        setInterval(() => {
            let s = document.createElement('div');
            s.className = 'shooting-star';
            s.style.left = rand(40, 100) + 'vw';
            s.style.top = rand(0, 40) + 'vh';
            document.body.appendChild(s);
            setTimeout(() => s.remove(), 1500);
        }, 3500);

        // --- 2. Lấp lánh + cánh hoa rơi trong lồng kính ---
        const dome = document.getElementById('dome');
        ['✨','⭐','💫','✨','🌟'].forEach((e, i) => {
            let sp = document.createElement('span');
            sp.className = 'sparkle';
            sp.innerText = e;
            sp.style.cssText = `left:${rand(-30,150)}px;top:${rand(0,260)}px;font-size:${rand(12,20)}px;animation-delay:${i*0.45}s`;
            dome.appendChild(sp);
        });
        for (let i = 0; i < 6; i++) {
            let p = document.createElement('span');
            p.className = 'petal';
            p.innerText = '🌸';
            p.style.cssText = `left:${rand(25,115)}px;font-size:${rand(10,16)}px;--dx:${rand(-30,30)}px;
                animation-duration:${rand(4,7)}s;animation-delay:${rand(0,5)}s`;
            dome.appendChild(p);
        }

        // --- 3. Chạm đâu, tim bung ra đó ---
        document.addEventListener('pointerdown', e => {
            for (let i = 0; i < 7; i++) {
                let b = document.createElement('span');
                b.className = 'burst';
                b.innerText = pick(['💖','💕','✨','🌸','💗','⭐']);
                b.style.left = e.clientX + 'px';
                b.style.top = e.clientY + 'px';
                b.style.setProperty('--x', rand(-70, 70) + 'px');
                b.style.setProperty('--y', rand(-90, 20) + 'px');
                b.style.setProperty('--rot', rand(-60, 60) + 'deg');
                document.body.appendChild(b);
                setTimeout(() => b.remove(), 900);
            }
        });

        // --- 4. Chuyển cảnh ---
        let currentScene = 1;
        function nextScene(num) {
            document.getElementById('scene' + currentScene).classList.remove('active');
            currentScene = num;
            document.getElementById('scene' + currentScene).classList.add('active');

            let audio = document.getElementById('bgMusic');
            if (audio.paused) {
                audio.play().then(() => setMusicUI(true)).catch(() => {});
            }
            if (num === 5) initUniverse();
        }

        // --- 5. Mở thư ---
        function openEnvelope() {
            let env = document.getElementById('envelope');
            if (!env.classList.contains('open')) {
                env.classList.add('open');
                document.getElementById('hint-letter').innerText = '💕 Chạm thêm lần nữa để xem tiếp';
            } else {
                nextScene(5);
            }
        }

        // --- 6. Vũ trụ (Scene 5) ---
        function initUniverse() {
            const universe = document.getElementById('universe');
            const photoUrl = '<?= htmlspecialchars($gift['photo']) ?>';
            const messages = <?= json_encode($messages, JSON_UNESCAPED_UNICODE) ?>;
            const heartList = ['❤️','💖','💗','💕','🌸','✨','🦋'];

            function createElement(type) {
                let el = document.createElement(type === 'img' ? 'img' : 'div');
                let size = rand(60, 120);
                let duration = rand(10, 20);

                if (type === 'img') {
                    el.src = photoUrl;
                    el.className = 'floating-item';
                    el.style.width = size + 'px';
                    el.style.height = (size * 1.25) + 'px';
                    el.style.setProperty('--r', rand(-14, 14) + 'deg');
                } else if (type === 'heart') {
                    el.className = 'floating-heart';
                    el.innerText = pick(heartList);
                    el.style.fontSize = rand(20, 40) + 'px';
                    duration = rand(5, 10);
                } else {
                    el.className = 'floating-msg';
                    el.innerText = messages.length ? pick(messages) : 'Mãi vui vẻ nhé! 💖';
                    el.style.fontSize = rand(12, 20) + 'px';
                    el.style.color = pick(['#ff9ab0', '#ffd1dc', '#fff', '#dcc7ff']);
                    el.style.setProperty('--r', rand(-5, 5) + 'deg');
                }

                // Giữ phần tử nằm gọn trong màn hình (không tràn mép phải)
                const vw = window.innerWidth;
                const maxW = type === 'text' ? Math.min(vw * 0.78, 340) : (type === 'img' ? size : 40);
                el.style.left = rand(8, Math.max(9, vw - maxW - 8)) + 'px';
                el.style.animationDuration = duration + 's';
                el.style.animationDelay = rand(0, 5) + 's';
                el.addEventListener('animationend', () => { el.remove(); createElement(type); });
                universe.appendChild(el);
            }

            for (let i = 0; i < 10; i++) createElement('img');
            for (let i = 0; i < 24; i++) createElement('heart');
            for (let i = 0; i < 8; i++) createElement('text');
        }

        // --- 7. Nhạc ---
        function setMusicUI(on) {
            let btn = document.getElementById('musicBtn');
            btn.innerHTML = on ? '🎶' : '🎵';
            btn.classList.toggle('playing', on);
        }
        function toggleMusic() {
            let audio = document.getElementById('bgMusic');
            if (audio.paused) { audio.play(); setMusicUI(true); }
            else { audio.pause(); setMusicUI(false); }
        }
    </script>
</body>
</html>