// Scratch Game - Anti Gagal Version
let currentStage = 1;
let gameComplete = false;

function openScratchCard() {
    // Acak urutan emoji setiap kali modal dibuka
    const emojis = [
        "🫶", "❤️", "🥳", "🫣",
        "🤍", "😍", "🥰", "🩷",
        "💖", "💕", "💓", "💗",
        "💝", "💞", "💘", "💣"
    ];
    
    // Fisher-Yates shuffle
    for (let i = emojis.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [emojis[i], emojis[j]] = [emojis[j], emojis[i]];
    }

    // Render ulang scratch card dengan urutan baru
    const board = document.querySelector('.scratch-game-board');
    if (board) {
        board.innerHTML = '';
        emojis.forEach(emoji => {
            const card = document.createElement('div');
            card.className = 'scratch-card';
            card.setAttribute('data-emoji', emoji);
            const canvas = document.createElement('canvas');
            card.appendChild(canvas);
            board.appendChild(card);
        });
    }

    const modal = new bootstrap.Modal(document.getElementById('scratchCardModal'));
    modal.show();

    // Auto-play Forever Young music
    const audio = document.getElementById('foreverYoungAudio');
    if (audio) {
        audio.volume = 0.4;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }

    resetGame();
}

function resetGame() {
    currentStage = 1;
    gameComplete = false;
    document.querySelectorAll('.game-stage').forEach(stage => stage.classList.add('d-none'));
    document.getElementById('stage1').classList.remove('d-none');
}

function nextStage(stageNumber) {
    document.getElementById(`stage${currentStage}`).classList.add('d-none');
    document.getElementById(`stage${stageNumber}`).classList.remove('d-none');
    currentStage = stageNumber;

    if (stageNumber === 3) {
        setTimeout(initScratchCards, 500);
    }
}

function initScratchCards() {
    const cards = document.querySelectorAll('.scratch-card');
    cards.forEach(card => {
        setupScratchCard(card);
    });
}

function setupScratchCard(card) {
    const canvas = card.querySelector('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 60;
    canvas.height = 60;

    // Fill canvas with gray overlay for scratching
    ctx.fillStyle = 'rgba(255,255,255,0.8)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    let isScratching = false;
    let revealed = false;

    function handleScratch(e) {
        if (gameComplete || revealed) return;

        const rect = canvas.getBoundingClientRect();
        const x = (e.type.includes('mouse') ? e.clientX : e.touches[0].clientX) - rect.left;
        const y = (e.type.includes('mouse') ? e.clientY : e.touches[0].clientY) - rect.top;

        // Scale coordinates to canvas size
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        ctx.globalCompositeOperation = 'destination-out';
        ctx.beginPath();
        ctx.arc(x * scaleX, y * scaleY, 15, 0, Math.PI * 2);
        ctx.fill();
        ctx.globalCompositeOperation = 'source-over';

        // Check if enough area is scratched
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
        let transparentPixels = 0;
        for (let i = 3; i < imageData.length; i += 4) {
            if (imageData[i] === 0) transparentPixels++;
        }
        const totalPixels = canvas.width * canvas.height;

        if (transparentPixels / totalPixels > 0.15 && !revealed) {
            revealed = true;
            console.log('🎮 REVEAL STARTED for emoji:', card.dataset.emoji);
            
            // METODE BRUTE FORCE - HAPUS SEMUA CHILD DAN BUAT ULANG
            card.innerHTML = '';
            
            // Reset semua style dengan force
            card.removeAttribute('style');
            card.removeAttribute('class');
            
            // Buat container khusus untuk emoji
            const emojiContainer = document.createElement('div');
            emojiContainer.innerHTML = card.dataset.emoji;
            
            // Apply style super kuat dengan setAttribute
            card.setAttribute('style', `
                width: 60px !important;
                height: 60px !important;
                background: white !important;
                border: 3px solid #ff6b9d !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                position: relative !important;
                cursor: pointer !important;
                font-size: 2em !important;
                color: #000 !important;
                font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", Arial, sans-serif !important;
                text-align: center !important;
                line-height: 1 !important;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
                z-index: 999 !important;
            `);
            
            // Apply style pada container emoji juga
            emojiContainer.setAttribute('style', `
                font-size: 2em !important;
                color: #000 !important;
                font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", Arial, sans-serif !important;
                text-align: center !important;
                line-height: 1 !important;
                width: 100% !important;
                height: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            `);
            
            card.appendChild(emojiContainer);
            card.classList.add('revealed-brute-force');
            
            console.log('🎮 REVEAL COMPLETED:', {
                emoji: card.dataset.emoji,
                innerHTML: card.innerHTML,
                style: card.getAttribute('style')
            });

            if (card.dataset.emoji === '💣') {
                gameComplete = true;
                setTimeout(showResult, 800);
            }
        }
    }

    // Event listeners
    canvas.addEventListener('mousedown', (e) => {
        isScratching = true;
        handleScratch(e);
    });
    canvas.addEventListener('mouseup', () => {
        isScratching = false;
    });
    canvas.addEventListener('mouseleave', () => {
        isScratching = false;
    });
    canvas.addEventListener('mousemove', (e) => {
        if (isScratching) handleScratch(e);
    });

    // Touch events
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        isScratching = true;
        handleScratch(e);
    });
    canvas.addEventListener('touchend', () => {
        isScratching = false;
    });
    canvas.addEventListener('touchcancel', () => {
        isScratching = false;
    });
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (isScratching) handleScratch(e);
    });
}

function showResult() {
    document.getElementById('stage3').classList.add('d-none');
    document.getElementById('stage4').classList.remove('d-none');

    const resultText = document.getElementById('resultText');
    const resultSticker = document.getElementById('resultSticker');

    // Change sticker to explosion
    resultSticker.src = 'https://feeldreams.github.io/emawh.gif';

    // Type animation for result text
    const messages = [
        "Yaahh kena Boom!! 💥😝",
        "Kalau kamu kena boom...",
        "Kamu harus jadi pacarku yaa! 😆😍🩷",
        "Gaboleh nolak!! 😝🫣💐"
    ];

    let messageIndex = 0;

    function typeMessage() {
        if (messageIndex >= messages.length) {
            setTimeout(startLoveAnimation, 1000);
            return;
        }

        const message = messages[messageIndex];
        let charIndex = 0;
        resultText.innerHTML = '<div class="type-animation"></div>';
        const textElement = resultText.querySelector('.type-animation');

        function typeChar() {
            if (charIndex < message.length) {
                textElement.textContent = message.substring(0, charIndex + 1);
                charIndex++;
                setTimeout(typeChar, 50);
            } else {
                textElement.classList.remove('type-animation');
                messageIndex++;
                setTimeout(() => {
                    resultText.innerHTML += '<br><div class="type-animation"></div>';
                    typeMessage();
                }, 800);
            }
        }
        typeChar();
    }

    typeMessage();
}

function startLoveAnimation() {
    const resultText = document.getElementById('resultText');
    const loveContainer = document.createElement('div');
    loveContainer.style.marginTop = '20px';

    // Love percentage animation
    let percentage = 10;
    const loveText = document.createElement('div');
    loveText.className = 'type-animation';
    loveText.style.fontSize = '1.2em';
    loveText.style.color = '#ff6b9d';
    loveContainer.appendChild(loveText);
    resultText.appendChild(loveContainer);

    function updatePercentage() {
        if (percentage <= 100) {
            loveText.textContent = `I Love You ${percentage}% ❤️`;
            percentage += 10;
            setTimeout(updatePercentage, 200);
        } else {
            loveText.classList.remove('type-animation');

            setTimeout(() => {
                const finalMessage = document.createElement('div');
                finalMessage.innerHTML = '<br><strong style="color: #ff6b9d;">Makasii udah mau jadi pacarku! 😆🫣💐</strong>';
                loveContainer.appendChild(finalMessage);

                startHeartRain();
                document.getElementById('resultSticker').src = 'https://htmlku.com/0/panda/terlope.gif';
            }, 500);
        }
    }
    updatePercentage();
}

function startHeartRain() {
    const heartRain = document.getElementById('loveAnimation');
    heartRain.classList.remove('d-none');

    function createHeart() {
        const heart = document.createElement('div');
        heart.className = 'heart-fall';
        heart.innerHTML = ['❤️', '💕', '💖', '💗', '💘', '💝'][Math.floor(Math.random() * 6)];
        heart.style.left = Math.random() * 100 + 'vw';
        heart.style.animationDuration = (Math.random() * 3 + 2) + 's';
        heart.style.fontSize = (Math.random() * 10 + 15) + 'px';

        document.querySelector('.heart-rain').appendChild(heart);

        setTimeout(() => {
            if (heart.parentNode) {
                heart.parentNode.removeChild(heart);
            }
        }, 5000);
    }

    const heartInterval = setInterval(createHeart, 300);
    setTimeout(() => {
        clearInterval(heartInterval);
    }, 10000);
}

// Stop music when modal is closed
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('scratchCardModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const audio = document.getElementById('foreverYoungAudio');
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
                console.log('🎵 Music stopped and reset');
            }
        });
    }
});
